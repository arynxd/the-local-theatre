import {JSONObject} from "../backend/JSONObject";

export default interface User {
    id: number,
    name: string,
    permissions: number,
    dob: number,
    joinDate: number,
    username: string
}

export function isUser(json: JSONObject | User): json is User {
    return typeof json.id          === "number" &&
           typeof json.name        === "string" &&
           typeof json.permissions === "number" &&
           typeof json.dob         === "number" &&
           typeof json.joinDate    === "number" &&
           typeof json.username    === "string"
}