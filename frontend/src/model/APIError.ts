import {isJSONObject, JSON} from '../backend/JSON';

export default interface APIError {
    error: boolean,
    message: string
}

export function isAPIError(json: JSON): json is APIError {
    if (!isJSONObject(json)) {
        return false
    }

    const message = json['message']
    const error = json['error']

    return message !== null &&
           error !== null &&
           typeof message === 'string' &&
           typeof error === 'boolean'
}