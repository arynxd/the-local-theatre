import {JSONObject} from "../backend/JSONObject";
import {User,isUser} from './User'

export interface SelfUser extends User {
    email: string
}

export function isSelfUser(json: JSONObject | SelfUser): json is SelfUser {
    return isUser(json) &&
        typeof json.email === 'string'
}