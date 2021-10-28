import {Post as PostModel} from "../../../model/Post";
import {useEffect, useState} from "react";
import {BackendProps} from "../../props/BackendProps";
import {StylableProps} from "../../props/StylableProps";
import like from '../../../assets/thumbs-up.png'
import comment from '../../../assets/comment.png'
import share from '../../../assets/share.png'
import {Comment as CommentModel} from "../../../model/Comment";
import {EntityIdentifier} from "../../../model/EntityIdentifier";
import {Comment} from "../Comment";

interface PostProps {
    postModel: PostModel
}

function CommentWrapper(props: BackendProps & { open: boolean }) {
    const [comments, setComments] = useState<CommentModel[]>([])
    const [latest, setLatest] = useState<EntityIdentifier | undefined>(undefined)

    useEffect(() => {
        props.backend.http.fetchComments(10, latest)
            .then(c => {
                setComments(c)
            })

    }, [latest])

    //TODO get 'x' button to float in top right of containing div
    if (props.open) {
        return (
            <div className='rounded p-4  w-full md:w-5/6 max-h-full h-screen bg-gray-200 dark:bg-gray-500 shadow-xl'>
                <div className=''>
                    <h1 className='text-3xl pb-2'>Comments</h1>
                    <button className='float-right'>X</button>
                </div>

                <div className='grid grid-cols-1 grid-flow-row'>
                    {comments.map(c => <Comment className='mb-4 mt-4' key={c.id} model={c}/>)}
                </div>
            </div>
        )
    }
    return ( <> </> )
}

export default function Post(props: PostProps & BackendProps & StylableProps) {
    const model = props.postModel
    const [img, setImg] = useState<string>()
    const [showComments, setShowComments] = useState(false)

    useEffect(() => {
        props.backend.http.loadAvatar(model.author)
            .then(blob => setImg(URL.createObjectURL(blob)))
    }, [model.author, props.backend.http])

    const center = 'w-16'
    const smallTextStyles = `font-semibold text-xl text-gray-600 dark:text-gray-300 w-16 p2 flex-col items-center justify-items-center `
    const imgStyles = 'w-6 h-6 inline-block'

    const postStyles = `
        rounded p-3 w-full md:w-5/6 max-h-full h-full bg-gray-200 dark:bg-gray-500 shadow-xl
        ${showComments ? 'hidden' : 'block'}
    `

    const [s1, setS1] = useState(0)
    const [s2, setS2] = useState(0)
    const [s3, setS3] = useState(0)

    return (
        <>
            <div className={props.className}>
                <div className={postStyles}>
                    <div className='items-center justify-start flex'>
                        <img className='w-12 h-12 static inline-block' src={img} alt='User avatar'/>
                        <h2 className='inline-block ml-4 text-2xl font-semibold'>{model.author.name}</h2>
                    </div>

                    <div className='p-3'>
                        <p className='font-mono text-justify relative'>{model.content}</p>
                        <div className='bg-gray-200 dark:bg-gray-500 rounded p-1 mt-5 w-60 shadow-md grid grid-cols-3 grid-rows-1'>
                            <button onClick={(_) => setS1(s1 + 1)} className={center}><img className={imgStyles} src={like} alt={'Like count'}/><p className={smallTextStyles}>{s1}</p></button>
                            <button onClick={(_) => {setS2(s2 + 1); setShowComments(!showComments)}} className={center}><img className={imgStyles} src={comment} alt={'Comment count'}/><p className={smallTextStyles}>{s2}</p></button>
                            <button onClick={(_) => setS3(s3 + 1)} className={center}><img className={imgStyles} src={share} alt={'Share count'}/><p className={smallTextStyles}>{s3}</p></button>
                        </div>
                    </div>
                </div>

                <CommentWrapper open={showComments}  backend={props.backend}/>
            </div>
        </>
    )
}