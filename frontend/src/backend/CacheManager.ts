export default class CacheManager<K, V> {
    private readonly cache = new Map<K, V>()

    get(key: K): V | undefined {
        return this.cache.get(key)
    }

    set(key: K, value: V) {
        this.cache.set(key, value)
    }
}