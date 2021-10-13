import {JSONObject} from '../backend/JSONObject';

export interface APIError {
    error: boolean,
    message: string
}

export function isAPIError(json: JSONObject | APIError): json is APIError {
    return typeof json.error === 'boolean' &&
           typeof json.message === 'string'
}