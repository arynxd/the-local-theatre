import {Manager} from "./Manager";
import {UserCache} from "../cache/UserCache";

export class CacheManager extends Manager {
    public readonly user = new UserCache(this.backend)
}

