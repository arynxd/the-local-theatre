import {User} from "../../model/User";

interface PostProps {
    content: string,
    author: User,
}


export function Post(props: PostProps) {
    return (
        <>
            <h3>{props.author.name}</h3>
            <p>{props.content}</p>
        </>
    )
}