import {useEffect, useState} from "react";
import {logger} from "../../util/log";
import BackendError from "../error/BackendError";

/**
 * A React hook for making API requests
 *
 * Typically used with HttpManager's methods, which output BackendAction
 *
 * @param action The action to use
 * @param errorHandler The error handler to use, defaults to an error log
 * @returns Promise<T>|undefined The  resolved value, or undefined if this request has not resolved yet
 */
export function useAPI<T>(action: Promise<T>, errorHandler: (err: BackendError) => void = logger.error): T | undefined {
    const [res, setRes] = useState<T>()

    useEffect(() => {
        action.then(setRes).catch(errorHandler)
        // eslint-disable-next-line
    }, [])
    return res
}
