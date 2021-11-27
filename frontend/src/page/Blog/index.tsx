import {useAPI} from "../../backend/hook/useAPI";
import {getAuth, getBackend} from "../../backend/global-scope/util/getters";
import Post from "../../component/model/Post";
import {createPlaceholders} from "../../util/tsx";
import {useState} from "react";
import Separator from "../../component/Separator";

interface CreatePostProps {
    done: () => void
}

function CreatePostView(props: CreatePostProps) {
    const [title, setTitle] = useState<string>()
    const [content, setContent] = useState<string>()

    const handleSubmitClick = () => {
        //TODO send post to backend
        props.done()
    }

    if (!getAuth().isAuthenticated()) {
        return (
            <></>
        )
    }

    return (
        <div className='w-auto bg-gray-200 rounded shadow-xl m-4 p-2'>
            <div className='w-max'>
                <h2 className='text-lg '>Create a new post</h2>
                <Separator className='w-full mt-1 pb-4'/>
            </div>

            <textarea placeholder='Post title' maxLength={50} minLength={1} onChange={(ev) => setTitle(ev.target.value)}
                      className='w-full p-2 bg-gray-100 h-10 mb-4 rounded shadow-xl'/>
            <textarea placeholder='Post content' maxLength={5000} minLength={1}
                      onChange={(ev) => setContent(ev.target.value)}
                      className='w-full p-2 bg-gray-100 h-24 rounded shadow-xl'/>

            <button onClick={handleSubmitClick}>Submit</button>
        </div>
    )
}

type BlogState = 'view_posts' | 'create_post' | 'submitting_post'

export default function Blog() {
    const [state, setState] = useState<BlogState>('view_posts')
    const PostPlaceholders = () =>
        createPlaceholders(() =>
            <div className='m-5 p-4 bg-gray-200 dark:bg-gray-600 rounded shadow-xl w-full'>
                <div className='w-2/5 h-8 m-2 bg-gray-300 animate-pulse rounded-xl'/>

                <div className='w-full h-4 m-2 mt-4 bg-gray-300 animate-pulse rounded-xl'/>
                <div className='w-full h-4 m-2 mt-4 bg-gray-300 animate-pulse rounded-xl'/>
                <div className='w-full h-4 m-2 mt-4 bg-gray-300 animate-pulse rounded-xl'/>
                <div className='w-full h-4 m-2 mt-4 bg-gray-300 animate-pulse rounded-xl'/>
            </div>
        )

    const posts = useAPI(() => getBackend().http.listPosts())

    if (!posts) {
        return (
            <div className='flex flex-col items-center justify-center mx-4 md:mx-24 lg:mx-44'>{
                PostPlaceholders()
            }</div>
        )
    }


    const handlePostClick = () => {
        setState('create_post')
    }

    const createPostButton = getAuth().isAuthenticated()
        ? <button onClick={handlePostClick}
                  className='absolute top-0 right-0 m-4 bg-blue-200 p-2 px-4 shadow-xl rounded-xl'>Create post
        </button>
        : <></>

    if (state === 'view_posts') {
        return (
            <div className='relative'>
                {createPostButton}
                {posts.length
                    ? <div className='flex flex-col items-center justify-center mx-4 md:mx-24 lg:mx-44'>{
                        posts.map(post => <Post post={post}/>)
                    }</div>
                    //TODO make this a proper GUI element
                    : <p className='absolute'>No posties :(</p>
                }
            </div>
        )
    }
    else if (state === 'create_post') {
        return (
            <CreatePostView done={() => setState('submitting_post')}/>
        )
    }
    else {
        throw new TypeError("Unhandled state " + state)
    }
}
