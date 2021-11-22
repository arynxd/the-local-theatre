import {Manager} from "./Manager";
import {User} from "../../model/User";
import Routes from "../request/route/Routes";
import {Post} from "../../model/Post";
import {newBackendAction} from "../request/BackendAction";
import {EntityIdentifier} from "../../model/EntityIdentifier";
import {Comment} from "../../model/Comment";
import {Show} from "../../model/Show";
import {ModelTransformer} from "../request/Transformers";
import {getBackend} from "../global-scope/util/getters";
import {AuthToken} from "../global-scope/context/AuthContext";

/**
 * Manages all HTTP duties for the backend
 * **ALL** requests should go through this manager
 */
export class HttpManager extends Manager {
    async loadUser(id: EntityIdentifier): Promise<User> {
        const route = Routes.User.FETCH.compile()
        route.withQueryParam('id', id.toString())

        return newBackendAction(route, this.backend().entity.createUser)
    }

    /**
     * Returns the blob of the image
     * @param user
     */
    async loadAvatar(user: User): Promise<Blob> {
        const route = Routes.User.AVATAR.compile()
        route.withQueryParam('id', user.id)

        return newBackendAction(route, undefined, res => res.blob())
    }

    async listPosts(limit: number, last?: EntityIdentifier): Promise<Post[]> {
        const route = Routes.Post.LIST.compile()

        if (last) {
            route.withQueryParam('start', last.toString())
        }

        route.withQueryParam('limit', limit.toString(10))

        return newBackendAction(route, ModelTransformer<Post>(res => res.posts, this.backend().entity.createPost))
    }

    async loadShowImage(show: Show): Promise<Blob> {
        const route = Routes.Show.IMAGE.compile()
        route.withQueryParam('id', show.id)

        return newBackendAction(route, undefined, res => res.blob())
    }

    async loadShows(limit: number): Promise<Show[]> {
        const route = Routes.Show.LIST.compile()
        route.withQueryParam('limit', limit.toString(10))


        return newBackendAction(route, ModelTransformer<Show>(res => res.shows, this.backend().entity.createShow))
    }

    async fetchComments(limit: number, latest?: EntityIdentifier): Promise<Comment[]> {
        const route = Routes.Comment.LIST.compile()

        if (latest) {
            route.withQueryParam('last', latest.toString())
        }

        route.withQueryParam('limit', limit.toString(10))

        return newBackendAction(route, ModelTransformer<Comment>(res => res.comments, this.backend().entity.createComment))
    }

    private readonly backend = () => getBackend()

    async loadSelfUser(token: AuthToken): Promise<User> {
         const route = Routes.Self.FETCH.compile()
        route.withQueryParam('token', token)

        return newBackendAction(route, this.backend().entity.createUser)
    }
}
