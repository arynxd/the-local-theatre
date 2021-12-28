import { useAPI } from '../../backend/hook/useAPI'
import { getAuth, getBackend } from '../../backend/global-scope/util/getters'
import PostElement from '../../component/model/Post'
import { createPlaceholders } from '../../util/factory'
import { useEffect, useState } from 'react'
import Separator from '../../component/Separator'
import InlineButton from '../../component/InlineButton'
import { Error, Warning } from '../../component/Factory'
import { useSelfUser } from '../../backend/hook/useSelfUser'
import { hasPermission } from '../../model/Permission'
import { Post } from '../../model/Post'
import { EntityIdentifier } from '../../model/EntityIdentifier'
import { useReactiveCache } from '../../util/cache'

interface CreatePostProps {
	onComplete: (post: Post) => void
}

function CreatePostView(props: CreatePostProps) {
	const [title, setTitle] = useState<string>('')
	const [content, setContent] = useState<string>('')

	const handleSubmitClick = () => {
		let err = false
		if (!title) {
			setTitle('Title is required')
			err = true
		}

		if (!content) {
			setContent('Content is required')
			err = true
		}

		if (err) {
			return
		}

		getBackend()
			.http.addPost(title, content)
			.then((p) => {
				props.onComplete(p)
			})
	}

	if (!getAuth().isAuthenticated()) {
		return <></>
	}

	return (
		<div className="w-auto bg-gray-200 dark:bg-gray-600 rounded shadow-xl m-4 p-2">
			<div className="w-max">
				<h2 className="text-lg dark:text-gray-100">
					Create a new post
				</h2>
				<Separator className="w-full mt-1 pb-4" />
			</div>

			<textarea
				placeholder="Post title"
				maxLength={50}
				minLength={1}
				onChange={(ev) => setTitle(ev.target.value)}
				className="w-full p-2 bg-gray-100 dark:bg-gray-600 dark:text-gray-100 h-10 mb-4 rounded shadow-xl"
			/>
			<textarea
				placeholder="Post content"
				maxLength={5000}
				minLength={1}
				onChange={(ev) => setContent(ev.target.value)}
				className="w-full p-2 bg-gray-100 dark:bg-gray-600 dark:text-gray-100 h-24 rounded shadow-xl"
			/>

			<InlineButton className="mt-2" onClick={handleSubmitClick}>
				Submit
			</InlineButton>
		</div>
	)
}

type BlogState = 'view_posts' | 'create_post' | 'error'

export default function Blog() {
	const [state, setState] = useState<BlogState>('view_posts')
	const [cache, updateCache] = useReactiveCache<EntityIdentifier, Post>()
	const selfUser = useSelfUser()

	const PostPlaceholders = () =>
		createPlaceholders((i) => (
			<div
				key={i}
				className="m-5 p-4 bg-gray-200 dark:bg-gray-600 rounded shadow-xl w-full"
			>
				<div className="w-2/5 h-8 m-2 bg-gray-300 animate-pulse rounded-xl" />

				<div className="w-full h-4 m-2 mt-4 bg-gray-300 animate-pulse rounded-xl" />
				<div className="w-full h-4 m-2 mt-4 bg-gray-300 animate-pulse rounded-xl" />
				<div className="w-full h-4 m-2 mt-4 bg-gray-300 animate-pulse rounded-xl" />
				<div className="w-full h-4 m-2 mt-4 bg-gray-300 animate-pulse rounded-xl" />
			</div>
		))

	const apiRes = useAPI(
		() => getBackend().http.loadAllPosts(),
		() => setState('error')
	)

	useEffect(() => {
		if (apiRes) {
			apiRes.forEach((post) => cache.set(post.id, post))
			updateCache()
		}
	}, [apiRes])

	if (state === 'error') {
		return <>{<Error>An error occurred</Error>}</>
	}

	if (!apiRes || (!selfUser && getAuth().isAuthenticated())) {
		return (
			<div className="flex flex-col items-center justify-center mx-4 md:mx-24 lg:mx-44">
				{PostPlaceholders()}
			</div>
		)
	}

	const handlePostClick = () => {
		setState('create_post')
	}

	const createPostButton =
		selfUser && hasPermission(selfUser.permissions, 'moderator') ? (
			<InlineButton
				onClick={handlePostClick}
				className="fixed bottom-0 right-0 m-2 z-20"
			>
				Create post
			</InlineButton>
		) : (
			<></>
		)

	const deleteHandler = (post: Post) => {
		cache.delete(post.id)
		updateCache()
		setState('view_posts')
	}

	const sorted = cache.values().sort((a, b) => b.createdAt - a.createdAt)

	if (state === 'view_posts') {
		return (
			<>
				{createPostButton}
				{sorted.length ? (
					<div className="flex flex-col items-center justify-center mx-4 md:mx-24 lg:mx-44">
						{sorted.map((post) => (
							<PostElement
								key={post.id}
								post={post}
								onDelete={deleteHandler}
								cache={cache}
								updateCache={updateCache}
							/>
						))}
					</div>
				) : (
					<div className="w-auto m-4 p-2 bg-gray-200 rounded shadow-xl flex flex-col items-center">
						<Warning>No posts found</Warning>
					</div>
				)}
			</>
		)
	} else if (state === 'create_post') {
		return (
			<CreatePostView
				onComplete={(p) => {
					cache.set(p.id, p)
					updateCache()
					setState('view_posts')
				}}
			/>
		)
	} else {
		throw new TypeError('Unhandled state ' + state)
	}
}
