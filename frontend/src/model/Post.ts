import {isUser, User} from "./User";
import {isJSONObject, JSONObject, JSONValue} from "../backend/JSONObject";
import {EntityIdentifier, isEntityIdentifier} from "./EntityIdentifier";

export interface Post {
    id: EntityIdentifier,
    author: User,
    content: string,
    createdAt: number
}

export function isPost(json: JSONObject | Post): json is Post {
    return isEntityIdentifier(json.id) &&
        isJSONObject(json.author as JSONValue) && // it will always be a value of some sort
        isUser(json.author as JSONObject) &&      // if its an object ^, we can cast it
        typeof json.content === 'string' &&
        typeof json.createdAt === 'number'
}