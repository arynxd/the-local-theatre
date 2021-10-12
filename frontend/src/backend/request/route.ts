import BackendError from "../BackendError";
import { JSON } from "../JSON";

export class Route {
    private constructor(
        public readonly path: Path,
        public readonly method: Method,
        public readonly requiredQueryParams: QueryParams,
        public readonly requiredBodyKeys: string[]
    ) { }

    compile(): CompiledRoute {
        return new CompiledRoute(this)
    }

    public static readonly User = class {
        public static readonly FETCH = new Route('api/user', 'GET', ['id'], [])
        public static readonly FETCH_ALL = new Route('api/user/list', 'GET', ['limit'], [])
        public static readonly UPDATE = new Route('api/user', 'POST', [], [])

    }
}

export class CompiledRoute {
    private readonly queryParams = new Map<string, string>()
    private readonly headers = new Map<string, string>()
    private body: JSON = {}

    constructor(public readonly routeData: Route) { }

    withQueryParam(key: string, value: string): CompiledRoute {
        this.queryParams.set(key, value)
        return this
    }

    withHeader(key: string, value: string): CompiledRoute {
        this.headers.set(key, value)
        return this
    }

    withBody(json: JSON): CompiledRoute {
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

    get url(): string {
        // me being lazy, just gonna use this object for string[][] to x=x&y=y conversion
        return this.routeData.path + new URLSearchParams(this.flattenQueryParams()).toString()
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
}

export type Method = 'GET' | 'POST' | 'PATCH' | 'PUT' | 'DELETE'

export type Path = string

export type QueryParams = string[]
