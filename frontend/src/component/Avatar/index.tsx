import {StylableProps} from "../props/StylableProps";
import {User} from "../../model/User";
import {getBackend} from "../../backend/global-scope/util/getters";
import {useAPI} from "../../backend/hook/useAPI";
import {ImgHTMLAttributes} from "react";
import {toURL} from "../../backend/request/mappers";

interface AvatarProps extends StylableProps, ImgHTMLAttributes<HTMLImageElement> {
    user: User
}

export default function Avatar(props: AvatarProps) {
    const {user, ...rest} = props

    const avatar = useAPI(() => getBackend().http.loadAvatar(user).map(toURL))
    const styles = `
        ${props.className}
        animation-pulse rounded-xl bg-gray-300
    `

    if (!avatar) {
        return (
            <div className={styles}/>
        )
    }
    return (
        <img {...rest} src={avatar} alt="User avatar" className={props.className}/>
    )
}