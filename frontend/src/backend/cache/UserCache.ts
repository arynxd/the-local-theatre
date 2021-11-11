import {AbstractCache} from "./AbstractCache";
import {EntityIdentifier} from "../../model/EntityIdentifier";
import {User} from "../../model/User";

export class UserCache extends AbstractCache<EntityIdentifier, User> {
    public async fetch(key: EntityIdentifier): Promise<User> {
        return this.get(key) ?? await this.backend.http.loadUser(key)
    }

    public cache(key: EntityIdentifier, value: User): void {
        this.set(key, value)
    }
}