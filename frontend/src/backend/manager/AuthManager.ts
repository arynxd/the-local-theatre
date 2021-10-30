import {Manager} from "./Manager";
import BackendError from "../error/BackendError";
import Routes from "../request/route/Routes";
import {BackendAction} from "../request/BackendAction";
import {BackendController} from "../BackendController";

export type AuthState = 'none' | 'authenticated' | 'signed_out'

export class AuthManager extends Manager {
    private _state: AuthState
    private _token?: string

    get state() {
        return this._state
    }

    get token() {
        return this._token
    }

    constructor(backend: BackendController) {
        super(backend);
        this._state = 'none'
        this._token = undefined
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