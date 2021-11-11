import {Manager} from "./Manager";
import BackendError from "../error/BackendError";
import Routes from "../request/route/Routes";
import {BackendAction} from "../request/BackendAction";
import {BackendController} from "../BackendController";
import {assert} from "../../util/assert";
import {getToken} from "../../component/context/AuthContext";

export type AuthState = 'none' | 'authenticated' | 'signed_out'

export class AuthManager extends Manager {
    constructor(backend: BackendController) {
        super(backend);
        this._state = 'none'
        this._token = getToken()
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
        assert(() => this._state !== 'authenticated',
              () => new BackendError('Tried to login whilst already being authenticated.')
        )

        const route = Routes.Auth.LOGIN.compile()
        route.withQueryParam('email', email)
        route.withQueryParam('password', password)

        const newToken = await BackendAction(this.backend, route, res => {
            if (typeof res.token !== 'string') {
                throw new BackendError('Token was not a string')
            }
            return res.token
        })

        this._token = newToken
    }

    async logout(): Promise<void> {
        this._state = 'signed_out'
    }
}