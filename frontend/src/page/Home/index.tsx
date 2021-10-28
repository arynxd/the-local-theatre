
import { useEffect, useState} from "react";
import {BackendProps} from "../../component/props/BackendProps";
import {LoadingIcon} from "../../component/LoadingIcon";
import {logger} from "../../util/log";
import {Post} from "../../model/Post";
import PostElement from "../../component/model/PostElement";

function getPost(props: BackendProps, setPost: (post: Post) => void) {
   props.backend.http.listPosts(1)
        .then(posts => {
            if (posts) {
                const post = posts[0]

                setPost(post)

                logger.debug('Received valid post ' + JSON.stringify(post))
            } else {
                logger.debug('No posts received')
                throw new TypeError('No posts received')
            }
        })
}
export default function Home(props: BackendProps) {
    const [post, setPost] = useState<Post>()

    logger.debug('Rendering home page')

    useEffect(() => {
        logger.debug('Sending request for home page post')
        getPost(props, setPost)
    }, [props])

    if (!post) {
        logger.debug('Home page post not loaded, rendering loading icon')
        return (
            <LoadingIcon/>
        )
    }

    const styles = `
        justify-center items-center flex
        lg:justify-start lg:items-start m-4
    `
    return (
        <>
            <div className='grid grid-cols-1 grid-flow-row gap-2'>
                <PostElement className={styles} postModel={post} backend={props.backend}/>
                <PostElement className={styles} postModel={post} backend={props.backend}/>
                <PostElement className={styles} postModel={post} backend={props.backend}/>

            </div>
        </>
    )
}
