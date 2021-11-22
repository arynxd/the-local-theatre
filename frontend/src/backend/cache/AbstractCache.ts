import {EntityIdentifier} from "../../model/EntityIdentifier";

/**
 * An extension of the inbuilt Map, which adds extra functionality
 */
export abstract class AbstractCache<V> extends Map<EntityIdentifier, V> {
    public abstract fetch(key: EntityIdentifier): Promise<V>

    public abstract cache(key: EntityIdentifier, value: V): void
}