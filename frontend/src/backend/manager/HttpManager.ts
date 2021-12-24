import { Manager } from './Manager'
import { User } from '../../model/User'
import Routes from '../request/route/Routes'
import { Post } from '../../model/Post'
import { BackendAction } from '../request/BackendAction'
import { EntityIdentifier } from '../../model/EntityIdentifier'
import { Comment } from '../../model/Comment'
import { Show } from '../../model/Show'
import { getAuth, getBackend } from '../global-scope/util/getters'
import { fromPromise, toJSON, toModel, toModelArray } from '../request/mappers'
import { SelfUser } from '../../model/SelfUser'

/**
 * Manages all HTTP duties for the backend
 * **ALL** requests should go through this manager
 */
export class HttpManager extends Manager {
    updatePost(id: EntityIdentifier, title: string, content: string) {
        const route = Routes.Post.UPDATE.compile().withBody({
            id,
            title,
            content,
        })

        return BackendAction.new(route)
            .flatMap(toJSON)
            .map((v) => toModel(v, this.backend().entity.createPost))
    }
    deletePost(id: EntityIdentifier) {
        const route = Routes.Post.DELETE.compile().withQueryParam('id', id)

        return BackendAction.new(route)
            .flatMap(toJSON)
            .map((v) => toModel(v, this.backend().entity.createPost))
    }

    updateComment(
        id: EntityIdentifier,
        content: string
    ): BackendAction<Comment> {
        const route = Routes.Comment.UPDATE.compile().withBody({
            id,
            content,
        })

        return BackendAction.new(route)
            .flatMap(toJSON)
            .map((v) => toModel(v, this.backend().entity.createComment))
    }

    addPost(title: string, content: string): BackendAction<Post> {
        const route = Routes.Post.ADD.compile().withBody({
            title,
            content,
        })

        return BackendAction.new(route)
            .flatMap(toJSON)
            .map((v) => toModel(v, this.backend().entity.createPost))
    }

    loadUser(id: EntityIdentifier): BackendAction<User> {
        const route = Routes.User.FETCH.compile().withQueryParam(
            'id',
            id.toString()
        )

        return BackendAction.usingCache(
            () => this.backend().cache.user.get(id),
            () =>
                BackendAction.new(route)
                    .flatMap(toJSON)
                    .map(this.backend().entity.createUser)
        )
    }

    /**
     * Returns the blob of the image
     * @param user
     */
    loadAvatar(user: User): BackendAction<Blob> {
        const route = Routes.User.AVATAR.compile().withQueryParam('id', user.id)

        return BackendAction.new(route).flatMap((res) =>
            fromPromise(res.blob())
        )
    }

    loadAllPosts(): BackendAction<Post[]> {
        const route = Routes.Post.LIST.compile()
        // this wont use cache as we wont know what's in cache vs what we're missing
        return BackendAction.new(route)
            .flatMap(toJSON)
            .map((v) => toModelArray(v, this.backend().entity.createPost))
            .also((posts) =>
                this.backend().cache.post.setAll(posts.map((p) => [p.id, p]))
            )
        // set posts in cache so that individual lookups can use it
    }

    loadShowImage(show: Show): BackendAction<Blob> {
        const route = Routes.Show.IMAGE.compile().withQueryParam('id', show.id)

        return BackendAction.new(route).flatMap((res) =>
            fromPromise(res.blob())
        )
    }

    loadShows(): BackendAction<Show[]> {
        const route = Routes.Show.LIST.compile()

        return BackendAction.new(route)
            .flatMap(toJSON)
            .map((v) => toModelArray(v, this.backend().entity.createShow))
    }

    loadCommentsForPost(
        postId: EntityIdentifier
    ): BackendAction<[Comment[], number]> {
        const route = Routes.Comment.LIST.compile().withQueryParam('id', postId)

        const cachingFunction = () => {
            const arr = Array.from(
                this.backend().cache.comment.values()
            ).filter((x) => x.postId === postId)

            if (!arr.length) {
                return undefined
            }
            return [arr, arr.length] as [Comment[], number]
        }

        return BackendAction.usingCache(cachingFunction, () =>
            BackendAction.new(route)
                .flatMap(toJSON)
                .map((v) => {
                    const count = v.count
                    if (typeof count !== 'number')
                        throw new TypeError('Count was not a number')

                    return [
                        toModelArray(
                            v.comments,
                            this.backend().entity.createComment
                        ),
                        count,
                    ] as [Comment[], number]
                })
                .also((comments) =>
                    this.backend().cache.comment.setAll(
                        comments[0].map((c) => [c.id, c])
                    )
                )
        )
    }

    loadSelfUser(): BackendAction<SelfUser> {
        const route = Routes.Self.FETCH.compile()

        // this cannot be cached as we do not know our ID
        return BackendAction.new(route)
            .flatMap(toJSON)
            .map((v) => this.backend().entity.createSelfUser(v))
    }

    loadPing(): BackendAction<number> {
        const now = () => Math.floor(Date.now() / 1000)
        let time = now()
        return fromPromise(getAuth().loadSelfUser()).map(() => now() - time)
    }

    loadPost(id: EntityIdentifier): BackendAction<Post> {
        const route = Routes.Post.FETCH.compile().withQueryParam('id', id)

        return BackendAction.usingCache(
            () => this.backend().cache.post.get(id),
            () =>
                BackendAction.new(route)
                    .flatMap(toJSON)
                    .map((v) => toModel(v, this.backend().entity.createPost))
                    .also((v) => this.backend().cache.post.set(v.id, v))
        )
    }

    deleteComment(id: EntityIdentifier): BackendAction<void> {
        const route = Routes.Comment.DELETE.compile().withQueryParam('id', id)

        return BackendAction.new(route).toVoid()
    }

    addComment(postId: EntityIdentifier, text: string): BackendAction<Comment> {
        const route = Routes.Comment.ADD.compile().withBody({
            postId: postId,
            content: text,
        })

        return BackendAction.new(route)
            .flatMap(toJSON)
            .map((v) => toModel(v, this.backend().entity.createComment))
    }

    loadUsers(): BackendAction<User[]> {
        const route = Routes.User.FETCH_ALL.compile()
        // this wont use cache as we wont know what's in cache vs what we're missing
        return BackendAction.new(route)
            .flatMap(toJSON)
            .map((v) => toModelArray(v, this.backend().entity.createUser))
    }

    private readonly backend = () => getBackend()

    updateUser(newUser: User): BackendAction<void> {
        const route = Routes.User.UPDATE.compile().withBody({
            id: newUser.id,
            firstName: newUser.firstName,
            lastName: newUser.lastName,
            permissions: newUser.permissions,
            dob: newUser.dob,
            joinDate: newUser.dob,
            username: newUser.username,
        })

        return BackendAction.new(route).toVoid()
    }

    deleteUser(id: EntityIdentifier): BackendAction<void> {
        const route = Routes.User.DELETE.compile().withQueryParam('id', id)

        return BackendAction.new(route).toVoid()
    }
}
