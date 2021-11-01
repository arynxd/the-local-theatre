import {isPost, Post} from "./Post";
import {JSONObject} from "../backend/JSONObject";

/**
 * A comment is fundamentally the same as a post, hence we will join the interfaces
 */
export interface Comment extends Post {
}

export function isComment(json: JSONObject | Comment): json is Comment {
    return isPost(json)
}
