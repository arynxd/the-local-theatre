import {useAPI} from '../../backend/hook/useAPI'
import {Post} from '../../model/Post'
import {LoadingIcon} from '../LoadingIcon'
import {BackendProps} from '../props/BackendProps'
import {StylableProps} from '../props/StylableProps'

interface PostPreviewProps {
    model: Post
}

export function PostPreview(props: PostPreviewProps & StylableProps & BackendProps) {
    const avatar = useAPI(props.backend.http.loadAvatar(props.model.author))
   
    let avatarJSX;

    if (!avatar) {
        avatarJSX = <LoadingIcon />
    }
    else {
        avatarJSX = <img className='w-6 h-6' src={URL.createObjectURL(avatar)} alt='User avatar'/>
    }

    return (
        <div className={props.className}>
            <div className='p-2 m-2 w-full bg-gray-200 shadow-lg rounded'>
                <h1 className='text-4xl bold'>{props.model.title}</h1> 
            </div>
            <div className=''>
                {avatarJSX}
                <h2>{props.model.author.name}</h2>
            </div>
        </div>
    )
}
