import {StylableProps} from "../props/StylableProps";
import {Post} from "../../model/Post";

interface PostPreviewProps {
    postModel: Post
}

export default function PostPreview(props: StylableProps & PostPreviewProps) {
    return (
        <div className={props.className}>
            <div className='bg-gray-200 rounded shadow-xl p-2 w-1/3'>
                <h1 className='text-2xl'>{props.postModel.title}</h1>
                <hr className='bg-gray-900'/>
                <p>{props.postModel.content.substring(0, 100)}...</p>
            </div>
        </div>
    )
}