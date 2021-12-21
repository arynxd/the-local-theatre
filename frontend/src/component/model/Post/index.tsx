import {toDate} from "../../../util/time";
import {Post as PostModel} from "../../../model/Post";
import {ChangeEvent, useCallback, useEffect, useState} from "react";
import {useAPI} from "../../../backend/hook/useAPI";
import {getAuth, getBackend} from "../../../backend/global-scope/util/getters";
import CommentElement from "../Comment";
import Separator from "../../Separator";
import {assert} from "../../../util/assert";
import {createPlaceholders} from "../../../util/factory";
import InlineButton from "../../InlineButton";
import { WarningIcon } from "../../Factory";

const MAX_COMMENT_LENGTH = 3000

interface PostProps {
    post: PostModel
}

interface AddCommentProps {
    done: () => void
}

function CommentView(props: PostProps) {
    const apiRes = useAPI(() => getBackend().http.loadCommentsForPost(props.post.id))
    
    const LoadingComments = () =>
        createPlaceholders((i) =>
            <div key={i} className='bg-gray-100 dark:bg-gray-600 shadow-xl my-2 relative rounded p-2'>
                <div className='bg-gray-300 w-2/5 h-4 animate-pulse rounded-xl m-2 mb-3'/>

                <div className={'bg-gray-300 w-auto  h-3 animate-pulse rounded-xl m-2'}/>
                <div className={'bg-gray-300 w-auto  h-3 animate-pulse rounded-xl m-2'}/>
            </div>, 3
        )
    
    
    if (!apiRes) {
        return (
            <>{
                LoadingComments()
            }</>
        )
    }

    if (!apiRes[0].length) {
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
            apiRes[0].map(c => <CommentElement key={c.id} model={c}/>)
        }</>
    )
}

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
        <div className='bg-gray-100 dark:bg-gray-600 mt-2 p-2 shadow-xl rounded w-full'>
            <h2 className='select-none dark:text-gray-100'>Add a comment</h2>
            <Separator className='mx-0'/>

            <textarea minLength={1} maxLength={MAX_COMMENT_LENGTH} onChange={changeHandler}
                      className='w-full h-44 rounded-xl shadow-xl p-2 mt-2 dark:bg-gray-600 dark:text-gray-100'/>

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

    const SeeCommentsButton = () => {
        if (!getAuth().isAuthenticated()) {
            return ( <> </> )
        }

        return (
            <InlineButton
                className='w-max text-sm'
                    onClick={() => {
                        setCommentsOpen(!isCommentsOpen); 
                        setAddingComment(false)
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
                    setCommentsOpen(false); 
                    setAddingComment(!isAddingComment)
                }}
                >
                Add comment
            </InlineButton>
        )
    }

    return (
        <div className='m-5 p-4 bg-gray-200 dark:bg-gray-600 rounded shadow-xl w-full'>
            <h1 className='text-3xl font-bold pb-2 dark:text-gray-100'>{post.title}</h1>
            <Separator className='mx-0'/>
            <h3 className='text-gray-600 dark:text-gray-300 text-sm pb-6 mt-2'>{formatDate(post.createdAt)}</h3>
            <p className='text-md dark:text-gray-200 text-black font-medium pb-6 text-justify'>{post.content}</p>

            <div className='flex flex-col gap-4 md:flex-row w-full'>
                    <SeeCommentsButton />
                    <AddCommentButton />
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
    )
}