export type Theme = 'dark' | 'light'

export function loadTheme() {
    getTheme() === "dark"
        ? document.documentElement.classList.add('dark')
        : document.documentElement.classList.remove('dark')
}

export function getTheme(): Theme {
    return localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
        ? 'dark'
        : 'light'
}

export function setTheme(theme: Theme) {
    localStorage.theme = theme
    loadTheme()
}