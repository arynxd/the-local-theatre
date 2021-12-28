import { Context } from './Context'
import { BehaviorSubject } from 'rxjs'

export type Theme = 'dark' | 'light'

const THEME_KEY = 'theme'

export class ThemeContext extends Context {
	public readonly observable$$: BehaviorSubject<Theme>

	constructor() {
		super()
		this.observable$$ = new BehaviorSubject(this.currentTheme())

		this.observable$$.subscribe((theme) => {
			localStorage.theme = theme
			this.setThemeOnDOM(theme)
		})
	}

	/**
	 * Loads the current theme from local storage.
	 *
	 * @returns Theme the currently selected theme
	 */
	private currentTheme(): Theme {
		return localStorage[THEME_KEY] === 'dark' ||
			(!(THEME_KEY in localStorage) &&
				window.matchMedia('(prefers-color-scheme: dark)').matches)
			? 'dark'
			: 'light'
	}

	/**
	 * Loads the currently set theme into the DOM.
	 * This will trigger a re-render and thus, tailwind will load the appropriate theme styles.
	 */
	private setThemeOnDOM(theme: Theme) {
		theme === 'dark'
			? document.documentElement.classList.add('dark')
			: document.documentElement.classList.remove('dark')
	}
}
