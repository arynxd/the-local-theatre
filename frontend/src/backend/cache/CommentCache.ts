import { Comment } from "../../model/Comment";
import {AbstractCache} from "./AbstractCache";

export class CommentCache extends AbstractCache<Comment> {
    constructor() {
        super();
        // Set the prototype explicitly.
        // https://github.com/Microsoft/TypeScript-wiki/blob/main/Breaking-Changes.md#extending-built-ins-like-error-array-and-map-may-no-longer-work
        Object.setPrototypeOf(this, CommentCache.prototype);
    }
}