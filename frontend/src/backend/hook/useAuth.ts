import {BackendController} from "../BackendController";
import {AuthToken} from "../../component/context/AuthContext";

class AuthHook {
    constructor(
        private readonly backend: BackendController,
        public readonly token: AuthToken | undefined,
    ) { }

    public isLoggedIn(): this is { token: string } {
        return !!this.token
    }

    public login(email: string, password: string): Promise<void> {
        return this.backend.auth.login(email, password)
    }

    public logout(): Promise<void> {
        return this.backend.auth.logout()
    }
}

export function useAuth(backend: BackendController): AuthHook {
    return new AuthHook(backend, backend.auth.token)
}