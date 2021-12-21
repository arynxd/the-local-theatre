import {MouseEvent, useCallback, useState} from "react";
import {StylableProps} from "../props/StylableProps";
import {useSubscription} from "../../backend/hook/useSubscription";
import {getTheme} from "../../backend/global-scope/util/getters";
import {Theme} from "../../backend/global-scope/context/ThemeContext";
import { Moon, Sun } from "../Icons";

interface ThemeToggleProps {
    onChange?: (newTheme: Theme) => void
}

export default function ThemeToggle(props: StylableProps & ThemeToggleProps) {
    const themeCtx = getTheme().observable$$
    const [theme, setTheme] = useState<Theme>(themeCtx.value)

    useSubscription(themeCtx, useCallback(newTheme => setTheme(newTheme), []))

    const handler = (): void => {
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
            <button onClick={handler}>
                <Sun className={props.className}/>
            </button>
        )
    }
    else if (theme === 'light') {
        return (
            <button onClick={handler}>
                <Moon className={props.className}/>
            </button>
        )
    }
    else {
        throw new TypeError("Unknown theme " + theme)
    }
}