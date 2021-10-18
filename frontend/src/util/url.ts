import * as pack from '../../package.json'

export function getPrefix() {
    let prefix

    if (process.env.NODE_ENV === 'development') {
        prefix = "http://localhost:8000/"
    }
    else {
        prefix = pack.homepage
    }
    return prefix
}

export function fetch(input: RequestInfo, init?: RequestInit): Promise<Response> {
    return global.fetch(getPrefix() + input, init)
}