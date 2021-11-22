import {StylableProps} from "../props/StylableProps";
import {User} from "../../model/User";
import {getBackend} from "../../backend/global-scope/util/getters";
import {toURL} from "../../util/image";
import {useAPI} from "../../backend/hook/useAPI";
import {ImgHTMLAttributes} from "react";

interface AvatarProps extends StylableProps, ImgHTMLAttributes<HTMLImageElement>{
    user: User
    notLoaded: () => JSX.Element
}

export default function Avatar(props: AvatarProps) {
    const {user, notLoaded, ...rest} = props

    const avatar = useAPI(() => getBackend().http.loadAvatar(user).map(toURL))

    if (!avatar) {
        return (
            <>{props.notLoaded()}</>
        )
    }
    return (
        <img {...rest} src={avatar} alt="User avatar" className={props.className}/>
    )
}