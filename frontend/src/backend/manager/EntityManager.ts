import {Manager} from "./Manager";
import {JSONObject} from "../JSONObject";
import {isUser, User} from "../../model/User";
import BackendError from "../error/BackendError";
import {isPost, Post} from "../../model/Post";
import {Comment, isComment} from "../../model/Comment";
import {isShow, Show} from "../../model/Show";
import {getBackend} from "../global-scope/util/getters";

/**
 * Manages the creation of entities, primarily used in HttpManager for transformation of JSON responses
 */
export class EntityManager extends Manager {
    public createUser(json: JSONObject): User {
        if (!isUser(json)) {
            this.err("User", json)
        }

        getBackend().cache.user.set(json.id, json)
        return json
    }

    public createPost(json: JSONObject): Post {
        if (!isPost(json)) {
            this.err('Post', json)
        }
        return json
    }

    public createComment(json: JSONObject): Comment {
        if (!isComment(json)) {
            this.err('Comment', json)
        }
        return json
    }

    public createShow(json: JSONObject): Show {
        if (!isShow(json))
            this.err('Show', json)

        return json
    }

    private err(type: string, json: JSONObject): never {
        throw new BackendError("JSON was not a valid " + type + " object. Got " + JSON.stringify(json) + " instead")
    }
}