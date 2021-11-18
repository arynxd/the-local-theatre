import {BackendRequestJSONTransformer} from "./BackendAction";
import {GenericModel} from "../../model/GenericModel";
import {isJSONArray, isJSONObject, JSONObject, JSONValue} from "../JSONObject";
import BackendError from "../error/BackendError";

type JSONToModel <T extends GenericModel> = (json: JSONObject) => T
type ArrayExtraction = (json: JSONObject) => JSONValue

/**
 * Provides a convenience request transformer to model types
 *
 * @param arrayExtraction The array extract function, should extract an array from the JSON.
 *                        This function can return null if the array does not exist in JSON.
 *
 * @param conversion      The conversion function, converts JSON to the model type.
 *                        This function should throw when invalid data is received
 */
export function ModelTransformer <T extends GenericModel>
    (arrayExtraction: ArrayExtraction, conversion: JSONToModel<T>)
    : BackendRequestJSONTransformer<T[]> {
    return res => {
        const arr = arrayExtraction(res)

        if (isJSONArray(arr)) {
                                           // we just filtered for this, TS just cant infer it
                                           // as such, casting is ok
            return arr.filter(isJSONObject).map(v => conversion(v as JSONObject))
        }
        throw new BackendError('Data was invalid, expected array got ' + arr)
    }
}

