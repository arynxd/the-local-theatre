import { IdentifiedEntity } from './IdentifiedEntity'
import { JSONObject } from '../backend/JSONObject'
import { isEntityIdentifier } from './EntityIdentifier'
import { GenericModel } from './GenericModel'

export interface Show extends IdentifiedEntity, GenericModel {
	title: string
	showDate: number
}

export function isShow(json: JSONObject | Show): json is Show {
	return (
		isEntityIdentifier(json.id) &&
		typeof json.title === 'string' &&
		typeof json.showDate === 'number'
	)
}
