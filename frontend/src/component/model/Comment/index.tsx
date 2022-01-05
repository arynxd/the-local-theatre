import { Comment as CommentModel } from '../../../model/Comment'
import { StylableProps } from '../../props/StylableProps'
import { Hamburger } from '../../Icons'
import { useCallback, useState, ChangeEvent } from 'react'
import { getAuth, getBackend } from '../../../backend/global-scope/util/getters'
import { toLevel } from '../../../model/Permission'
import { User } from '../../../model/User'
import { useSubscription } from '../../../backend/hook/useSubscription'
import InlineButton from '../../InlineButton'
import { assert } from '../../../util/assert'
import { EntityIdentifier } from '../../../model/EntityIdentifier'
import { CacheUpdateFunction, ReactiveCache } from '../../../util/cache'
import SubmitButton from '../../SubmitButton'
import { Error } from '../../Factory'
import Modal from '../../Modal'

export const MAX_COMMENT_LENGTH = 3000

interface CommentProps {
	model: CommentModel
	onDeletion?: (comment: CommentModel) => void
	onChange?: (newComment: CommentModel) => void
}

interface ContextMenuProps {
	model: CommentModel
	state: CommentState
	setState: (newState: CommentState) => void
}

function ContextMenu(props: ContextMenuProps) {
	const user$$ = getAuth().observeUser$$

	const [selfUser, setSelfUser] = useState<User>()
	useSubscription(
		user$$,
		useCallback((newUser) => setSelfUser(newUser), [])
	)

	if (!selfUser) {
		return <> </>
	}

	const permLevel = toLevel(selfUser.permissions)

	const isOwnPost = props.model.author.id === selfUser.id
	const canDelete = permLevel === 'moderator' || isOwnPost
	const canEdit = isOwnPost

	const showMenu = canEdit || canDelete

	if (!showMenu) {
		return <> </>
	}

	const contextHandler = () => {
		if (props.state === 'context') {
			props.setState('view')
		} else {
			props.setState('context')
		}
	}

	const contextStyles = `
        absolute top-0 right-0 m-2 bg-blue-800 p-1 
        rounded shadow-xl w-8 h-8 flex flex-col items-center align-center
        ${getAuth().isAuthenticated() ? '' : 'hidden'}
    `

	if (!showMenu) {
		return <> </>
	}

	const menu = () => (
		<ul className="z-20 absolute top-2 right-14 bg-white dark:bg-gray-700 p-2 shadow-xl rounded-xl flex flex-col items-center">
			{canEdit ? (
				<button onClick={() => props.setState('edit')}>
					<li className="dark:text-gray-200 font-semibold">Edit</li>
				</button>
			) : (
				<> </>
			)}

			{canDelete ? (
				<button onClick={() => props.setState('delete')}>
					<li className="text-red-600 font-semibold">Delete</li>
				</button>
			) : (
				<> </>
			)}
		</ul>
	)
	return (
		<>
			<div onClick={contextHandler} className={contextStyles}>
				<Hamburger className="h-6 w-6 fill-white" />
			</div>

			{props.state === 'context' ? (
				<Modal
					provideMenu={menu}
					onClickAway={contextHandler}
					shouldShow={() => props.state === 'context'}
					className='bg-opacity-0'
				/>
			) : (
				<> </>
			)}
		</>
	)
}

type EditCommentState = 'view' | 'submit' | 'error'

function EditComment(props: CommentProps) {
	const [text, setText] = useState(props.model.content)
	const [state, setState] = useState<EditCommentState>('view')

	const makePromise = useCallback(
		() => getBackend().http.updateComment(props.model.id, text),
		[text]
	)

	const changeHandler = useCallback(
		(ev: ChangeEvent<HTMLTextAreaElement>) => {
			setText(ev.target.value)
		},
		[]
	)

	if (state === 'error') {
		return <Error>An error has occurred</Error>
	}

	return (
		<div className="w-auto m-2 flex flex-col items-center">
			<textarea
				minLength={1}
				maxLength={MAX_COMMENT_LENGTH}
				onChange={changeHandler}
				className="w-full min-h-max rounded-xl shadow-xl p-2 dark:bg-gray-500 dark:text-gray-100"
				defaultValue={props.model.content}
			/>

			<SubmitButton
				onSubmit={makePromise}
				onSuccess={(c) => props.onChange?.(c)}
				onError={() => setState('error')}
				shouldDisplayLoading={() => state === 'submit'}
				onClick={() => setState('submit')}
				className="w-full"
			/>
		</div>
	)
}
type CommentState = 'view' | 'edit' | 'delete' | 'context'

export default function Comment(props: CommentProps & StylableProps) {
	const { model, onDeletion } = props
	const { author } = model

	const [state, setState] = useState<CommentState>('view')

	if (state === 'delete') {
		getBackend()
			.http.deleteComment(model.id)
			.then(() => {
				onDeletion?.(model)
			})
		return <> </>
	}

	return (
		<div className={props.className}>
			<div className="bg-gray-100 dark:bg-gray-600 shadow-xl my-2 relative rounded">
				<h3 className="text-xl p-2 w-max dark:text-gray-200">
					{author.firstName} {author.lastName}
				</h3>

				{state === 'edit' ? (
					<EditComment
						model={model}
						onChange={(c) => {
							setState('view')
							props.onChange?.(c)
						}}
					/>
				) : (
					<p className="text-md p-2 dark:text-gray-300 break-words">
						{model.content}
					</p>
				)}

				<ContextMenu
					model={props.model}
					state={state}
					setState={setState}
				/>
			</div>
		</div>
	)
}
