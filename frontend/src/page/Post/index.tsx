import {EntityIdentifier} from "../../model/EntityIdentifier";
import {useParams} from "react-router";
import {useAPI} from "../../backend/hook/useAPI";
import {getBackend} from "../../backend/global-scope/util/getters";
import {toDate} from "../../util/time";
import Avatar from "../../component/Avatar";

export function Post() {
    const id = useParams<{ id: EntityIdentifier }>().id
    const post = useAPI(() => getBackend().http.loadPost(id))

    const formatDate = (unix: number): string => {
        const d = toDate(unix)
        return `${d.getDate()}/${d.getMonth() + 1}/${d.getFullYear()}`
    }

    if (!post) {
        return (
            <p>Loading</p>
        )
    }

    return (
        <div className='w-auto h-auto m-5 bg-gray-200 dark:bg-gray-600 rounded shadow-xl'>
            <h1 className='text-3xl font-bold p-4 pb-2 dark:text-gray-100'>{post.title}</h1>
            <div className='flex flex-row items-center pl-2'>
                <Avatar className='h-10 w-10 p-2' user={post.author} notLoaded={() => <p>Not loaded</p>}/>
                <h2 className='dark:text-gray-200 text-md leading-loose'>{post.author.name}</h2>
            </div>
            <h3 className='pl-4 text-gray-600 dark:text-gray-300 text-sm pb-4'>{formatDate(post.createdAt)}</h3>
            <p className='p-4 text-md dark:text-gray-200 text-black font-medium '>{post.content}</p>
        </div>
    )
}
