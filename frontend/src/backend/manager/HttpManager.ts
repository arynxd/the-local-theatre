import {Manager} from "./Manager";
import {User} from "../../model/User";
import Routes from "../request/route/Routes";
import {Post} from "../../model/Post";
import {isJSONArray, isJSONObject, JSONObject} from "../JSONObject";
import {BackendAction} from "../request/BackendAction";
import {EntityIdentifier} from "../../model/EntityIdentifier";
import {Comment} from "../../model/Comment";
import BackendError from "../error/BackendError";

/**
 * Manages all HTTP duties for the backend
 * **ALL** requests should go through this manager
 */
export class HttpManager extends Manager {
    async loadUser(id: EntityIdentifier): Promise<User> {
        const route = Routes.User.FETCH.compile()
        route.withQueryParam('id', id.toString())

        return BackendAction(this.backend, route, res => this.backend.entity.createUser(res))
    }

    /**
     * Returns the blob of the image
     * @param user
     */
    async loadAvatar(user: User): Promise<Blob> {
        const route = Routes.User.AVATAR.compile()
        route.withQueryParam('id', user.id)

        return BackendAction(this.backend, route, undefined, res => res.blob())
    }

    async listPosts(limit: number, last?: EntityIdentifier): Promise<Post[]> {
        const route = Routes.Post.LIST.compile()

        if (last) {
            route.withQueryParam('start', last.toString())
        }

        route.withQueryParam('limit', limit.toString(10))

        return BackendAction(this.backend, route, res => {
            const arr = res.posts

            if (isJSONArray(arr)) {
                return arr.filter(isJSONObject)
                    .map(val => this.backend.entity.createPost(val as JSONObject)) // we just filtered for this, TS just cant infer it
            }
            throw new BackendError('Data was invalid, expected array got ' + arr)
        })
    }

    async fetchComments(limit: number, latest?: EntityIdentifier): Promise<Comment[]> {
        const route = Routes.Comment.LIST.compile()

        if (latest) {
            route.withQueryParam('last', latest.toString())
        }

        route.withQueryParam('limit', limit.toString(10))

        return BackendAction(this.backend, route, res => {
            const arr = res.comments

            if (isJSONArray(arr)) {
                return arr.filter(isJSONObject)
                    .map(val => this.backend.entity.createComment(val as JSONObject)) // we just filtered for this, TS just cant infer it
            }
            throw new BackendError('Data was invalid, expected array got ' + arr)
        })
    }
}
