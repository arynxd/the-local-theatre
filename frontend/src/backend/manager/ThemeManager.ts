import {Manager} from "./Manager";
import {JSONObject} from "../JSONObject";
import {BackendController} from "../BackendController";
import {createContext} from "react";

export type Theme = 'dark' | 'light'
export type LocalStorage = JSONObject

interface ThemeContextProps {
    theme: Theme
    setTheme: (theme: Theme) => void
}

const DEFAULT_PROPS: ThemeContextProps = {
    theme: 'dark',
    setTheme: (_) => {
        throw new TypeError("Default setTheme called, this should never happen")
    }
}

export const ThemeContext = createContext<ThemeContextProps>(DEFAULT_PROPS)


export class ThemeManager extends Manager {
    constructor(backend: BackendController) {
        super(backend);
        this.loadTheme()
    }

    /**
     * Loads the current theme from local storage.
     *
     * @returns Theme the currently selected theme
     */
    get theme(): Theme {
        return localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
            ? 'dark'
            : 'light'
    }

    /**
     * Loads the currently set theme into the DOM.
     * This will trigger a re-render and thus, tailwind will load the appropriate theme styles.
     */
    loadTheme() {
        this.theme === "dark"
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