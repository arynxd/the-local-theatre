import {fetch} from "../../util/url";
import {isAPIError} from "../../model/APIError";
import BackendError from "../error/BackendError";
import {CompiledRoute} from "./route/CompiledRoute";
import {JSONObject} from "../JSONObject";
import {logger} from "../../util/log";
import {assert, assertTruthy} from "../../util/assert";
import {getAuth} from "../global-scope/util/getters";

export type BackendRequestTransformer<T> = (res: Response) => T | Promise<T>
export type BackendRequestJSONTransformer<T> = (res: JSONObject) => T | Promise<T>
export type BackendAction<T> = Promise<T>

/**
 * Create a new backend action.
 * This is just a function around a Promise.
 * This function will always reject with a BackendError, so it is safe to cast to this type.
 *
 * When requestTransformer is present, it will take priority over JSONTransformer
 * As such, Response#json will **NOT** be called when a requestTransformer is present
 *
 * @param route The route to request
 * @param JSONTransformer The transformer function to transform a JSON response
 * @param requestTransformer The transformer function to transform a regular response
 * @returns A Promise representing the request
 */
export function newBackendAction<T>(
    route: CompiledRoute,
    JSONTransformer?: BackendRequestJSONTransformer<T>,
    requestTransformer?: BackendRequestTransformer<T>
): BackendAction<T> {
    logger.debug('Starting backend action for URL ' + route.url)
    return new Promise<T>(async (resolve, reject) => {
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
            if (requestTransformer) {
                resolve(await requestTransformer(result))
            }
            else if (JSONTransformer) {
                const json = await result.text()

                let jsonObj: JSONObject

                try {
                    jsonObj = JSON.parse(json) as JSONObject
                }
                catch (ex) {
                    let msg = ""

                    msg += "Failed to parse JSON for backend response. Expected valid JSON got: \n"
                    msg += json
                    logger.error(new BackendError(msg))
                    throw new BackendError(msg)
                }

                logger.debug('Got a valid JSON response: \n ' + JSON.stringify(jsonObj))
                resolve(await JSONTransformer(jsonObj))
            }
        }
        else {
            const json = await result.json()

            const ex = new BackendError('JSON response was malformed. Expected object, got ' + json)

            // assert that the json we received was of type object, and was not null/undefined
            assert(() => typeof json === 'object', () => ex)
            assertTruthy(() => json, () => ex)

            if (isAPIError(json)) {
                logger.error("API returned an error: \n " + JSON.stringify(json))
                reject(json)
            }
        }
    })
}

