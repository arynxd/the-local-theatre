const AUTH_KEY = "authorisation"

export type AuthToken = string | undefined

export function getToken(): AuthToken {
    return localStorage[AUTH_KEY] ?? undefined
}