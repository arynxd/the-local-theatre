export function initThemes() {
    getTheme() === "dark"
        ? document.documentElement.classList.add('dark')
        : document.documentElement.classList.remove('dark')
}

export function getTheme(): 'dark' | 'light' {
    return localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
        ? 'dark'
        : 'light'
}