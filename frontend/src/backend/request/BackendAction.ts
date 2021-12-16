import {fetch} from "../../util/url";
import {isAPIError} from "../../model/APIError";
import BackendError from "../error/BackendError";
import {CompiledRoute} from "./route/CompiledRoute";
import {logger} from "../../util/log";
import {assert} from "../../util/assert";
import {getAuth, getBackend} from "../global-scope/util/getters";
import {toJSON, ValidTypeOf} from "./mappers";
import { AbstractCache } from "../cache/AbstractCache";
import { EntityIdentifier } from "../../model/EntityIdentifier";

export type BackendActionLike<T> = BackendAction<T> | Promise<T>

export class BackendAction<T> extends Promise<T> {
    /**
     * Create a new backend action.
     * This is just a class around a Promise.
     * This function will always reject with a BackendError, so it is safe to cast to this type.

     * @param route The route to request
     * @returns BackendAction<T> An action representing the request
     */
    public static new(
        route: CompiledRoute
    ): BackendAction<Response> {
        return newBackendAction(route)
    }

    /**
     * Makes a backend request, hitting the cache first.
     * If the cache lookup fails, the provided onMiss function will be used to resolve the value.
     * 
     * @param cacheSelector The function to get the cached value
     * @param onMiss The function to run when the cache misses
     * @returns The action representing this request
     */
    public static usingCache <T> (cacheSelector: () => T | undefined, onMiss: () => BackendAction<T>): BackendAction<T> {
        return new BackendAction(async (res, rej) => {
            let cacheHit: T | undefined

            try {
                cacheHit = cacheSelector()
            }
            catch (ex) {
                rej(ex)
                return
            }
            
            if (cacheHit) {
                res(cacheHit)
            }
            else {
                onMiss()
                    .then(res)
                    .catch(rej)
            }
        })
    }

    public flatMap<U>(mapper: (action: T) => BackendActionLike<U>): BackendAction<U> {
        return new BackendAction<U>((res, rej) => {
            return this.then(v => {
                mapper(v)
                    .then(v2 => res(v2))
                    .catch(err => rej(err))
            }).catch(err => rej(err))
        })
    }

    public map<U>(mapper: (value: T) => U): BackendAction<U> {
        return new BackendAction<U>((res, rej) => {
            return this.then(v => res(mapper(v))).catch(rej)
        })
    }

    public assertTypeOf<U extends keyof ValidTypeOf>(type: U): BackendAction<ValidTypeOf[U]> {
        return new BackendAction<ValidTypeOf[U]>((res, rej) => {
            return this.then(v => {
                this.throwIfTypeOfInvalid(v, type)
                res(v)
            }).catch(rej)
        })
    }

    public also(effect: (value: T) => void): BackendAction<T> {
        this.then(effect)
        return this
    }

    public toVoid(): BackendAction<void> {
        return this.map(() => null!!)
    }

    public toPromise(): Promise<T> {
        return this
    }

    private throwIfTypeOfInvalid<U extends keyof ValidTypeOf>(value: unknown, type: U): asserts value is ValidTypeOf[U] {
        if (typeof value !== type) {
            throw new TypeError("Assertion failed, value was not of type " + type + " \n Got " + JSON.stringify(value) + " instead")
        }
    }
}

function newBackendAction(
    route: CompiledRoute
): BackendAction<Response> {
    logger.debug('Starting backend action for URL ' + route.url)
    return new BackendAction<Response>(async (resolve, reject) => {
        route.validate()
        const auth = getAuth()
        if (route.routeData.requiresAuth) {
            if (!auth.token) {
                let msg = 'Route ' + route + ' requires auth but the AuthManager has no token set.'

                logger.error(new BackendError(msg))
                reject(new BackendError(msg))
                throw new BackendError(msg)
            }
            route.withHeader('Authorisation', auth.token)
            logger.debug('Authorisation required, added the header')
        }
        let result: Response

        try {
            let opts: RequestInit = {
                method: route.routeData.method,
                headers: route.flattenHeaders(),
                mode: 'cors'
            }

            if (route.routeData.method !== 'GET') {
                opts.body = route.stringifyBody()
            }

            logger.debug("Route & Opts ", route, " Opts => ", opts)
            result = await fetch(route.url, opts)
        }
        catch (ex) {
            let msg = ""
            msg += "Backend request to URL " + route.url + " failed\n\n"
            msg += ex

            logger.error(new BackendError(msg))
            reject(new BackendError(msg))
            throw new BackendError(msg)
        }

        if (result.ok) {
            resolve(result)
        }
        else {
            const json = await toJSON(result)

            const ex = new BackendError('JSON response was malformed. Expected object, got ' + JSON.stringify(json))

            // assert that the json we received was of type object, and was not null/undefined
            assert(() => typeof json === 'object', () => ex)
            assert(() => json != null, () => ex)

            if (isAPIError(json)) {
                logger.error("API returned an error: \n " + JSON.stringify(json))
                reject(json)
            }
        }
    })
}