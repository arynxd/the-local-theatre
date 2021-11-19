import {Context} from "./Context";
import {assert} from "../../../util/assert";
import BackendError from "../../error/BackendError";
import Routes from "../../request/route/Routes";
import {newBackendAction} from "../../request/BackendAction";

export type AuthState = 'none' | 'authenticated' | 'signed_out'
export type AuthToken = string

const AUTH_KEY = "authorisation"

export class AuthContext extends Context {
    constructor() {
        super()
        this._state = 'none'
        this._token = localStorage.getItem(AUTH_KEY) ?? undefined
    }

    private _state: AuthState

    get state() {
        return this._state
    }

    private _token?: string

    get token(): AuthToken | undefined {
        return this._token
    }

    async login(email: string, password: string): Promise<boolean> {
        assert(() => this._state !== 'authenticated',
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
        return true
    }

    async logout(): Promise<void> {
        this._state = 'signed_out'
        this._token = undefined
    }
}