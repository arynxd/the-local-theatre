import { NIL, parse } from 'uuid'
import { JSONValue } from '../backend/JSONObject'

export type EntityIdentifier = string

export function emptyIdentifier(): EntityIdentifier {
	return NIL
}

export function isEntityIdentifier(
	id: JSONValue | EntityIdentifier
): id is EntityIdentifier {
	if (typeof id !== 'string') {
		return false
	}

	try {
		parse(id)
		return true
	} catch (ex) {
		return false
	}
}
