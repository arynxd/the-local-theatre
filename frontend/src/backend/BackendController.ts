import {HttpManager} from "./manager/HttpManager";
import {CacheManager} from "./manager/CacheManager";
import {AuthManager} from "./manager/AuthManager";
import {EntityManager} from "./manager/EntityManager";

/**
 * Primary class for all backend operations
 * Contains instances of the managers required to utilise the backend
 * 
 * This class should only be created once, due to the expensive nature of its creation
 */
export class BackendController {
    public readonly cache: CacheManager
    public readonly http: HttpManager
    public readonly auth: AuthManager
    public readonly entity: EntityManager

    constructor() {
        this.cache = new CacheManager(this)
        this.http = new HttpManager(this)
        this.auth = new AuthManager(this)
        this.entity = new EntityManager(this)
    }
}

