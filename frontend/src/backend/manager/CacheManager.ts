import {Guid} from "guid-typescript";
import {User} from "../../model/User";
import {Manager} from "./Manager";

class Cache<K, V> extends Map<K, V> { }

export class CacheManager extends Manager {
    private readonly user = new Cache<Guid, User>()

    cacheUser(user: User) {
        this.user.set(user.id, user)
    }

    async getUser(id: Guid): Promise<User> {
        return this.user.get(id) ?? await this.backend.http.loadUser(id)
    }
}

