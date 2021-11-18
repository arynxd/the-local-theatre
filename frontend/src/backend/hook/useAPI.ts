import {logger} from "../../util/log";
import BackendError from "../error/BackendError";
import {BackendAction} from "../request/BackendAction";
import usePromise from "react-use-promise";

/**
 * A React hook for making API requests
 *
 * Typically used with HttpManager's methods, which output BackendAction
 *
 * @param action The action to use
 * @param deps   The dependencies to use, when these variables change, the request will be done again
 * @param errorHandler The error handler to use, defaults to an error log
 * @returns Promise<T>|undefined The  resolved value, or undefined if this request has not resolved yet
 */
export function useAPI<T>(
    action: () => BackendAction<T>,
    deps: any[] = [],
    errorHandler: (err: BackendError) => void = logger.error
) : T | undefined
{
    const [res, err,,] = usePromise(action, deps)

    if (err) {
        errorHandler(err)
    }
    return res
}
