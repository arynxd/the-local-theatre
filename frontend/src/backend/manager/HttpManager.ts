import {Manager} from "./Manager";
import {User} from "../../model/User";
import Routes from "../request/route/Routes";
import {Post} from "../../model/Post";
import {BackendAction} from "../request/BackendAction";
import {EntityIdentifier} from "../../model/EntityIdentifier";
import {Comment} from "../../model/Comment";
import {Show} from "../../model/Show";
import {getAuth, getBackend} from "../global-scope/util/getters";
import {fromPromise, toJSON, toModel, toModelArray} from "../request/mappers";

/**
 * Manages all HTTP duties for the backend
 * **ALL** requests should go through this manager
 */
export class HttpManager extends Manager {
    loadUser(id: EntityIdentifier): BackendAction<User> {
        const route = Routes.User.FETCH.compile()
            .withQueryParam('id', id.toString())

        return BackendAction.new(route)
            .flatMap(toJSON)
            .map(this.backend().entity.createUser)
    }

    /**
     * Returns the blob of the image
     * @param user
     */
    loadAvatar(user: User): BackendAction<Blob> {
        const route = Routes.User.AVATAR.compile()
            .withQueryParam('id', user.id)

        return BackendAction.new(route)
            .flatMap(res => fromPromise(res.blob()))
    }

    listPosts(): BackendAction<Post[]> {
        const route = Routes.Post.LIST.compile()

        return BackendAction.new(route)
            .flatMap(toJSON)
            .map(v => toModelArray(v, this.backend().entity.createPost))
    }

    loadShowImage(show: Show): BackendAction<Blob> {
        const route = Routes.Show.IMAGE.compile()
            .withQueryParam('id', show.id)

        return BackendAction.new(route)
            .flatMap(res => fromPromise(res.blob()))
    }

    loadShows(): BackendAction<Show[]> {
        const route = Routes.Show.LIST.compile()

        return BackendAction.new(route)
            .flatMap(toJSON)
            .map(v => toModelArray(v, this.backend().entity.createShow))
    }

    fetchComments(id: EntityIdentifier): BackendAction<[Comment[], number]> {
        const route = Routes.Comment.LIST.compile()
            .withQueryParam('id', id)

        return BackendAction.new(route)
            .flatMap(toJSON)
            .map(v => {
                const count = v.count
                if (typeof count !== 'number')
                    throw new TypeError("Count was not a number")

                return [toModelArray(v.comments, this.backend().entity.createComment), count]
            })
    }

    loadSelfUser(): BackendAction<User> {
        const route = Routes.Self.FETCH.compile()

        return BackendAction.new(route)
            .flatMap(toJSON)
            .map(v => this.backend().entity.createUser(v))
    }

    loadPing(): BackendAction<number> {
        const now = () => Math.floor(Date.now() / 1000)
        let time = now()
        return fromPromise(getAuth().loadSelfUser()).map(() => now() - time)
    }

    loadPost(id: EntityIdentifier): BackendAction<Post> {
        const route = Routes.Post.FETCH.compile()
            .withQueryParam('id', id)

        return BackendAction.new(route)
            .flatMap(toJSON)
            .map(v => toModel(v, this.backend().entity.createPost))

    }

    deleteComment(id: EntityIdentifier): BackendAction<void> {
        const route = Routes.Comment.DELETE.compile()
            .withQueryParam('id', id)

        return BackendAction.new(route).toVoid()
    }

    addComment(postId: EntityIdentifier, text: string): BackendAction<void> {
        const route = Routes.Comment.ADD.compile()
            .withBody({
                id: postId,
                data: text
            })

        return BackendAction.new(route).toVoid()
    }

    loadUsers(): BackendAction<User[]> {
        const route = Routes.User.FETCH_ALL.compile()
        return BackendAction.new(route)
            .flatMap(toJSON)
            .map(v => toModelArray(v, this.backend().entity.createUser))
    }

    private readonly backend = () => getBackend()

    updateUser(newUser: User): BackendAction<void> {
        const route = Routes.User.UPDATE.compile()
            .withBody({
                data: {
                    id: newUser.id,
                    firstName: newUser.firstName,
                    lastName: newUser.lastName,
                    permissions: newUser.permissions,
                    dob: newUser.dob,
                    joinDate: newUser.dob,
                    username: newUser.username
                }
            })

        return BackendAction.new(route)
            .toVoid()
    }

    deleteUser(id: EntityIdentifier): BackendAction<void> {
        const route = Routes.User.DELETE.compile()
            .withQueryParam('id', id)

        return BackendAction.new(route).toVoid()
    }
}
