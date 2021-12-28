import { logger } from '../../util/log'
import BackendError from '../error/BackendError'
import usePromise from 'react-use-promise'
import { useState } from 'react'

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
	action: () => Promise<T>,
	errorHandler: (err: BackendError) => void = () => {},
	deps: unknown[] = []
): T | undefined {
	const [res, err] = usePromise(action, deps)
	// make sure we only fire the error handler once
	const [isErrorFired, setErrorFired] = useState(false)

	if (err && !isErrorFired) {
		setErrorFired(true)
		logger.error(err) // force a log, we dont want errors supressed by the handler
		errorHandler(err)
	}
	return res
}
