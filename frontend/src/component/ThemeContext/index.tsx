import React, {useState} from "react";
import {getTheme, initThemes} from "../../util/theme";
import {MouseEvent} from "react";

export function ThemeContext(props: { children: JSX.Element[] }) {
    const [theme, setTheme] = useState(getTheme())
    const sideBarToggle = (_: MouseEvent<HTMLButtonElement | HTMLDivElement>): void => {
        if (theme === 'dark') {
            localStorage.theme = 'light'
            setTheme('light')
        }
        else if (theme === 'light') {
            localStorage.theme = 'dark'
            setTheme('dark')
        }
        initThemes()
    }

    return (
        <>
            <button className='' onClick={sideBarToggle}>Theme Toggle</button>
            {props.children}
        </>
    )
}