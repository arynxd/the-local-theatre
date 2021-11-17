import {logger} from "./log";

/**
 * Provides the URL prefix for making backend requests based on the current environment.
 * The returned string **WILL** have a slash at the end.
 * 
 * @returns The URL prefix
 */
export function getPrefix(): string {
    if (process.env.NODE_ENV === 'development') {
        return 'http://localhost:8000/'
    }
    else if (process.env.NODE_ENV === 'production') {
        return window.location.toString()
    }

    throw new TypeError('Could not locate prefix.')
}

/**
 * Makes a wrapped fetch request using getPrefix(). Used for making backend requests without specifying the whole URL.
 * 
 * @param input The RequestInfo to make the request with
 * @param init  The optional options to supply to the request
 * @returns The resulting promise representing the request operation
 */
export function fetch(input: RequestInfo, init?: RequestInit): Promise<Response> {
    logger.debug('Sending wrapped fetch() for ' + getPrefix() + input + ' with init ' + JSON.stringify(init))
    return global.fetch(getPrefix() + input, init)
}
