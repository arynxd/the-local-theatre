/**
 * A type representing a JSON object
 */
export type JSONObject = {
    readonly [key: string]: JSONValue
}

/**
 * A type representing any value JSON value.
 */
export type JSONValue =
    | string
    | number
    | boolean
    | undefined
    | JSONValue[]
    | JSONObject
    | JSONObject[]

/**
 * A type representing an array of json values
 */
export type JSONArray = JSONValue[]

/**
 * Type guard to check whether `value` is an array
 * @param value The value
 * @returns Whether `value` is a JSONValue[]
 */
export function isJSONArray(
    value: JSONValue | JSONValue[]
): value is JSONValue[] {
    // due to the way JSON is structured, we can only really verify that this data is an array
    // any further restriction would cause us to veer too far away from the real structure of JSON
    return Array.isArray(value)
}

/**
 * ype guard to check whether `value` is a JSON object
 *
 * @param value The value
 * @returns Whether `value` is a JSONObject
 */
export function isJSONObject(
    value: JSONValue | JSONObject
): value is JSONObject {
    return (
        typeof value !== 'string' &&
        typeof value !== 'number' &&
        typeof value !== 'boolean' &&
        !Array.isArray(value)
    )
}
