import {Manager} from "./Manager";
import {User} from "../../model/User";
import Routes from "../request/route/Routes";
import {Post} from "../../model/Post";
import {isJSONArray, isJSONObject, JSONObject} from "../JSONObject";
import {BackendAction} from "../request/BackendAction";
import {EntityIdentifier} from "../../model/EntityIdentifier";

export class HttpManager extends Manager {
    async loadUser(id: EntityIdentifier): Promise<User> {
        const route = Routes.User.FETCH.compile()
        route.withQueryParam('id', id.toString())

        return BackendAction(this.backend, route, res => this.backend.entity.createUser(res))
    }

    async listPosts(limit: number, start?: EntityIdentifier): Promise<Post[]> {
        const route = Routes.Post.LIST.compile()

        if (start) {
            route.withQueryParam('start', start.toString())
        }

        //route.withQueryParam('limit', limit.toString(10))

        return BackendAction(this.backend, route, res => {
            const arr = res['posts']

            if (isJSONArray(arr)) {
                return arr.filter(isJSONObject)
                    .map(val => this.backend.entity.createPost(val as JSONObject)) // we just filtered for this, TS just cant infer it
            }
            return []
        })
    }
}