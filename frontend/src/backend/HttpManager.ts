
import CacheManager from "./CacheManager";
import {EntityIdentifier} from "../model/EntityIdentifier";
import User from "../model/User";

export default class HttpManager {
    public readonly userCache = new CacheManager<EntityIdentifier, User>()

    async loadUsers() {
        

        
    }
}