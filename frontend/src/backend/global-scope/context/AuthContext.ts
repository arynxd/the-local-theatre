import {Context} from "./Context";
import {assert} from "../../../util/assert";
import BackendError from "../../error/BackendError";
import Routes from "../../request/route/Routes";
import {newBackendAction} from "../../request/BackendAction";
import {BehaviorSubject} from "rxjs";

export type AuthState = 'none' | 'authenticated' | 'signed_out'
export type AuthToken = string

const AUTH_KEY = "authorisation"

export class AuthContext extends Context {
    public readonly observable$$: BehaviorSubject<AuthState>

    constructor() {
        super()
        this._token = localStorage.getItem(AUTH_KEY) ?? undefined
        this.observable$$ = new BehaviorSubject<AuthState>(this.token ? 'authenticated' : 'none')
    }

    private _token?: string

    get token(): AuthToken | undefined {
        return this._token
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

        const newToken = await newBackendAction(route, res => {
            if (typeof res.token !== 'string')
                throw new BackendError('Token was not a string')
            return res.token
        })


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