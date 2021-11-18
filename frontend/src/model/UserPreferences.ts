import {EntityIdentifier, isEntityIdentifier} from "./EntityIdentifier";
import {LocalStorage, Theme} from "../backend/manager/ThemeManager";
import {GenericModel} from "./GenericModel";

export interface UserPreferences extends GenericModel  {
    userId: EntityIdentifier
    theme: Theme
}

export function isUserPreferences(obj: LocalStorage | UserPreferences) {
    return typeof obj === 'object' &&
        (obj.theme === 'dark' || obj.theme === 'light') &&
        isEntityIdentifier(obj.userId)
}