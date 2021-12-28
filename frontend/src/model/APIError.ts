import { JSONObject } from '../backend/JSONObject'
import { GenericModel } from './GenericModel'

export interface APIError extends GenericModel {
	error: boolean
	message: string
}

export function isAPIError(json: JSONObject | APIError): json is APIError {
	return typeof json.error === 'boolean' && typeof json.message === 'string'
}
