import {JSONObject} from "../backend/JSONObject";
import {Guid} from "guid-typescript";

export interface User {
    id: Guid,
    name: string,
    permissions: number,
    dob: number,
    joinDate: number,
    username: string
}

export const LoadingUser: User = {
    dob: 0,
    id: Guid.createEmpty(),
    joinDate: 0,
    name: "",
    permissions: 0,
    username: ""
}

export function isUser(json: JSONObject | User): json is User {
    return Guid.isGuid(json.id)                 &&
           typeof json.name        === "string" &&
           typeof json.permissions === "number" &&
           typeof json.dob         === "number" &&
           typeof json.joinDate    === "number" &&
           typeof json.username    === "string"
}