import {EntityIdentifier} from "../../model/EntityIdentifier";
import {useParams} from "react-router";
import {useAPI} from "../../backend/hook/useAPI";
import {getBackend} from "../../backend/global-scope/util/getters";
import PostElement from "../../component/model/Post";

export function Post() {
    const id = useParams<{ id: EntityIdentifier }>().id
    const post = useAPI(() => getBackend().http.loadPost(id))

    if (!post) {
        return (
            <div
                className='flex items-center bg-gray-200 dark:bg-gray-500 m-2 shadow-2xl rounded-xl mx-4 md:mx-24 lg:mx-44'>
                <div className='w-full h-full animate-pulse m-2'>
                    <div className='w-3/5 h-5 m-2 mb-4 bg-gray-300 dark:bg-gray-400 rounded'/>

                    <div className='w-auto h-4 m-2 bg-gray-300 dark:bg-gray-400 rounded'/>
                    <div className='w-auto h-4 m-2 bg-gray-300 dark:bg-gray-400 rounded'/>
                    <div className='w-auto h-4 m-2 bg-gray-300 dark:bg-gray-400 rounded'/>
                    <div className='w-auto h-4 m-2 bg-gray-300 dark:bg-gray-400 rounded'/>
                    <div className='w-auto h-4 m-2 bg-gray-300 dark:bg-gray-400 rounded'/>
                    <div className='w-auto h-4 m-2 bg-gray-300 dark:bg-gray-400 rounded'/>
                </div>
            </div>
        )
    }


    return (
        <div className='flex flex-col items-center justify-center mx-4 md:mx-24 lg:mx-44'>
            <PostElement post={post}/>
        </div>
    )
}
