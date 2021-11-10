import {BackendProps} from "../../component/props/BackendProps";
import {logger} from "../../util/log";
import {Post} from "../../model/Post";
import PostPreview from '../../component/PostPreview'
import {BackendController} from "../../backend/BackendController";
import {useAPI} from "../../backend/hook/useAPI";

const HOME_PAGE_POST_COUNT = 3

async function getPost(backend: BackendController): Promise<Post[]> {
    const posts = await backend.http.listPosts(1)
    if (posts) {
        logger.debug('Received valid post ' + JSON.stringify(posts))
        return posts.slice(0, HOME_PAGE_POST_COUNT)
    }
    else {
        logger.debug('No posts received')
        throw new TypeError('No posts received')
    }
}

export default function Home(props: BackendProps) {
    const posts = useAPI(getPost(props.backend))

    if (!posts) {
        return (
            <></>
        )
    }

    return (
        <>
            <div className='grid grid-cols-1 grid-rows-2'>
                <div className='grid grid-cols-1 m-2 gap-2 md:w-1/3'>
                    {posts.map(post => <PostPreview className='w-auto' postModel={post}/>)}
                </div>
            </div>
        </>
    )
}
