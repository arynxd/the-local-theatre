import {MouseEvent, useState} from "react";
import {StylableProps} from "../props/StylableProps";
import sun from '../../assets/sun.png'
import moon from '../../assets/moon (1).png'
import {distinctUntilChanged, map} from "rxjs";
import {useSubscription} from "../../backend/hook/useSubscription";
import {getGlobalScope, getTheme} from "../../backend/global-scope/util/getters";

//TODO investigate desyncing between theme toggle instance
export default function ThemeToggle(props: StylableProps) {
    const gs$$ = getGlobalScope()
    const themeCtx = getTheme()
    const [theme, setTheme] = useState(themeCtx.currentTheme)

    const [theme$] = useState(gs$$.pipe(
        map(state => state.context.theme.currentTheme),
        distinctUntilChanged()
    ))

    useSubscription(theme$, newTheme => setTheme(newTheme))

    const handler = (_: MouseEvent<HTMLImageElement>): void => {
        if (theme === 'light') {
            setTheme('dark')
            themeCtx.setTheme('dark')
        }
        else if (theme === 'dark') {
            setTheme('light')
            themeCtx.setTheme('light')
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