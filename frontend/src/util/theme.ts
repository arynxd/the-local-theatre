export type Theme = 'dark' | 'light'

/**
 * Loads the currently set theme into the DOM. 
 * This will trigger a re-render and thus, tailwind will load the appropriate theme styles.
 */
export function loadTheme() {
    getTheme() === "dark"
        ? document.documentElement.classList.add('dark')
        : document.documentElement.classList.remove('dark')
}

/**
 * Loads the current theme from local storage.
 * 
 * @returns The currently selected theme
 */
export function getTheme(): Theme {
    return localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
        ? 'dark'
        : 'light'
}

/**
 * Sets the theme in local storage
 * This will call loadTheme()
 * 
 * @param theme The new theme
 */
export function setTheme(theme: Theme) {
    localStorage.theme = theme
    loadTheme()
}