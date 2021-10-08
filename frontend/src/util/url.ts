import * as pack from '../../package.json'

export function fetch(input: RequestInfo, init?: RequestInit): Promise<Response> {
    return global.fetch(pack.homepage + input, init)
}