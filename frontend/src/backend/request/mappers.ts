import {isJSONArray, isJSONObject, JSONObject, JSONValue} from "../JSONObject";
import {logger} from "../../util/log";
import BackendError from "../error/BackendError";
import {BackendAction} from "./BackendAction";
import {GenericModel} from "../../model/GenericModel";
import {getBackend} from "../global-scope/util/getters";

export interface ValidTypeOf {
    'undefined': undefined,
    'null': null,
    'boolean': boolean,
    'string': string,
    'number': number,
    'bigint': bigint,
    'symbol': symbol,
    'object': object,
    'function': ((...args: unknown[]) => unknown)
}

export function toJSON(response: Response): BackendAction<JSONObject> {
    return new BackendAction<JSONObject>(async (res, rej) => {
        const json = await response.text()

        let jsonObj: JSONObject

        try {
            jsonObj = JSON.parse(json) as JSONObject
        }
        catch (ex) {
            let msg = ""

            msg += "Failed to parse JSON for backend response. Expected valid JSON got: \n"
            msg += JSON.stringify(json)
            logger.error(new BackendError(msg))
            rej(new BackendError(msg))
            throw new BackendError(msg)
        }

        logger.debug('Got a valid JSON response: \n ' + JSON.stringify(jsonObj))
        res(jsonObj)
    })
}

export function throwIfNull<T>(value: T | undefined | null): T {
    if (!value) {
        throw new TypeError("Assertion failed, value was null or undefined")
    }
    return value
}

export function fromPromise<T>(promise: Promise<T>): BackendAction<T> {
    return new BackendAction((res, rej) => promise.then(res).catch(rej))
}


/**
 * Provides a convenience request transformer to model types
 *
 * @param value           The value to transform, this will be type checked for array
 *
 * @param conversion      The conversion function, converts JSON to the model type.
 *                        This function should throw when invalid data is received
 */
export function toModelArray<T extends GenericModel>(value: JSONValue, conversion: (json: JSONObject) => T): T[] {
    if (isJSONArray(value)) {
        // we just filtered for this, TS just cant infer it
        // as such, casting is ok
        return value.filter(isJSONObject).map(v => conversion.call(getBackend().entity, v as JSONObject))
    }
    throw new BackendError('Data was invalid, expected array got ' + JSON.stringify(value))
}

export function toModel<T extends GenericModel>(value: JSONValue, conversion: (json: JSONObject) => T): T {
    if (isJSONObject(value)) {
        return conversion.call(getBackend().entity, value)
    }
    throw new BackendError("Data was invalid, expected object got " + JSON.stringify(value))
}

export function toURL(blob: Blob): string {
    return URL.createObjectURL(blob)
}