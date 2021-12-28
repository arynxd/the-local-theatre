import { AbstractCache } from './AbstractCache'
import { Post } from '../../model/Post'

export class PostCache extends AbstractCache<Post> {
	constructor() {
		super()
		// Set the prototype explicitly.
		// https://github.com/Microsoft/TypeScript-wiki/blob/main/Breaking-Changes.md#extending-built-ins-like-error-array-and-map-may-no-longer-work
		Object.setPrototypeOf(this, PostCache.prototype)
	}
}
