import {HttpManager} from "./manager/HttpManager";
import {CacheManager} from "./manager/CacheManager";
import {AuthManager} from "./manager/AuthManager";
import {EntityManager} from "./manager/EntityManager";

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

