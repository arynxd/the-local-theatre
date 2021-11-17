import {MouseEvent, useContext} from "react";
import {StylableProps} from "../props/StylableProps";
import sun from '../../assets/sun.png'
import moon from '../../assets/moon (1).png'
import {ThemeContext} from "../../backend/manager/ThemeManager";
import {BackendProps} from "../props/BackendProps";

export default function ThemeToggle(props: StylableProps & BackendProps) {
    const {theme, setTheme } = useContext(ThemeContext)
    const themeManager = props.backend.theme

    const handler = (_: MouseEvent<HTMLImageElement>): void => {
        if (theme === 'light') {
            themeManager.setTheme('dark')
            setTheme('dark')
        }
        else if (theme === 'dark') {
            themeManager.setTheme('light')
            setTheme('light')
        }
    }


    if (theme === 'dark') {
        return (
            <img src={sun} onClick={handler} className={props.className} alt='Light theme toggle'/>
        )
    }
    else if (theme === 'light') {
        return (
            <img src={moon} onClick={handler} className={props.className} alt='Dark theme toggle'/>
        )
    }
    else {
        return (
            <img src={moon} onClick={handler} className={props.className} alt='Dark theme toggle'/>
        )
    }
}