import {Context} from "./Context";

export type Theme = 'dark' | 'light'

export class ThemeContext extends Context {
    constructor() {
        super();
        this.loadTheme()
    }

    /**
     * Loads the current theme from local storage.
     *
     * @returns Theme the currently selected theme
     */
    get currentTheme(): Theme {
        return localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
            ? 'dark'
            : 'light'
    }

    /**
     * Loads the currently set theme into the DOM.
     * This will trigger a re-render and thus, tailwind will load the appropriate theme styles.
     */
    loadTheme() {
        this.currentTheme === "dark"
            ? document.documentElement.classList.add('dark')
            : document.documentElement.classList.remove('dark')
    }

    /**
     * Sets the theme in local storage
     * This will call loadTheme()
     *
     * @param theme The new theme
     */
    setTheme(theme: Theme) {
        localStorage.theme = theme
        this.loadTheme()
    }
}