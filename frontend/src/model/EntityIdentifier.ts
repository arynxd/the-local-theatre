import {Guid} from "guid-typescript";
import {JSONValue} from "../backend/JSONObject";

export type EntityIdentifier = Guid

export function emptyIdentifier(): EntityIdentifier {
    return Guid.createEmpty()
}

export function isEntityIdentifier(id: JSONValue | EntityIdentifier): id is EntityIdentifier {
    return Guid.isGuid(id)
}

export function parseIdentifier(id: string): EntityIdentifier {
    return Guid.parse(id)
}