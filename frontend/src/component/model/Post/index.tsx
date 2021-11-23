import {toDate} from "../../../util/time";
import {Post as PostModel} from "../../../model/Post";
import commentIco from '../../../assets/comment.png'
import {ChangeEvent, useCallback, useState} from "react";
import {useAPI} from "../../../backend/hook/useAPI";
import {getBackend} from "../../../backend/global-scope/util/getters";
import {Comment} from "../Comment";
import Separator from "../../Separator";

const MAX_COMMENT_LENGTH = 3000

interface PostProps {
    post: PostModel
}

interface AddCommentProps {
    done: () => void
}

function CommentView(props: PostProps) {
    const comments = useAPI(() => getBackend().http.fetchComments(props.post.id))
    if (!comments) {
        return (
            <p>Loading</p>
        )
    }

    return (
        <>{
            comments.map(c => <Comment model={c}/>)
        }</>
    )
}

function AddCommentView(props: PostProps & AddCommentProps) {
    const [text, setText] = useState("")

    //TODO Length limits
    const submitHandler = useCallback(() => {
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
        <div className='bg-gray-100 mt-2 p-2 shadow rounded w-full'>
            <h2 className='select-none'>Add a comment</h2>
            <Separator className='mx-0'/>

            <textarea minLength={1} maxLength={MAX_COMMENT_LENGTH} onChange={changeHandler}
                      className='w-full h-44 rounded-xl shadow p-2'/>

            <button onClick={submitHandler}
                    className='p-1 mt-4 w-6/12 text-gray-100 font-semibold text-md bg-blue-900 rounded shadow-xl'>
                Submit
            </button>
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
            <h3 className='text-gray-600 dark:text-gray-300 text-sm pb-12'>{formatDate(post.createdAt)}</h3>
            <p className='text-md dark:text-gray-200 text-black font-medium pb-6 text-justify'>{post.content}</p>

            <div className='w-1/2'>
                <div className='flex flex-col md:flex-row'>
                    <div
                        className='block w-full h-10 bg-gray-100 shadow-xl rounded mb-1 md:mr-1 md:my-0'>
                        <div onClick={() => {
                            setCommentsOpen(!isCommentsOpen)
                            setAddingComment(false)
                        }}
                             className='flex flex-row items-center justify-center h-full'>
                            <img className='w-5 h-5 m-2' src={commentIco} alt='Click to see comments'/>
                            <p className='m-2 block select-none text-sm'>See comments</p>
                        </div>
                    </div>

                    <div
                        className='block w-full h-10 bg-gray-100 shadow-xl rounded mt-1 md:ml-1 md:my-0'>
                        <div onClick={() => {
                            setAddingComment(!isAddingComment)
                            setCommentsOpen(false)
                        }}
                             className='flex flex-row items-center justify-center h-full'>
                            <img className='w-5 h-5 m-2' src={commentIco} alt='Click to see comments'/>
                            <p className='m-2 block select-none text-sm'>Add comment</p>
                        </div>
                    </div>
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