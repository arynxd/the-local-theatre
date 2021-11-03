import {Manager} from "./Manager";
import BackendError from "../error/BackendError";
import Routes from "../request/route/Routes";
import {BackendAction} from "../request/BackendAction";
import {BackendController} from "../BackendController";

export type AuthState = 'none' | 'authenticated' | 'signed_out'

/**
 * Manages the authentication state for the app
 * Used to login and logout of accounts
 * 
 * Also stores the token used in each backend request
 */
export class AuthManager extends Manager {
    constructor(backend: BackendController) {
        super(backend);
        this._state = 'none'
        this._token = undefined
    }

    private _state: AuthState

    get state() {
        return this._state
    }

    private _token?: string

    get token() {
        return this._token
    }

    async login(email: string, password: string): Promise<void> {
        if (this._state === 'authenticated') {
            throw new BackendError('Tried to login whilst already being authenticated.')
        }

        const route = Routes.Auth.LOGIN.compile()
        route.withQueryParam('email', email)
        route.withQueryParam('password', password)

        return BackendAction(this.backend, route, res => {
            if (typeof res.token !== 'string') {
                throw new BackendError('Token was not a string')
            }
            this._token = res.token
        })
    }

    async logout(): Promise<void> {
        this._state = 'signed_out'
    }
}