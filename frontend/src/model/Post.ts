import {isUser, User} from "./User";
import {isJSONObject, JSONObject, JSONValue} from "../backend/JSONObject";
import {isEntityIdentifier} from "./EntityIdentifier";
import {IdentifiedEntity} from "./IdentifiedEntity";
import {GenericModel} from "./GenericModel";

export interface Post extends IdentifiedEntity, GenericModel {
    author: User,
    title: string,
    content: string,
    createdAt: number
}

export function isPost(json: JSONObject | Post): json is Post {
    const now = new Date()

    return isEntityIdentifier(json.id) &&
        isJSONObject(json.author as JSONValue) && // it will always be a value of some sort
        isUser(json.author as JSONObject) &&      // if its an object ^, we can cast it
        typeof json.title === 'string' &&
        typeof json.content === 'string' &&
        typeof json.createdAt === 'number' &&
        // posts cannot be created in the future (obviously)
        // this also asserts the number is some sort of valid utc
        now.getTime() >= new Date(json.createdAt * 1000).getTime()
}
