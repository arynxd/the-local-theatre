export type JSONObject = {
  readonly [key: string]: JSONValue,
};

export type JSONValue = string | number | boolean | null | JSONValue[] | JSONObject | JSONObject[]

export function isJSONArray(value: JSONValue | JSONValue[]): value is JSONValue[] {
  return Array.isArray(value)
}

export function isJSONObject(value: JSONValue | JSONObject): value is JSONObject {
  return typeof value !== 'string' &&
         typeof value !== 'number' &&
         typeof value !== 'boolean' &&
         !Array.isArray(value)
}