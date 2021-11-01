import {logger} from "./log";

export function getPrefix(): string {
    if (process.env.NODE_ENV === 'development') {
        return 'http://localhost:8000/'
    }
    else if (process.env.NODE_ENV === 'production') {
        return window.location.toString()
    }

    throw new TypeError('Could not locate prefix.')
}

export function fetch(input: RequestInfo, init?: RequestInit): Promise<Response> {
    logger.debug('Sending wrapped fetch() for ' + getPrefix() + input + ' with init ' + JSON.stringify(init))
    return global.fetch(getPrefix() + input, init)
}
