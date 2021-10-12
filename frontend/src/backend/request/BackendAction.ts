import {CompiledRoute} from "./route";
import {fetch} from "../../util/url";
import Backend from "../Backend";
import APIError, {isAPIError} from "../../model/APIError";
import BackendError from "../BackendError";

export default class BackendAction<T> extends Promise<T> {
    constructor(
        private readonly transformer: BackendRequestTransformer<T>,
        private readonly backend: Backend,
        private readonly route: CompiledRoute
    ) {
        super(async (res, rej) => { await this.send(res, rej) });
    }

    private async send(resolve: (value: T | PromiseLike<T>) => void, reject: (reason: APIError) => void) {
        this.route.withHeader('Authorisation', this.backend.token)

        const result = await fetch(this.route.url, {
            method: this.route.routeData.method,
            headers: this.route.flattenHeaders(),
        })

        if (result.ok) {
            resolve(this.transformer(result))
        }
        else {
            const json = await result.json()

            if (typeof json !== 'object' || !json) {
                throw new BackendError('JSON response was malformed. Expected object, got ' + json)
            }

            if (isAPIError(json)) {
                reject(json as APIError)
            }
        }
    }
}

type BackendRequestTransformer<T> = (res: Response) => T