import {useAPI} from "../../backend/hook/useAPI";
import {getBackend} from "../../backend/global-scope/util/getters";
import Post from "../../component/model/Post";
import {createPlaceholders} from "../../util/tsx";


export default function Blog() {
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
    return (
        <div className='flex flex-col items-center justify-center mx-4 md:mx-24 lg:mx-44'>{
            posts.map(post => <Post post={post}/>)
        }</div>
    )
}
