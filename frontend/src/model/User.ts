import {JSONObject} from "../backend/JSONObject";
import {isEntityIdentifier} from "./EntityIdentifier";
import {PermissionValue} from "./Permission";
import {IdentifiedEntity} from "./IdentifiedEntity";
import {GenericModel} from "./GenericModel";

export interface User extends IdentifiedEntity, GenericModel{
    name: string,
    permissions: PermissionValue,
    dob: number,
    joinDate: number,
    username: string
}

export function isUser(json: JSONObject | User): json is User {
    return isEntityIdentifier(json.id) &&
        typeof json.name === "string" &&
        typeof json.permissions === "number" &&
        typeof json.dob === "number" &&
        typeof json.joinDate === "number" &&
        typeof json.username === "string"
}