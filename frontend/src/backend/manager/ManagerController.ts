import { HttpManager } from './HttpManager'
import { CacheManager } from './CacheManager'
import { EntityManager } from './EntityManager'

/**
 * Primary class for all backend operations
 * Contains instances of the managers required to utilise the backend
 *
 * This class should only be created once, due to the expensive nature of its creation
 */
export class ManagerController {
	public readonly cache: CacheManager
	public readonly http: HttpManager
	public readonly entity: EntityManager

	constructor() {
		this.cache = new CacheManager()
		this.http = new HttpManager()
		this.entity = new EntityManager()
	}
}
