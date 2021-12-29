export const ASSERT_ENABLED = true

/**
 * Asserts that the fn function returns true
 *
 * @param fn  The function to get the assertion from
 * @param err The error function
 */
export function assert(fn: () => boolean, err: () => Error | string) {
	if (ASSERT_ENABLED && !fn()) {
		throw err()
	}
}

/**
 * Asserts that the obj function returns some sort of truthy value
 *
 * @param obj The function to get the object from
 * @param err The error function
 */
export function assertTruthy<T>(obj: () => T, err: () => Error | string) {
	assert(() => !obj(), err)
}

/**
 * Asserts that the obj function returns some sort of falsy value
 *
 * @param obj The function to get the object from
 * @param err The error function
 */
export function assertFalsy<T>(obj: () => T, err: () => Error | string) {
	assert(() => !!obj(), err)
}
