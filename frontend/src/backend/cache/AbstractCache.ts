import {EntityIdentifier} from "../../model/EntityIdentifier";

/**
 * An extension of the inbuilt Map, which adds extra functionality
 */
export abstract class AbstractCache<V> extends Map<EntityIdentifier, V> {
    constructor() {
        super()
        // Set the prototype explicitly.
        // https://github.com/Microsoft/TypeScript-wiki/blob/main/Breaking-Changes.md#extending-built-ins-like-error-array-and-map-may-no-longer-work
        Object.setPrototypeOf(this, AbstractCache.prototype);
    }

    public abstract fetch(key: EntityIdentifier): Promise<V>

    public abstract cache(key: EntityIdentifier, value: V): void
}