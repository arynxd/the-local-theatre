import {Context} from "./Context";
import {assert} from "../../../util/assert";
import BackendError from "../../error/BackendError";
import Routes from "../../request/route/Routes";
import {BackendAction} from "../../request/BackendAction";
import {BehaviorSubject} from "rxjs";
import {getBackend} from "../util/getters";
import {fromPromise, toJSON} from "../../request/mappers";
import {User} from "../../../model/User";

export type AuthState = 'unauthenticated' | 'authenticated'
export type AuthToken = string

const AUTH_KEY = "authorisation"

export interface SignupObj {
    firstName: string,
    lastName: string,
    username: string,
    email: string,
    dob: Date,
    password: string
}

export class AuthContext extends Context {
    public readonly observeAuth$$: BehaviorSubject<AuthState>
    public readonly observeUser$$: BehaviorSubject<User | undefined>

    constructor() {
        super()
        this._token = localStorage.getItem(AUTH_KEY) ?? undefined
        this.observeUser$$ = new BehaviorSubject<User | undefined>(undefined)

        let currentState: AuthState = 'unauthenticated'
        if (this._token && this._token !== 'null') {
            currentState = 'authenticated'
        }
        this.observeAuth$$ = new BehaviorSubject<AuthState>(currentState)

        //FIXME remove this hack lol
        setTimeout(() => {
            if (this.isAuthenticated()) {
                this.loadSelfUser()
            }
        }, 500)
    }

    private _token?: string

    get token(): AuthToken | undefined {
        return this._token
    }

    loadSelfUser(): BackendAction<User> {
        return fromPromise(this.loadSelfUser0())
    }

    async login(email: string, password: string): Promise<boolean> {
        assert(() => !this.isAuthenticated(),
            () => new BackendError('Tried to login whilst already being authenticated.')
        )

        const route = Routes.Auth.LOGIN.compile()
        route.withBody({
            data: {
                email,
                password
            }
        })

        const newToken = await BackendAction.new(route)
            .flatMap(toJSON)
            .map(res => res.token)
            .assertTypeOf('string')


        this._token = newToken
        localStorage[AUTH_KEY] = newToken
        this.observeAuth$$.next('authenticated')
        this.observeUser$$.next(await this.loadSelfUser())
        return true
    }

    async signup(obj: SignupObj): Promise<string> {
        assert(() => !this.isAuthenticated(),
            () => new BackendError('Tried to sign up whilst already being authenticated.')
        )

        const route = Routes.Auth.SIGNUP.compile()
        route.withBody({
                data: {
                    firstName: obj.firstName,
                    lastName: obj.lastName,
                    username: obj.username,
                    email: obj.email,
                    dob: Math.floor(obj.dob.getTime() / 1000),
                    password: obj.password
                }
            }
        )

        // TODO handle error cases when signing up
        const tok = await BackendAction.new(route)
            .flatMap(toJSON)
            .map(res => res.token)
            .assertTypeOf('string')

        this._token = tok
        localStorage[AUTH_KEY] = tok
        this.observeAuth$$.next('authenticated')
        return 'success'
    }

    logout(): void {
        this.observeAuth$$.next('unauthenticated')
        this._token = undefined
        localStorage[AUTH_KEY] = null
    }

    isAuthenticated() {
        return this.observeAuth$$.value === 'authenticated' && !!this._token
    }

    private async loadSelfUser0(): Promise<User> {
        assert(() => this.isAuthenticated(),
            () => new BackendError('Tried to load self user without being authenticated')
        )

        if (this._token === undefined) {
            throw new BackendError('Tried to load self user without a token present')
        }

        const user = await getBackend().http.loadSelfUser()
        this.observeUser$$.next(user)
        return user;
    }
}