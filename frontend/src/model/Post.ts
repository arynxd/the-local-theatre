import {isUser, User} from "./User";
import {Guid} from "guid-typescript";
import {isJSONObject, JSONObject, JSONValue} from "../backend/JSONObject";

export interface Post {
    id: Guid,
    author: User,
    content: string,
    createdAt: number
}

export function isPost(json: JSONObject | Post): json is Post {
    return Guid.isGuid(json.id) &&
         isJSONObject(json.author as JSONValue) && // it will always be a value of some sort
         isUser(json.author as JSONObject) &&      // if its an object ^, we can cast it
         typeof json.author === 'string' &&
         typeof json.createdAt === 'number'
}