import { useState } from 'react'

export interface ReactiveCache<K, V> {
    set(key: K, value: V): ReactiveCache<K, V>
	get(key: K): V | undefined
    delete(key: K): boolean
	clone(): ReactiveCache<K, V>
    keys(): K[]
    values(): V[]
}

export class NoOpCache implements ReactiveCache<never, never> {
    set(_: never, __: never): ReactiveCache<never, never> {
        throw new Error('Method not implemented.')
    }
    get(_: never): never {
        throw new Error('Method not implemented.')
    }
    delete(_: never): boolean {
        throw new Error('Method not implemented.')
    }
    clone(): ReactiveCache<never, never> {
        throw new Error('Method not implemented.')
    }
    keys(): never[] {
        throw new Error('Method not implemented.')
    }
    values(): never[] {
        throw new Error('Method not implemented.')
    }
    
}
class ReactiveCacheImpl<K, V> implements ReactiveCache<K, V> {
	constructor(private readonly data: Map<K, V> = new Map()) {}

	set(key: K, value: V) {
		this.data.set(key, value)
        return this
	}

    keys() {
        return Array.from(this.data.keys())
    }

    values() {
        return Array.from(this.data.values())
    }

	get(key: K) {
		return this.data.get(key)
	}

    delete(key: K) {
        return this.data.delete(key)
    }

	clone() {
		return new ReactiveCacheImpl(this.data)
	}
}

export type CacheUpdateFunction = () => void

export function useReactiveCache<K, V>(): [
	ReactiveCache<K, V>,
	CacheUpdateFunction
] {
	const [cache, setCache] = useState(new ReactiveCacheImpl<K, V>())

	const apply: CacheUpdateFunction = () => {
		setCache(cache.clone())
	}

	return [cache, apply]
}
