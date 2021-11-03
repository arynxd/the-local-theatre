/**
 * A custom error representing an error state within the backend
 * All rejected backend requests will reject with this value
 */
export default class BackendError extends Error {
    constructor(msg: string) {
        super(msg);

        // Set the prototype explicitly.
        // https://github.com/Microsoft/TypeScript-wiki/blob/main/Breaking-Changes.md#extending-built-ins-like-error-array-and-map-may-no-longer-work
        Object.setPrototypeOf(this, BackendError.prototype);
    }
}
