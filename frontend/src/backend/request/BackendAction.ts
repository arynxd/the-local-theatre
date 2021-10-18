
import {fetch} from "../../util/url";
import {BackendController} from "../BackendController";
import {isAPIError} from "../../model/APIError";
import BackendError from "../error/BackendError";
import {CompiledRoute} from "./route/CompiledRoute";
import {JSONObject} from "../JSONObject";

export function BackendAction<T>(
    backend: BackendController,
    route: CompiledRoute,
    JSONTransformer?: BackendRequestJSONTransformer<T>,
    requestTransformer?: BackendRequestTransformer<T>
) {
    return new Promise<T>(async (resolve, reject) => {
        if (route.routeData.requiresAuth) {
            route.withHeader('Authorisation', backend.auth.token)
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

            reject(new BackendError(msg))
            return
        }

        if (result.ok) {
            if (requestTransformer) {
                resolve(requestTransformer(result))
            }
            else if (JSONTransformer) {
                const json = await result.json() as JSONObject
                resolve(JSONTransformer(json))
            }
        }
        else {
            const json = await result.json()

            if (typeof json !== 'object' || !json) { // assert that its some type of json object
                                                     // throwing is ok because this is an assertion and ideally should never happen
                throw new BackendError('JSON response was malformed. Expected object, got ' + json)
            }

            if (isAPIError(json)) {
                reject(json)
            }
        }
    })
}


type BackendRequestTransformer<T> = (res: Response) => T
type BackendRequestJSONTransformer<T> = (res: JSONObject) => T