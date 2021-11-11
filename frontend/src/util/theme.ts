import {JSONObject} from "../backend/JSONObject";

export type Theme = 'dark' | 'light'

//TODO unify this API with the react context, or create our own state management through hooks

export type LocalStorage = JSONObject

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