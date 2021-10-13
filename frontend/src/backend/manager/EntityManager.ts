import {Manager} from "./Manager";
import {JSONObject} from "../JSONObject";
import {isUser, User} from "../../model/User";
import BackendError from "../error/BackendError";
import {isPost, Post} from "../../model/Post";

export class EntityManager extends Manager {
    private err(type: string, json: JSONObject): never {
        throw new BackendError("JSON was not a valid " + type + " object. Got " + json + " instead")
    }

    public createUser(json: JSONObject): User {
        if (!isUser(json)) {
            this.err("User", json)
        }

        this.backend.cache.cacheUser(json)
        return json
    }

    public createPost(json: JSONObject): Post {
        if (!isPost(json)) {
            this.err('Post', json)
        }
        return json
    }
}