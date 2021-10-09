import * as pack from '../../package.json'

export function fetch(input: RequestInfo, init?: RequestInit): Promise<Response> {
    let prefix

    if (process.env.NODE_ENV === 'development') {
        prefix = "localhost:8000/"
    }
    else {
        prefix = pack.homepage
    }
    return global.fetch(prefix + input, init)
}