import {BackendController} from "../BackendController";
import {AuthToken} from "../../component/context/AuthContext";

interface AuthHook {
    token: AuthToken,
    login(email: string, password: string): Promise<void>,
    logout(): Promise<void>,
}

export function useAuth(backend: BackendController): AuthHook {
    const tok = backend.auth.token

    return {
        token: tok,
        login: backend.auth.login,
        logout: backend.auth.logout
    }
}

export function isLoggedIn(auth: AuthHook): boolean {
    // TODO: improve this API so that we can assert token will be present after this function call
    return !!auth.token
}