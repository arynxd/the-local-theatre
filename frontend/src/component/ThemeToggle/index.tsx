import {MouseEvent, useCallback, useState} from "react";
import {StylableProps} from "../props/StylableProps";
import sun from '../../assets/sun.png'
import moon from '../../assets/moon (1).png'
import {useSubscription} from "../../backend/hook/useSubscription";
import {getTheme} from "../../backend/global-scope/util/getters";
import {Theme} from "../../backend/global-scope/context/ThemeContext";

interface ThemeToggleProps {
    onChange?: (newTheme: Theme) => void
}

export default function ThemeToggle(props: StylableProps & ThemeToggleProps) {
    const themeCtx = getTheme().observable$$
    const [theme, setTheme] = useState<Theme>(themeCtx.value)

    useSubscription(themeCtx, useCallback(newTheme => setTheme(newTheme), []))

    const handler = (_: MouseEvent<HTMLImageElement>): void => {
        if (theme === 'light') {
            setTheme('dark')
            themeCtx.next('dark')
            props.onChange?.('light')
        }
        else if (theme === 'dark') {
            setTheme('light')
            themeCtx.next('light')
            props.onChange?.('light')
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
        throw new TypeError("Unknown theme " + theme)
    }
}