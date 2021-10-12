export type JSONObject = {
  readonly [key: string]: JSONValue,
};

export type JSONValue = string | number | boolean | null | JSONValue[] | JSONObject | JSONObject[]


