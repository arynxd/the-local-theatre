import {LocalStorage, Theme} from "../util/theme";
import {EntityIdentifier, isEntityIdentifier} from "./EntityIdentifier";

export interface UserPreferences {
    id: EntityIdentifier
    theme: Theme
}

export function isUserPreferences(obj: LocalStorage | UserPreferences) {
    return typeof obj === 'object' &&
        (obj.theme === 'dark' || obj.theme === 'light') &&
        isEntityIdentifier(obj.id)
}