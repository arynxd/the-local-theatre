import { Observable } from 'rxjs'
import { useEffect } from 'react'

export function useSubscription<T>(
    obs: Observable<T>,
    onChange: (newValue: T) => void
) {
    useEffect(() => {
        const sub = obs.subscribe(onChange)

        return () => sub.unsubscribe()
    }, [obs, onChange])
}
