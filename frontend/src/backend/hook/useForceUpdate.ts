import { useState } from 'react'

export function useForceUpdate(): () => void {
    const [, setState] = useState(0)
    return () => setState((prev) => prev + 1)
}
