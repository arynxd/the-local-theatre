import {BackendProps} from "../../component/props/BackendProps";
import {LoadingIcon} from "../../component/LoadingIcon";
import {logger} from "../../util/log";
import {Post} from "../../model/Post";
import {useAPI} from "../../backend/hook/useAPI";
import {BackendController} from "../../backend/BackendController";
import {Link} from "react-router-dom";
import React from "react";
import Separator from "../../component/Separator";

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

interface ActivityProps {
    post: Post,
    backend: BackendController
}

function Activity(props: ActivityProps ) {
    const activityElementStyles = `
        m-5 text-md text-gray-900 dark:text-gray-200
    `

    const boldStyles = `
    `

    const promise = props.backend.http.loadAvatar(props.post.author)
        .then(URL.createObjectURL)

    const avatar = useAPI(promise)
    const post = props.post

    const postURL = `/~20006203/post/${post.id}`
    return (
         <Link to={postURL}>
             <div className='transition duration-300 ease-in-out transform hover:-translate-y-1 hover:bg-blue-200 dark:hover:bg-gray-400 flex items-center bg-blue-100 dark:bg-gray-500 m-2 shadow-2xl rounded-xl'>
                <img className='w-12 h-12 m-2' src={avatar} alt="User avatar"/>
                <p className={activityElementStyles}>
                  <b>{post.author.name}</b> created 1 new post <b>{post.title}</b>
                  <br />
                  <p className='text-sm text-gray-400 dark:text-gray-300'>at {post.createdAt}</p>
                </p>
            </div>
         </Link>

    )
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

    return (
        <div className='md:flex flex-col md:flex-row w-auto h-auto'>
            <div className='w-auto md:w-1/2 h-auto bg-blue-100 dark:bg-gray-500 m-2 p-2 shadow-xl rounded'>
                {/* Recent activity pane  */}
                <h1 className='text-xl font-semibold p-2 text-gray-900 dark:text-gray-200'>Recent Activity</h1>

                <Separator />
                <ul>
                    {posts.map(post =>
                      <Activity post={post} backend={props.backend} />
                    )}
                </ul>
            </div>

            <div className='h-screen w-auto md:w-2/3 bg-blue-400 rounded m-2 shadow-xl'>

            </div>
        </div>
    )
}
