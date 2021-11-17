import {BackendController} from "../BackendController";
import {AuthToken} from "../manager/AuthManager";
import {useEffect, useState} from "react";

class AuthHook {
    constructor(
        private readonly backend: BackendController,
        public readonly token: AuthToken | undefined,
    ) {
    }

    public isLoggedIn(): this is { token: string } {
        return !!this.token
    }

    public login(email: string, password: string): Promise<boolean> {
        return this.backend.auth.login(email, password)
    }

    public logout(): Promise<void> {
        return this.backend.auth.logout()
    }
}

export function useAuth(backend: BackendController): AuthHook {
    const [hook, setHook] = useState(new AuthHook(backend, backend.auth.token))

    useEffect(() => {
        setHook(new AuthHook(backend, backend.auth.token))
    }, [backend, backend.auth.state])

    return hook
}