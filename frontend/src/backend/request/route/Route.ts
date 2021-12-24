import { CompiledRoute } from './CompiledRoute'
import { logger } from '../../../util/log'

/**
 * A stateless class representing a Route on the backend API
 * Compiled with the compile() function before use in a BackendAction
 */
export class Route {
    public constructor(
        public readonly path: Path,
        public readonly method: Method,
        public readonly requiredQueryParams: QueryParams,
        public readonly requiredDataKeys: string[],
        public readonly requiresAuth: boolean
    ) {}

    compile(): CompiledRoute {
        logger.debug('Compiling route ' + this.path)
        return new CompiledRoute(this)
    }
}

export type Method = 'GET' | 'POST' | 'PATCH' | 'PUT' | 'DELETE'

export type Path = string

export type QueryParams = string[]
