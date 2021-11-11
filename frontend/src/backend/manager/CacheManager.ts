import {Manager} from "./Manager";
import {UserCache} from "../cache/UserCache";

/**
 * Manages all the entity caches used in the app
 * Used by the app to reduce the number of backend requests
 */
export class CacheManager extends Manager {
    /**
     * The user cache
     */
    public readonly user = new UserCache(this.backend)
}

