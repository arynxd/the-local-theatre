
import { useEffect, useState} from "react";
import {BackendProps} from "../../component/props/BackendProps";
import {LoadingIcon} from "../../component/LoadingIcon";
import {logger} from "../../util/log";
import {Post} from "../../model/Post";
import PostElement from "../../component/model/Post";
const HOME_PAGE_POST_COUNT = 1

function getPost(props: BackendProps, setPosts: (posts: Post[]) => void) {
   props.backend.http.listPosts(1)
        .then(posts => {
            if (posts) {
                setPosts(posts.slice(0, HOME_PAGE_POST_COUNT))
                logger.debug('Received valid post ' + JSON.stringify(posts))
            } else {
                logger.debug('No posts received')
                throw new TypeError('No posts received')
            }
        })
}
export default function Home(props: BackendProps) {
    const [posts, setPosts] = useState<Post[]>([])

    logger.debug('Rendering home page')

    useEffect(() => {
        logger.debug('Sending request for home page post')
        getPost(props, setPosts)
    }, [props])

    if (!posts) {
        logger.debug('Home page post not loaded, rendering loading icon')
        return (
            <LoadingIcon/>
        )
    }

    const styles = `
        justify-center items-center flex
        md:justify-start md:items-start m-4
    `
    return (
        <>
            <p className='bg-clip-padding font-bold text-center p-2 m-4 text-4xl bg-gray-100 shadow-xl rounded'>Latest Announcements</p>
            <div className='grid grid-cols-1 grid-flow-row gap-2'>
                {
                    posts.map(post =>
                        <PostElement key={post.id} className={styles} postModel={post} backend={props.backend}/>
                    )
                }
            </div>
        </>
    )
}
