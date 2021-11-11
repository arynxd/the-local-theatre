import {BackendController} from "../BackendController";

export abstract class AbstractCache<K, V> extends Map<K, V> {
    constructor(protected backend: BackendController) {
        super()
    }

    public abstract fetch(key: K): Promise<V>

    public abstract cache(key: K, value: V): void
}