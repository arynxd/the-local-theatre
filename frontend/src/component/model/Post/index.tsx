import {toDate} from "../../../util/time";
import {Post as PostModel} from "../../../model/Post";
import commentIco from '../../../assets/comment.png'
import {ChangeEvent, useCallback, useEffect, useState} from "react";
import {useAPI} from "../../../backend/hook/useAPI";
import {getAuth, getBackend} from "../../../backend/global-scope/util/getters";
import Comment from "../Comment";
import Separator from "../../Separator";
import {assert} from "../../../util/assert";
import {createPlaceholders} from "../../../util/factory";
import { useStatefulCache } from "../../../util/cache";
import { EntityIdentifier } from "../../../model/EntityIdentifier";
import { Comment as CommentModel } from "../../../model/Comment";
import InlineButton from "../../InlineButton";

const MAX_COMMENT_LENGTH = 3000

interface PostProps {
    post: PostModel
}

interface AddCommentProps {
    done: () => void
}

function CommentView(props: PostProps) {
    const apiRes = useAPI(() => getBackend().http.loadCommentsForPost(props.post.id))
    const [commentCache, updateCommentCache] = useStatefulCache<EntityIdentifier, CommentModel>()

    const LoadingComments = () =>
        createPlaceholders((i) =>
            <div key={i} className='bg-gray-100 dark:bg-gray-600 shadow-xl my-2 relative rounded p-2'>
                <div className='bg-gray-300 w-2/5 h-4 animate-pulse rounded-xl m-2 mb-3'/>

                <div className={'bg-gray-300 w-auto  h-3 animate-pulse rounded-xl m-2'}/>
                <div className={'bg-gray-300 w-auto  h-3 animate-pulse rounded-xl m-2'}/>
            </div>
        )
    
    useEffect(() => {
        if (apiRes) {
            updateCommentCache(cache => {
                cache.setAll(apiRes[0].map(c => [c.id, c]))
            })
        }
    }, [apiRes])
    
    if (!apiRes) {
        return (
            <>{
                LoadingComments()
            }</>
        )
    }

    if (!commentCache.size) {
        //TODO: proper GUI element
        return (
            <div className=''>
                <p>No comments found</p>
            </div>
        )
    }


    //FIXME: ghost cache elements when comments are deleted
    const deleteHandler = (c: CommentModel) => 
        updateCommentCache((cache) => cache.delete(c.id))

    return (
        <>{
            commentCache.valueArray().map(c => <Comment key={c.id} model={c} onDeletion={deleteHandler}/>)
        }</>
    )
}

//TODO: pass the cache into this view and update it
function AddCommentView(props: PostProps & AddCommentProps) {
    const [text, setText] = useState("")

    const submitHandler = useCallback(() => {
        assert(() => text.length <= MAX_COMMENT_LENGTH,
            () => new TypeError("Text exceeded the maximum of " + MAX_COMMENT_LENGTH))

        assert(() => text.length > 0,
            () => new TypeError("Text was empty"))
            
        getBackend().http.addComment(props.post.id, text)
            .then(() => {
                setText('')
                props.done()
            })
    }, [props, text])

    const changeHandler = useCallback((ev: ChangeEvent<HTMLTextAreaElement>) => {
        setText(ev.target.value)
    }, [])

    return (
        <div className='bg-gray-100 dark:bg-gray-600 mt-2 p-2 shadow rounded w-full'>
            <h2 className='select-none dark:text-gray-100'>Add a comment</h2>
            <Separator className='mx-0'/>

            <textarea minLength={1} maxLength={MAX_COMMENT_LENGTH} onChange={changeHandler}
                      className='w-full h-44 rounded-xl shadow p-2 mt-2 dark:bg-gray-600 dark:text-gray-100'/>

            <InlineButton onClick={submitHandler} className='mt-2 w-full'>Submit</InlineButton>
        </div>
    )
}

export default function Post(props: PostProps) {
    const post = props.post
    const [isCommentsOpen, setCommentsOpen] = useState(false)
    const [isAddingComment, setAddingComment] = useState(false)

    const formatDate = (unix: number): string => {
        const d = toDate(unix)
        return `${d.getDate()}/${d.getMonth() + 1}/${d.getFullYear()}`
    }

    return (
        <div className='m-5 p-4 bg-gray-200 dark:bg-gray-600 rounded shadow-xl w-full'>
            <h1 className='text-3xl font-bold pb-2 dark:text-gray-100'>{post.title}</h1>
            <Separator className='mx-0'/>
            <h3 className='text-gray-600 dark:text-gray-300 text-sm pb-6 mt-2'>{formatDate(post.createdAt)}</h3>
            <p className='text-md dark:text-gray-200 text-black font-medium pb-6 text-justify'>{post.content}</p>

            <div className='w-1/2'>
                <div className='flex flex-col md:flex-row'>
                    <div
                        className='block w-full h-10 bg-gray-100 dark:bg-gray-500 shadow-xl rounded mb-1 md:mr-1 md:my-0'>
                        <div onClick={() => {
                            setCommentsOpen(!isCommentsOpen)
                            setAddingComment(false)
                        }}
                             className='flex flex-row items-center justify-center h-full'>
                            <img className='w-5 h-5 m-2' src={commentIco} alt='Click to see comments'/>
                            <p className='m-2 block select-none text-sm dark:text-gray-100'>See comments</p>
                        </div>
                    </div>

                    {getAuth().isAuthenticated()
                        ? <div
                            className='block w-full h-10 bg-gray-100 dark:bg-gray-500 shadow-xl rounded mt-1 md:ml-1 md:my-0'>
                            <div onClick={() => {
                                setAddingComment(!isAddingComment)
                                setCommentsOpen(false)
                            }}
                                 className='flex flex-row items-center justify-center h-full'>
                                <img className='w-5 h-5 m-2' src={commentIco} alt='Click to see comments'/>
                                <p className='m-2 block select-none text-sm dark:text-gray-100'>Add comment</p>
                            </div>
                        </div>
                        : <> </>}
                </div>

                {isCommentsOpen
                    ? <CommentView post={post}/>
                    : <> </>
                }

                {isAddingComment
                    ? <AddCommentView post={post} done={() => {
                        setAddingComment(false)
                        setCommentsOpen(true)
                    }}/>
                    : <> </>
                }
            </div>
        </div>
    )
}