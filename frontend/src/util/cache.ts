import { useState } from "react";

export class StatefulCache<K, V> extends Map<K, V> {
  constructor(initialValues: [K, V][] = []) {
    super()
    // Set the prototype explicitly.
    // https://github.com/Microsoft/TypeScript-wiki/blob/main/Breaking-Changes.md#extending-built-ins-like-error-array-and-map-may-no-longer-work
    Object.setPrototypeOf(this, StatefulCache.prototype);
    this.setAll(initialValues)
  }

  public setAll(elements: [K, V][]) {
    for (const el of elements) {
        this.set(el[0], el[1])
    }
  }
  
  public valueArray(): V[] {
    return Array.from(this.values())
  }
  
  public entryArray(): [K, V][] {
    return Array.from(this.entries())
  }
}

type ChangeFunction<K, V> = (change: (cache: StatefulCache<K, V>) => void) => void

export function useStatefulCache <K, V>(): [StatefulCache<K, V>, ChangeFunction<K, V>] {
  const [cache, updateCache] = useState(new StatefulCache<K, V>())

  const changeFunction: ChangeFunction<K, V> = (apply) => {
    const newCache = new StatefulCache<K, V>(cache.entryArray())
    apply(newCache)
    updateCache(newCache)
  }

  return [cache, changeFunction]
}