import {BackendProps} from "../../component/props/BackendProps";
import {logger} from "../../util/log";
import {Post} from "../../model/Post";
import {useAPI} from "../../backend/hook/useAPI";
import {BackendController} from "../../backend/BackendController";
import {Link} from "react-router-dom";
import React from "react";
import Separator from "../../component/Separator";
import {toDate} from "../../util/time";

const HOME_PAGE_POST_COUNT = 10

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
        m-4 text-md text-gray-900 dark:text-gray-200
    `

    const promise = props.backend.http.loadAvatar(props.post.author)
        .then(URL.createObjectURL)

    const avatar = useAPI(promise)
    const post = props.post

    const postURL = `/~20006203/post/${post.id}`

    const formatDate = (d: Date): string => {
        return `on ${d.getDate()}/${d.getMonth() + 1}/${d.getFullYear()} at ${d.toLocaleTimeString(undefined, {hour: 'numeric', minute: 'numeric'})}`
    }
    return (
         <Link to={postURL}>
             <div className='transition duration-300 ease-in-out transform hover:-translate-y-1 hover:bg-gray-100 dark:hover:bg-gray-400 flex items-center bg-gray-200 dark:bg-gray-500 m-2 shadow-2xl rounded-xl'>
                 {!avatar ?
                     // avatar hasn't loaded yet
                     <div className='w-12 h-12 m-2 bg-blue-200 dark:bg-gray-400 rounded'/> :
                     // avatar has loaded, display it
                     <img className='w-12 h-12 m-2 ml-5' src={avatar} alt="User avatar"/>
                 }
                <p className={activityElementStyles}>
                  <b>{post.author.name}</b> created 1 new post <b>{post.title}</b>
                  <br />
                  <p className='text-sm text-gray-500 dark:text-gray-300'>{formatDate(toDate(post.createdAt))}</p>
                </p>
            </div>
         </Link>

    )
}

export default function Home(props: BackendProps) {
    // TODO: separate this into components
    // TODO: support different types of activity

    const posts = useAPI(getPost(props.backend))
    logger.debug('Rendering home page')

    if (!posts) {
        logger.debug('Home page post not loaded, rendering loading icon')
    }

    const loaded = () => {
        if (!posts) {
            throw new TypeError("Loaded function called whilst posts was not set?")
        }
        return (
            <>{posts.sort((a, b) => a.createdAt - b.createdAt).map(post =>
              <Activity post={post} backend={props.backend} />
            )}</>
        )
    }

    const notLoaded = () => {
        const elems: JSX.Element[] = []

        for (let i = 0; i < 10; i++) {
            elems[i] = (
                <div className='flex items-center bg-gray-200 dark:bg-gray-500 m-2 shadow-2xl rounded-xl'>
                    <div className='w-12 h-12 m-2 bg-blue-200 dark:bg-gray-400 rounded'/>

                    <div className='w-full h-full'>
                        <div className='w-auto h-4 m-2 bg-blue-200 dark:bg-gray-400 rounded'/>
                        <div className='w-auto h-4 m-2 bg-blue-200 dark:bg-gray-400 rounded'/>
                    </div>
                </div>
            )
        }
        return elems
    }

    return (
        <div className='md:flex flex-col md:flex-row w-auto h-auto'>
            <div className='w-auto md:w-2/5 h-full md:h-screen bg-gray-300 dark:bg-gray-500 m-2 p-2 shadow-2xl rounded'>
                {/* Recent activity pane  */}
                <h1 className='text-xl font-semibold p-2 text-gray-900 dark:text-gray-200'>Recent Activity</h1>
                <Separator />

                <ul>
                    {!posts ? notLoaded() : loaded()}
                </ul>
            </div>

            <div className='w-auto md:w-2/3 h-full md:h-screen bg-gray-300 dark:bg-gray-500 m-2 p-2 shadow-2xl rounded'>
                <h1  className='text-5xl text-center font-bold mb-10'>PLACEHOLDER</h1>
                <h1  className='text-5xl text-center font-bold mb-10'>PLACEHOLDER</h1>
                <h1  className='text-5xl text-center font-bold mb-10'>PLACEHOLDER</h1>
                <h1  className='text-5xl text-center font-bold mb-10'>PLACEHOLDER</h1>
                <h1  className='text-5xl text-center font-bold mb-10'>PLACEHOLDER</h1>
                <h1  className='text-5xl text-center font-bold mb-10'>PLACEHOLDER</h1>
            </div>
        </div>
    )
}
