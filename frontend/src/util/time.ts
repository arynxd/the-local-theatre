/**
 * Converts a UTC epoch-second timestamp into a Date
 * 
 * @param utc The UTC timestamp
 * @returns The date object representing this timestamp
 */
export function toDate(utc: number): Date {
    return new Date(utc * 1000)
}