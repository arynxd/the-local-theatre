import {AbstractCache} from "./AbstractCache";
import {EntityIdentifier} from "../../model/EntityIdentifier";
import {User} from "../../model/User";
import {getBackend} from "../global-scope/util/getters";

export class UserCache extends AbstractCache<User> {
    public async fetch(key: EntityIdentifier): Promise<User> {
        return this.get(key) ?? await getBackend().http.loadUser(key)
    }

    public cache(key: EntityIdentifier, value: User): void {
        this.set(key, value)
    }
}