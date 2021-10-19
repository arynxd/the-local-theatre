import {logger} from "./log";

export function getPrefix() {
    if (process.env.NODE_ENV === 'development') {
        return 'http://localhost:8000/'
    }
    return window.location.origin + "/"
}

export function fetch(input: RequestInfo, init?: RequestInit): Promise<Response> {
    logger.debug('Sending wrapped fetch() for ' + getPrefix() + input + ' with init ' + JSON.stringify(init))
    return global.fetch(getPrefix() + input, init)
}