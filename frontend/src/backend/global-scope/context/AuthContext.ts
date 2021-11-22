import {Context} from "./Context";
import {assert} from "../../../util/assert";
import BackendError from "../../error/BackendError";
import Routes from "../../request/route/Routes";
import {BackendAction} from "../../request/BackendAction";
import {BehaviorSubject} from "rxjs";
import {getBackend} from "../util/getters";
import {toJSON} from "../../request/mappers";
import {User} from "../../../model/User";

export type AuthState = 'none' | 'authenticated' | 'signed_out'
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
    public readonly observable$$: BehaviorSubject<AuthState>
    private _token?: string

    get token(): AuthToken | undefined {
        return this._token
    }

    constructor() {
        super()
        this._token = localStorage.getItem(AUTH_KEY) ?? undefined
        this.observable$$ = new BehaviorSubject<AuthState>(!!this._token ? 'authenticated' : 'none')
    }

    loadSelfUser(): BackendAction<User> {
        assert(() => this.observable$$.value === 'authenticated',
            () => new BackendError('Tried to load self user without being authenticated')
        )

        if (this._token === undefined) {
            throw new BackendError('Tried to load self user without a token present')
        }

        return getBackend().http.loadSelfUser();
    }

    async login(email: string, password: string): Promise<boolean> {
        assert(() => this.observable$$.value !== 'authenticated',
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

        this.observable$$.next('authenticated')
        return true
    }

    async signup(obj: SignupObj): Promise<string> {
        assert(() => this.observable$$.value !== 'authenticated',
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
        this.observable$$.next('authenticated')
        return 'success'
    }

    logout(): void {
        this.observable$$.next('signed_out')
        this._token = undefined
        localStorage[AUTH_KEY] = null
    }

    isAuthenticated() {
        return this.observable$$.value === 'authenticated'
    }
}