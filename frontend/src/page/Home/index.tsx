import {logger} from "../../util/log";
import {Post} from "../../model/Post";
import {useAPI} from "../../backend/hook/useAPI";
import {Link} from "react-router-dom";
import React from "react";
import Separator from "../../component/Separator";
import {toDate} from "../../util/time";
import {User} from "../../model/User";
import {Show} from "../../model/Show";
import {toURL} from "../../util/image";
import {BackendController} from "../../backend/BackendController";
import {getBackend} from "../../backend/global-scope/util/getters";

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
    author: User
    message: JSX.Element
    timeCreated: Date
    backend: BackendController
    linkTo: string
}

function PostPlaceholders() {
    const elems: JSX.Element[] = []

    for (let i = 0; i < 10; i++) {
        elems[i] = (
            <div key={i} className='flex items-center bg-gray-200 dark:bg-gray-500 m-2 shadow-2xl rounded-xl'>
                <div className='w-12 h-12 m-2 bg-gray-200 dark:bg-gray-400 rounded'/>

                <div className='w-full h-full'>
                    <div className='w-auto h-4 m-2 bg-gray-200 dark:bg-gray-400 rounded'/>
                    <div className='w-auto h-4 m-2 bg-gray-200 dark:bg-gray-400 rounded'/>
                </div>
            </div>
        )
    }
    return elems
}

function Activity(props: ActivityProps) {
    const activityElementStyles = `
    m-4 text-md text-gray-900 dark:text-gray-200
    `

    const avatar = useAPI(() => props.backend.http.loadAvatar(props.author).then(toURL))


    const formatDate = (d: Date): string => {
        return `on ${d.getDate()}/${d.getMonth() + 1}/${d.getFullYear()} at
    ${d.toLocaleTimeString(undefined, {
            hour: 'numeric',
            minute: 'numeric'
        })
        }
    `
    }

    return (
        <Link to={props.linkTo}>
            <div
                className='transition duration-300 ease-in-out transform hover:-translate-y-1 hover:bg-gray-100 dark:hover:bg-gray-400 flex items-center bg-gray-200 dark:bg-gray-500 m-2 shadow-2xl rounded-xl'>
                {!avatar ?
                    // avatar hasn't loaded yet
                    <div className='w-12 h-12 m-2 bg-gray-200 dark:bg-gray-400 rounded'/> :
                    // avatar has loaded, display it
                    <img className='w-12 h-12 m-2 ml-5' src={avatar} alt="User avatar"/>
                }
                <div className={activityElementStyles}>
                    {props.message}
                    <br/>
                    <p className='text-sm text-gray-500 dark:text-gray-300'>{formatDate(props.timeCreated)}</p>
                </div>
            </div>
        </Link>

    )
}

function LatestShows() {
    const backend = getBackend()
    const shows = useAPI(() => backend.http.loadShows(4))

    const ShowElement = (showProps: { model: Show }) => {
        const img = useAPI(() => backend.http.loadShowImage(showProps.model).then(toURL))

        return (
            <div
                className='w-auto h-auto bg-gray-200 dark:bg-gray-500 m-2 p-4 shadow-xl rounded-xl flex flex-col place-items-center
                transition duration-300 ease-in-out transform  hover:scale-105'>
                <img className='h-2/3 w-full pb-4' src={img}
                     alt={`Advertisement of ${showProps.model.title}`}/>
                <Separator className='pt-4 w-2/3'/>
                <h1 className='text-bold text-lg text-gray-900 dark:text-gray-200'>{showProps.model.title}</h1>
            </div>
        )
    };

    return (
        <>{
            shows ? shows.map(show => <ShowElement key={show.id} model={show}/>)
                : PostPlaceholders()
        }</>
    )
}

function RecentActivity() {
    const backend = getBackend()
    const posts = useAPI(() => getPost(backend))

    if (!posts) {
        return <></>
    }

    const earliestFirst = [...posts]
        .sort((a, b) =>
            a.createdAt - b.createdAt
        )

    return (
        <>{earliestFirst.map(post =>
            <Activity
                backend={backend}
                author={post.author}
                linkTo={`/~20006203/post/${post.id}`}
                message={<><b>{post.author.name}</b> created 1 new post <b>{post.title}</b></>}
                timeCreated={toDate(post.createdAt)}
            />)
        }</>
    )
}

export default function Home() {
    logger.debug('Rendering home page')

    return (
        <div className='md:flex flex-col md:flex-row w-auto max-h-screen'>
            <div
                className='w-auto md:w-2/5 h-full overflow-scroll md:overflow-visible bg-gray-300 dark:bg-gray-500 m-2 p-2 shadow-2xl rounded'>
                {/* Recent activity pane  */}
                <h1 className='text-xl font-semibold p-2 text-gray-900 dark:text-gray-200'>Recent Activity</h1>
                <Separator/>

                <ul className='grid grid-cols-1 grid-flow-row auto-rows-max items-baseline'>
                    <RecentActivity/>
                </ul>
            </div>

            <div
                className='w-auto md:w-2/3 h-full overflow-scroll md:overflow-visible bg-gray-300 dark:bg-gray-500 m-2 p-2 shadow-2xl rounded'>
                {/* Latest shows pane  */}
                <h1 className='text-xl font-semibold p-2 text-gray-900 dark:text-gray-200'>Latest shows</h1>
                <Separator/>

                <ul className='grid grid-cols-1 grid-flow-row auto-rows-max lg:grid-cols-2 items-baseline'>
                    <LatestShows/>
                </ul>
            </div>
        </div>
    )
}
