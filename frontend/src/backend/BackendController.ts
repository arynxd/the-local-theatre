import {HttpManager} from "./manager/HttpManager";
import {CacheManager} from "./manager/CacheManager";
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
    public readonly entity: EntityManager

    constructor() {
        this.cache = new CacheManager()
        this.http = new HttpManager()
        this.entity = new EntityManager()
    }
}

