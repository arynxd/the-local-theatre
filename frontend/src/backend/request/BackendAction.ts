
import {fetch} from "../../util/url";
import {BackendController} from "../BackendController";
import {isAPIError} from "../../model/APIError";
import BackendError from "../error/BackendError";
import {CompiledRoute} from "./route/CompiledRoute";
import {JSONObject} from "../JSONObject";
import {logger} from "../../util/log";

export function BackendAction<T>(
    backend: BackendController,
    route: CompiledRoute,
    JSONTransformer?: BackendRequestJSONTransformer<T>,
    requestTransformer?: BackendRequestTransformer<T>
) {
    logger.debug('Starting backend action for URL ' + route.url)
    return new Promise<T>(async (resolve, reject) => {
        if (route.routeData.requiresAuth) {
            route.withHeader('Authorisation', backend.auth.token)
            logger.debug('Authorisation required, adding the header')
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

            result = await fetch(route.url, opts)
        }
        catch (ex) {
            let msg = ""
            msg += "Backend request to URL " + route.url + " failed\n\n"
            msg += ex

            logger.error(new BackendError(msg))
            throw new BackendError(msg)
        }

        if (result.ok) {
            if (requestTransformer) {
                resolve(requestTransformer(result))
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
                resolve(JSONTransformer(jsonObj))
            }
        }
        else {
            const json = await result.json()

            if (typeof json !== 'object' || !json) { // assert that its some type of json object
                                                     // throwing is ok because this is an assertion and ideally should never happen
                const ex = new BackendError('JSON response was malformed. Expected object, got ' + json)
                logger.error(ex)
                throw ex
            }

            if (isAPIError(json)) {
                logger.error("API returned an error: \n " + JSON.stringify(json))
                reject(json)
            }
        }
    })
}


type BackendRequestTransformer<T> = (res: Response) => T
type BackendRequestJSONTransformer<T> = (res: JSONObject) => T