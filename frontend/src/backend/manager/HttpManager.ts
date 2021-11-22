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
        route.withQueryParam('id', id.toString())

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
        route.withQueryParam('id', user.id)

        return BackendAction.new(route)
            .flatMap(res => fromPromise(res.blob()))
    }

    listPosts(limit: number, last?: EntityIdentifier): BackendAction<Post[]> {
        const route = Routes.Post.LIST.compile()

        if (last) {
            route.withQueryParam('start', last.toString())
        }

        route.withQueryParam('limit', limit.toString(10))

        return BackendAction.new(route)
            .flatMap(toJSON)
            .map(v => toModelArray(v.posts, this.backend().entity.createPost))
    }

    loadShowImage(show: Show): BackendAction<Blob> {
        const route = Routes.Show.IMAGE.compile()
        route.withQueryParam('id', show.id)

        return BackendAction.new(route)
            .flatMap(res => fromPromise(res.blob()))
    }

    loadShows(limit: number): BackendAction<Show[]> {
        const route = Routes.Show.LIST.compile()
        route.withQueryParam('limit', limit.toString(10))


        return BackendAction.new(route)
            .flatMap(toJSON)
            .map(v => toModelArray(v.shows, this.backend().entity.createShow))
    }

    fetchComments(limit: number, latest?: EntityIdentifier): BackendAction<Comment[]> {
        const route = Routes.Comment.LIST.compile()

        if (latest) {
            route.withQueryParam('last', latest.toString())
        }

        route.withQueryParam('limit', limit.toString(10))

        return BackendAction.new(route)
            .flatMap(toJSON)
            .map(v => toModelArray(v.comments, this.backend().entity.createComment))
    }

    private readonly backend = () => getBackend()

    loadSelfUser(): BackendAction<User> {
        const route = Routes.Self.FETCH.compile()

        return BackendAction.new(route)
            .flatMap(toJSON)
            .map(this.backend().entity.createUser)
    }

    loadPing(): BackendAction<number> {
        const now = () => Math.floor(Date.now() / 1000)
        let time = now()
        return getAuth().loadSelfUser().map(() => now() - time)
    }

    loadPost(id: EntityIdentifier): BackendAction<Post> {
        const route = Routes.Post.FETCH.compile()
        route.withQueryParam('id', id)

        return BackendAction.new(route)
            .flatMap(toJSON)
            .map(v => toModel(v.post, this.backend().entity.createPost))

    }
}
