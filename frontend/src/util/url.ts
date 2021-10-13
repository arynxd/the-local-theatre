import * as pack from '../../package.json'
import BackendError from "../backend/error/BackendError";

export function _fetch(input: RequestInfo, init?: RequestInit): Promise<Response> {
    let prefix

    if (process.env.NODE_ENV === 'development') {
        prefix = "localhost:8000/"
    }
    else {
        prefix = pack.homepage
    }

    console.log(prefix + input)
    return global.fetch(prefix + input, init)
}