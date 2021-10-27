import React, {useState, MouseEvent} from "react";
import {getTheme, initThemes} from "../../util/theme";
import {logger} from "../../util/log";

export default function ThemeContext(props: { children: JSX.Element[] }) {
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
        logger.debug('Toggled theme to ' + getTheme())
        initThemes()
    }

    return (
        <>
            <button className='text-xl rounded-2xl border-4 bottom-0 fixed' onClick={sideBarToggle}>Theme Toggle</button>
            {props.children}
        </>
    )
}