import {Observable} from "rxjs";
import {useEffect} from "react";

export function useSubscription<T>(obs: Observable<T>, callback: (newValue: T) => void) {
    useEffect(() => {
        const sub = obs.subscribe(callback)

        return () => sub.unsubscribe()
    }, [obs, callback])
}