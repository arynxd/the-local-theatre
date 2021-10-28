import {createContext, MouseEvent, useContext} from "react";
import {getTheme, setTheme, Theme} from "../../util/theme";
import {StylableProps} from "../props/StylableProps";
import sun from '../../assets/sun.png'
import moon from '../../assets/moon (1).png'

interface ThemeContextProps {
    theme: Theme
    setTheme: (theme: Theme) => void
}
export const ThemeContext = createContext<ThemeContextProps>({theme: 'dark', setTheme: (_) => {}})

export default function ThemeToggle(props: StylableProps) {
    const {theme: th, setTheme: setTh} = useContext(ThemeContext)

    const handler = (ev: MouseEvent<HTMLImageElement>): void => {
        if (th === 'light') {
            setTheme('dark')
            setTh('dark')
        }
        else if (getTheme() === 'dark') {
            setTheme('light')
            setTh('light')
        }
        else {
            // Default in case of corruption or tampering
            setTheme('dark')
            setTh('dark')
        }
    }


    if (th === 'dark') {
        return (
            <img src={sun} onClick={handler} className={props.className} alt='Light theme toggle'/>
        )
    }
    else if (th === 'light') {
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