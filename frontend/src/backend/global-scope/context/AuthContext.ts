import {Context} from "./Context";
import {assert} from "../../../util/assert";
import BackendError from "../../error/BackendError";
import Routes from "../../request/route/Routes";
import {BackendAction} from "../../request/BackendAction";
import {BehaviorSubject} from "rxjs";
import {getBackend} from "../util/getters";
import {toJSON} from "../../request/mappers";

export type AuthState = 'none' | 'authenticated' | 'signed_out'
export type AuthToken = string

const AUTH_KEY = "authorisation"

export class AuthContext extends Context {
    public readonly observable$$: BehaviorSubject<AuthState>
    private _token?: string

    get token(): AuthToken | undefined {
        return this._token
    }

    constructor() {
        super()
        this._token = localStorage.getItem(AUTH_KEY) ?? undefined
        this.observable$$ = new BehaviorSubject<AuthState>(this.token != null ? 'authenticated' : 'none')
    }

    async loadSelfUser() {
        assert(() => this.observable$$.value === 'authenticated',
            () => new BackendError('Tried to load self user without being authenticated')
        )

        if (this._token === undefined) {
            throw new BackendError('Tried to load self user without a token present')
        }

        return await getBackend().http.loadSelfUser(this._token)
    }

    async login(email: string, password: string): Promise<boolean> {
        assert(() => this.observable$$.value !== 'authenticated',
            () => new BackendError('Tried to login whilst already being authenticated.')
        )

        const hash = (inp: string): string => {
            return inp
        }

        const route = Routes.Auth.LOGIN.compile()
        route.withQueryParam('email', email)
        route.withQueryParam('password', hash(password))

        const newToken = await BackendAction.new(route)
            .flatMap(toJSON)
            .map(res => res.token)
            .throwIfTypeIsnt('string')


        this._token = newToken
        localStorage[AUTH_KEY] = newToken

        this.observable$$.next('authenticated')
        return true
    }

    async logout(): Promise<void> {
        this.observable$$.next('signed_out')
        this._token = undefined
        localStorage[AUTH_KEY] = undefined
    }
}