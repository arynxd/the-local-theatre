export function toDate(utc: number): Date {
    return new Date(utc * 1000)
}