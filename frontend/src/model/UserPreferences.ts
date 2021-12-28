import { EntityIdentifier, isEntityIdentifier } from './EntityIdentifier'
import { GenericModel } from './GenericModel'
import { Theme } from '../backend/global-scope/context/ThemeContext'
import { LocalStorage } from '../backend/global-scope/GlobalScope'

export interface UserPreferences extends GenericModel {
	userId: EntityIdentifier
	theme: Theme
}

export function isUserPreferences(obj: LocalStorage | UserPreferences) {
	return (
		typeof obj === 'object' &&
		(obj.theme === 'dark' || obj.theme === 'light') &&
		isEntityIdentifier(obj.userId)
	)
}
