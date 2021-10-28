import {Comment as CommentModel} from "../../../model/Comment";
import {StylableProps} from "../../props/StylableProps";

interface CommentProps {
    model: CommentModel
}

export function Comment(props: CommentProps & StylableProps) {
    const model = props.model
    const author = model.author

    return (
        <div className={props.className}>
            <div className='bg-gray-100 shadow-xl'>
                <h1 className='text-2xl p-2'>{author.name}</h1>
                <p className='text-xl p-2'>{model.content}</p>
            </div>
        </div>
    )
}