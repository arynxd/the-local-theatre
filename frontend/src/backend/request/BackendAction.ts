import {fetch} from "../../util/url";
import {isAPIError} from "../../model/APIError";
import BackendError from "../error/BackendError";
import {CompiledRoute} from "./route/CompiledRoute";
import {JSONObject} from "../JSONObject";
import {logger} from "../../util/log";
import {assert} from "../../util/assert";
import {getAuth} from "../global-scope/util/getters";
import {ValidTypeOf} from "./mappers";

export type BackendRequestTransformer<T> = (res: Response) => T | BackendAction<T>
export type BackendRequestJSONTransformer<T> = (res: JSONObject) => T | BackendAction<T>
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
            logger.debug("Route & Opts ", route, opts)
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
            const json = JSON.parse(await result.text())

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


