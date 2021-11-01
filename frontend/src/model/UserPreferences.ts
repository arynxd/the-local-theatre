import {Theme} from "../util/theme";
import {EntityIdentifier, isEntityIdentifier} from "./EntityIdentifier";

export interface UserPreferences {
    id: EntityIdentifier
    theme: Theme
}

export function isUserPreferences(obj: any | UserPreferences) {
    return typeof obj === 'object' &&
        (obj.theme === 'dark' || obj.theme === 'light') &&
        isEntityIdentifier(obj.id)
}