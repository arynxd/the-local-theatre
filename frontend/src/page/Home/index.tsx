import {BackendProps} from "../../component/props/BackendProps";
import {LoadingIcon} from "../../component/LoadingIcon";
import {logger} from "../../util/log";
import {Post} from "../../model/Post";
import {useAPI} from "../../backend/hook/useAPI";
import {BackendController} from "../../backend/BackendController";
import {PostPreview} from "../../component/model/PostPreview";

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

    logger.debug('Rendering home page')

    if (!posts) {
        logger.debug('Home page post not loaded, rendering loading icon')
        return (
            <LoadingIcon/>
        )
    }

    const styles = `
        md:justify-start md:items-start m-4
    `
    return (
        <>
            <p className='bg-clip-padding font-bold text-center p-2 m-4 text-4xl bg-gray-100 dark:bg-gray-500 dark:text-gray-100 shadow-xl rounded'>Latest
                Announcements</p>
            <div className='grid grid-cols-1 grid-flow-row gap-2'>
                {
                    posts.map(post =>
                        <PostPreview key={post.id} className={styles} backend={props.backend} model={post}/>
                    )
                }
            </div>
        </>
    )
}
