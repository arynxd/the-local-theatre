import {JSONObject} from "../backend/JSONObject";
import {Guid} from "guid-typescript";
import {EntityIdentifier} from "./EntityIdentifier";

export interface User {
    id: EntityIdentifier,
    name: string,
    permissions: number,
    dob: number,
    joinDate: number,
    username: string
}

export function isUser(json: JSONObject | User): json is User {
    return Guid.isGuid(json.id) &&
        typeof json.name === "string" &&
        typeof json.permissions === "number" &&
        typeof json.dob === "number" &&
        typeof json.joinDate === "number" &&
        typeof json.username === "string"
}