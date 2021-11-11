const AUTH_KEY = "authorisation"

export type AuthToken = string

export function getToken(): AuthToken {
    return localStorage[AUTH_KEY] ?? undefined
}