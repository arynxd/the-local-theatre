import { useState } from "react";

export class StatefulCache<K, V> extends Map<K, V> {
  constructor() {
    super()
    // Set the prototype explicitly.
    // https://github.com/Microsoft/TypeScript-wiki/blob/main/Breaking-Changes.md#extending-built-ins-like-error-array-and-map-may-no-longer-work
    Object.setPrototypeOf(this, StatefulCache.prototype);
  }

  public setAll(elements: [K, V][]) {
    for (const el of elements) {
        this.set(el[0], el[1])
    }
  }
  
  public valueArray(): V[] {
    return Array.from(this.values())
  }
}


type ChangeFunction<K, V> = (change: (cache: StatefulCache<K, V>) => void) => void

export function useStatefulCache <K, V>(): [StatefulCache<K, V>, ChangeFunction<K, V>] {
  const [cache, updateCache] = useState(new StatefulCache<K, V>())

  const changeFunction: ChangeFunction<K, V> = (apply) => {
    apply(cache)
    updateCache(cache)
  }

  return [cache, changeFunction]
}