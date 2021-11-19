import {isPost, Post} from "./Post";
import {JSONObject} from "../backend/JSONObject";
import {GenericModel} from "./GenericModel";

/**
 * A comment is fundamentally the same as a post, hence we will join the interfaces
 */
export interface Comment extends Post, GenericModel {
}

export function isComment(json: JSONObject | Comment): json is Comment {
    return isPost(json)
}
