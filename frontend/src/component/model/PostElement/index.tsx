import {Post as PostModel} from "../../../model/Post";
import {useEffect, useState} from "react";
import {BackendProps} from "../../props/BackendProps";
import {StylableProps} from "../../props/StylableProps";
import like from '../../../assets/thumbs-up.png'
import comment from '../../../assets/comment.png'
import share from '../../../assets/share.png'

interface PostProps {
    postModel: PostModel
}

export default function PostElement(props: PostProps & BackendProps & StylableProps) {
    const model = props.postModel
    const [img, setImg] = useState<string>()

    useEffect(() => {
        props.backend.http.loadAvatar(model.author)
            .then(blob => setImg(URL.createObjectURL(blob)))
    }, [model.author, props.backend.http])

    const center = 'w-16'
    const smallTextStyles = `font-semibold text-xl text-gray-600 dark:text-gray-300 w-16 p2 flex-col items-center justify-items-center `
    const imgStyles = 'w-6 h-6 inline-block'

    const [s1, setS1] = useState(0)
    const [s2, setS2] = useState(0)
    const [s3, setS3] = useState(0)

    return (
        <>
            <div className={props.className}>
                <div className='rounded p-3 w-5/6 max-h-full h-full bg-gray-200 dark:bg-gray-500 shadow-xl'>
                    <div className='items-center justify-start flex'>
                        <img className='w-12 h-12 static inline-block' src={img} alt='User avatar'/>
                        <h2 className='inline-block ml-4 text-2xl font-semibold'>{model.author.name}</h2>
                    </div>

                    <div className='p-3'>
                        <p className='font-mono text-justify relative'>{(model.content + " ").repeat(100) + " END"}</p>
                        <div className='bg-gray-200 dark:bg-gray-500 rounded p-1 mt-5 w-60 shadow-md grid grid-cols-3 grid-rows-1'>
                            <button onClick={(_) => setS1(s1 + 1)} className={center}><img className={imgStyles} src={like} alt={'Like count'}/><p className={smallTextStyles}>{s1}</p></button>
                            <button onClick={(_) => setS2(s2 + 1)} className={center}><img className={imgStyles} src={comment} alt={'Comment count'}/><p className={smallTextStyles}>{s2}</p></button>
                            <button onClick={(_) => setS3(s3 + 1)} className={center}><img className={imgStyles} src={share} alt={'Share count'}/><p className={smallTextStyles}>{s3}</p></button>
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}