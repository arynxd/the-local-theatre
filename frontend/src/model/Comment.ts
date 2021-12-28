import { isJSONObject, JSONObject, JSONValue } from '../backend/JSONObject'
import { GenericModel } from './GenericModel'
import { EntityIdentifier, isEntityIdentifier } from './EntityIdentifier'
import { IdentifiedEntity } from './IdentifiedEntity'
import { isUser, User } from './User'

export interface Comment extends GenericModel, IdentifiedEntity {
	author: User
	title: string
	content: string
	createdAt: number
	postId: EntityIdentifier
	editedAt: number | undefined
}

export function isComment(json: JSONObject | Comment): json is Comment {
	const now = new Date()

	return isEntityIdentifier(json.id) &&
		isEntityIdentifier(json.postId) &&
		isJSONObject(json.author as JSONValue) && // it will always be a value of some sort
		isUser(json.author as JSONObject) && // if its an object ^, we can cast it
		typeof json.content === 'string' &&
		typeof json.createdAt === 'number' &&
		// posts cannot be created in the future (obviously)
		// this also asserts the number is some sort of valid utc
		now.getTime() >= new Date(json.createdAt * 1000).getTime() &&
		json.editedAt
		? typeof json.editedAt == 'number'
			? true
			: false
		: true
}
