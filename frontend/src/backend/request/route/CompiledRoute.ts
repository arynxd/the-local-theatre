import {JSONObject} from "../../JSONObject";
import BackendError from "../../error/BackendError";
import {Route} from "./Route";

/**
 * A stateful class used per-request to hold metadata about the request
 *
 * Holds information about:
 *  - URL params
 *  - Headers
 *  - Request body
 */
export class CompiledRoute {
    private readonly queryParams = new Map<string, string>()
    private readonly headers = new Map<string, string>()
    private body: JSONObject = {}

    constructor(public readonly routeData: Route) {

    }

    get url(): string {
        // me being lazy, just gonna use this object for string[][] to x=x&y=y conversion
        let res = this.routeData.path

        if (this.queryParams.size) { // append '?' if there are args present
            res += "?"
        }

        res += new URLSearchParams(this.flattenQueryParams()).toString()
        return res
    }

    withQueryParam(key: string, value: string): CompiledRoute {
        this.queryParams.set(key, value)
        return this
    }

    withHeader(key: string, value: string): CompiledRoute {
        this.headers.set(key, value)
        return this
    }

    withBody(json: JSONObject): CompiledRoute {
        this.body = json
        return this
    }

    flattenHeaders(): string[][] {
        const out: string[][] = []

        for (const [key, value] of this.headers) {
            out.push([key, value])
        }

        return out
    }

    flattenQueryParams(): string[][] {
        const out: string[][] = []

        for (const [key, value] of this.queryParams) {
            out.push([key, value])
        }

        return out
    }

    validate() {
        const hasAllQueryParams = this.routeData.requiredQueryParams?.every(p => this.queryParams.has(p), this) ?? true

        if (!hasAllQueryParams) {
            throw new BackendError(
                'Route failed validation. Missing query params. ' +
                'Expected ' + this.routeData.requiredQueryParams +
                'Received ' + this.flattenQueryParams()
            )
        }
    }

    stringifyBody(): string {
        return JSON.stringify(this.body)
    }
}