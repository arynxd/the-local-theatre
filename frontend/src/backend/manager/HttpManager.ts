import {Manager} from "./Manager";
import {User} from "../../model/User";
import Routes from "../request/route/Routes";
import {Post} from "../../model/Post";
import {BackendAction} from "../request/BackendAction";
import {EntityIdentifier} from "../../model/EntityIdentifier";
import {Comment} from "../../model/Comment";
import {Show} from "../../model/Show";
import {getBackend} from "../global-scope/util/getters";
import {AuthToken} from "../global-scope/context/AuthContext";
import {fromPromise, toJSON, toModel} from "../request/mappers";

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
            .map(v => toModel(v.posts, this.backend().entity.createPost))
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
            .map(v => toModel(v.shows, this.backend().entity.createShow))
    }

    fetchComments(limit: number, latest?: EntityIdentifier): BackendAction<Comment[]> {
        const route = Routes.Comment.LIST.compile()

        if (latest) {
            route.withQueryParam('last', latest.toString())
        }

        route.withQueryParam('limit', limit.toString(10))

         return BackendAction.new(route)
            .flatMap(toJSON)
            .map(v => toModel(v.comments, this.backend().entity.createComment))
    }

    private readonly backend = () => getBackend()

    loadSelfUser(token: AuthToken): BackendAction<User> {
         const route = Routes.Self.FETCH.compile()
        route.withQueryParam('token', token)

        return BackendAction.new(route)
            .flatMap(toJSON)
            .map(this.backend().entity.createUser)
    }
}
