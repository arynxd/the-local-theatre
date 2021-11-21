export const PASSWORD_STRENGTH_REGEX = /(?=^.{8,}$)(?=.*\d)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/

export function isStrongPassword(pwd: string): boolean {
    return PASSWORD_STRENGTH_REGEX.test(pwd)
}