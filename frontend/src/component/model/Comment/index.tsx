import {Comment as CommentModel} from "../../../model/Comment";
import {StylableProps} from "../../props/StylableProps";
import menuIco from '../../../assets/dots-menu.png'
import {useCallback, useState} from "react";
import {getAuth, getBackend} from "../../../backend/global-scope/util/getters";
import {toLevel} from "../../../model/Permission";
import {User} from "../../../model/User";
import {useSubscription} from "../../../backend/hook/useSubscription";

interface CommentProps {
    model: CommentModel,
    onDeletion?: (commenet: CommentModel) => void
}

interface ContextMenuProps {
    deleteComment: () => void
    editComment: () => void
    model: CommentModel
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

    return (
        <ul className='absolute top-2 right-10 bg-white p-2 shadow-xl rounded-xl'>
            {canEdit
                ? <button onClick={props.editComment}>
                    <li className=''>Edit Comment</li>
                </button>
                : <> </>
            }

            {canDelete
                ? <button onClick={props.deleteComment}>
                    <li className='text-red-600 font-semibold'>Delete Comment</li>
                </button>
                : <> </>
            }
        </ul>
    )
}

export default function Comment(props: CommentProps & StylableProps) {
    const {model, onDeletion} = props
    const {author} = model

    const [isContextOpen, setContextOpen] = useState(false)

    const deleteHandler = useCallback(() => {
        getBackend().http.deleteComment(model.id)
            .then(() => {
                onDeletion?.(model)
            })
    }, [model, onDeletion])

    const contextStyles = `
        invisible group-hover:visible absolute top-0 right-0 m-2 bg-white p-1 rounded ring-1
        ${getAuth().isAuthenticated() ? '' : 'hidden'}
    `

    return (
        <div className={props.className}>
            <div className='group bg-gray-100 dark:bg-gray-600 shadow-xl my-2 relative rounded'>
                <h3 className='text-xl p-2 w-max dark:text-gray-200'>{author.firstName} {author.lastName}</h3>
                <p className='text-md p-2 dark:text-gray-300 break-words'>{model.content}</p>
                <div onClick={() => setContextOpen(!isContextOpen)}
                     className={contextStyles}>
                    <img className='h-4 w-4' src={menuIco} alt='Click to show comment menu'/>
                </div>
                {isContextOpen
                    ? <ContextMenu model={props.model} deleteComment={deleteHandler} editComment={() => {
                        throw TypeError("Unimplemented")
                    }}/>
                    : <> </>
                }
            </div>
        </div>
    )
}