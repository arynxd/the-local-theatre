import {toDate} from "../../../util/time";
import {Post as PostModel} from "../../../model/Post";
import {ChangeEvent, useCallback, useEffect, useState} from "react";
import {useAPI} from "../../../backend/hook/useAPI";
import {getAuth, getBackend} from "../../../backend/global-scope/util/getters";
import CommentElement, { MAX_COMMENT_LENGTH } from "../Comment";
import Separator from "../../Separator";
import {assert} from "../../../util/assert";
import {createPlaceholders} from "../../../util/factory";
import InlineButton from "../../InlineButton";
import { WarningIcon } from "../../Factory";
import { EntityIdentifier } from "../../../model/EntityIdentifier";
import { Comment } from "../../../model/Comment";
import {Hamburger} from '../../Icons';
import { useSubscription } from "../../../backend/hook/useSubscription";
import { User } from "../../../model/User";
import { toLevel } from "../../../model/Permission";


interface ContextMenuProps {
    model: PostModel
    setState: (newState: PostState) => void
    state: PostState
}

interface CommentCacheProps {
    cache: Map<EntityIdentifier, Comment>
    setCache: (newCache: Map<EntityIdentifier, Comment>) => void
    loaded: boolean
}

interface PostProps {
    post: PostModel
}

interface AddCommentProps {
    done: () => void
}

function CommentView(props: PostProps & CommentCacheProps) {
    const LoadingComments = () =>
        createPlaceholders((i) =>
            <div key={i} className='bg-gray-100 dark:bg-gray-600 shadow-xl my-2 relative rounded p-2'>
                <div className='bg-gray-300 w-2/5 h-4 animate-pulse rounded-xl m-2 mb-3'/>

                <div className={'bg-gray-300 w-auto  h-3 animate-pulse rounded-xl m-2'}/>
                <div className={'bg-gray-300 w-auto  h-3 animate-pulse rounded-xl m-2'}/>
            </div>, 3
        )
    
    
    const deleteHandler = (comment: Comment) => {
        const newCache = props.cache
        newCache.delete(comment.id)
        props.setCache(new Map(newCache))
    }
    if (!props.loaded) {
        return (
            <>{
                LoadingComments()
            }</>
        )
    }

    let sorted = Array.from(props.cache.values())
        .sort((a, b) => b.createdAt - a.createdAt)

    if (!sorted.length) {
        return (
            <div className='bg-gray-100 dark:bg-gray-600 dark:text-gray-100 p-2 my-2 w-auto rounded shadow-xl flex flex-col items-center'>
                <div className='flex flex-row items-center justify-items-center'>
                    <WarningIcon className='w-6 h-6 mr-2'/>
                    <p>No comments found</p>
                </div>
            </div>
        )
    }

    return (
        <>{
            sorted.map(c => <CommentElement 
                    key={c.id} 
                    model={c} 
                    onDeletion={deleteHandler} 
                    cache={props.cache} 
                    setCache={props.setCache}
                />)
        }</>
    )
}

function AddCommentView(props: PostProps & AddCommentProps & CommentCacheProps) {
    const [text, setText] = useState("")

    const submitHandler = useCallback(() => {
        assert(() => text.length <= MAX_COMMENT_LENGTH,
            () => new TypeError("Text exceeded the maximum of " + MAX_COMMENT_LENGTH))

        assert(() => text.length > 0,
            () => new TypeError("Text was empty"))
            
        getBackend().http.addComment(props.post.id, text)
            .then((c) => {
                const newCache = props.cache
                newCache.set(c.id, c)
                props.setCache(new Map(newCache))
                props.done()
            })
    }, [props, text])

    const changeHandler = useCallback((ev: ChangeEvent<HTMLTextAreaElement>) => {
        setText(ev.target.value)
    }, [])

    return (
        <div className='bg-gray-100 dark:bg-gray-600 mt-2 p-2 shadow-xl rounded w-full'>
            <h2 className='select-none dark:text-gray-100'>Add a comment</h2>
            <Separator className='mx-0'/>

            <textarea minLength={1} maxLength={MAX_COMMENT_LENGTH} onChange={changeHandler}
                      className='w-full h-44 rounded-xl shadow-xl p-2 mt-2 dark:bg-gray-600 dark:text-gray-100'/>

            <InlineButton onClick={submitHandler} className='mt-2 w-full'>Submit</InlineButton>
        </div>
    )
}

function ContextMenu(props: ContextMenuProps) {
    const user$$ = getAuth().observeUser$$

    const [selfUser, setSelfUser] = useState<User>()
    useSubscription(user$$, useCallback(newUser => setSelfUser(newUser), []))

    if (!selfUser) {
        return (
            <> </>
        )
    }

    const permLevel = toLevel(selfUser.permissions)

    const isOwnPost = props.model.author.id === selfUser.id
    const canDelete = permLevel === 'moderator' || isOwnPost
    const canEdit = isOwnPost

    const showMenu = canEdit || canDelete

    if (!showMenu) {
        return (
            <> </>
        )
    }

    const contextStyles = `
        absolute top-0 right-0 m-2 bg-blue-800 p-1 
        rounded shadow-xl w-8 h-8 flex flex-col items-center align-center
        ${getAuth().isAuthenticated() ? '' : 'hidden'}
    `

    const contextHandler = () => {
        if (props.state === 'context') {
            props.setState("view")
        }
        else {
            props.setState("context")
        }
    }

    if (!showMenu) {
        return (<> </> )
    }

    const menu = <ul className='absolute top-2 right-14 bg-white dark:bg-gray-700 p-2 shadow-xl rounded-xl flex flex-col items-center'>
                    {canEdit
                        ? <button onClick={() => props.setState("edit")}>
                            <li className='dark:text-gray-200 font-semibold'>Edit</li>
                        </button>
                        : <> </>
                    }

                    {canDelete
                        ? <button onClick={() => props.setState("delete")}>
                            <li className='text-red-600 font-semibold'>Delete</li>
                        </button>
                        : <> </>
                    }
                </ul>
    return (
        <>
            <div onClick={contextHandler}
                className={contextStyles}>
                <Hamburger className='h-6 w-6 fill-white'/>
            </div>
            
            {props.state === 'context'
                ? menu 
                : <> </> 
            }
        </>
    )
}


type PostState = "view" | "view_comments" | "add_comment" | "context" | "edit" | "delete"

export default function Post(props: PostProps) {
    const post = props.post
    const [cache, setCache] = useState(new Map<EntityIdentifier, Comment>())
    const [state, setState] = useState<PostState>("view")
    const apiRes = useAPI(() => getBackend().http.loadCommentsForPost(props.post.id))

    useEffect(() => {
        if (apiRes) {
            const map = new Map<EntityIdentifier, Comment>()
            for (const comment of apiRes[0]) {
                map.set(comment.id, comment)
            }
            setCache(map)
        }
    }, [apiRes])
    
    const formatDate = (unix: number): string => {
        const d = toDate(unix)
        return `${d.getDate()}/${d.getMonth() + 1}/${d.getFullYear()}`
    }

    const SeeCommentsButton = () => {
        return (
            <InlineButton
                className='w-max text-sm'
                    onClick={() => {
                        if (state === 'view_comments') {
                            setState('view')
                        }
                        else {
                            setState("view_comments")
                        }
                    }}
                >
                See comments
            </InlineButton>
        )
    }

    const AddCommentButton = () => {
        if (!getAuth().isAuthenticated()) {
            return ( <> </> )
        }

        return (
            <InlineButton
                className='w-max text-sm'
                onClick={() => {
                    if (state === 'add_comment') {
                        setState('view')
                    }
                    else {
                        setState("add_comment")
                    }
                }}
                >
                Add comment
            </InlineButton>
        )
    }

    return (
        <div className='m-5 p-4 bg-gray-200 dark:bg-gray-600 rounded shadow-xl w-full relative'>
            <h1 className='text-3xl font-bold pb-2 dark:text-gray-100'>{post.title}</h1>
            <Separator className='mx-0'/>


            <h3 className='text-gray-600 dark:text-gray-300 text-sm pb-6 mt-2'>{formatDate(post.createdAt)}</h3>
            <p className='text-md dark:text-gray-200 text-black font-medium pb-6 text-justify'>{post.content}</p>

            <div className='flex flex-col gap-4 md:flex-row w-full'>
                    <SeeCommentsButton />
                    <AddCommentButton />
                </div>

                {state === 'view_comments'
                    ? <CommentView post={post} cache={cache} setCache={setCache} loaded={!!apiRes}/>
                    : <> </>
                }

                {state === 'add_comment'
                    ? <AddCommentView post={post} cache={cache} setCache={setCache} loaded={!!apiRes} done={() => {
                        setState('view_comments')
                    }}/>
                    : <> </>
                }

                <ContextMenu 
                    model={post} 
                    state={state}
                    setState={setState}
                />
        </div>
    )
}