import {Manager} from "./Manager";
import BackendError from "../error/BackendError";
import Routes from "../request/route/Routes";
import {BackendAction} from "../request/BackendAction";
import {BackendController} from "../BackendController";
import {assert} from "../../util/assert";

export type AuthState = 'none' | 'authenticated' | 'signed_out'
export type AuthToken = string

const AUTH_KEY = "authorisation"

/**
 * Manages the authentication state for the app
 * Used to login and logout of accounts
 * 
 * Also stores the token used in each backend request
 */
export class AuthManager extends Manager {
    private _state: AuthState

    get state() {
        return this._state
    }

    private _token?: string

    get token(): AuthToken | undefined {
        return this._token
    }


    constructor(backend: BackendController) {
        super(backend);
        this._state = 'none'
        this._token = localStorage.getItem(AUTH_KEY) ?? undefined
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

        const newToken = await BackendAction(this.backend, route, res => {
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
