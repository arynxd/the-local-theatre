import { AbstractCache } from './AbstractCache'
import { User } from '../../model/User'

export class UserCache extends AbstractCache<User> {
    constructor() {
        super()
        // Set the prototype explicitly.
        // https://github.com/Microsoft/TypeScript-wiki/blob/main/Breaking-Changes.md#extending-built-ins-like-error-array-and-map-may-no-longer-work
        Object.setPrototypeOf(this, UserCache.prototype)
    }
}
