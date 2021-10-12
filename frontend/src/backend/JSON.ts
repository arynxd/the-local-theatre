export type JSON = {
  readonly [key: string]: JSONValue,
};

export type JSONValue = (string | number | boolean | null | JSONValue[]) & JSON

export function isJSONValue(json: JSON): json is JSONValue {
  return json && (typeof json != 'object' || Array.isArray(json));
}

export function isJSONObject(json: JSON): json is JSON {
  return !isJSONValue(json)
}

