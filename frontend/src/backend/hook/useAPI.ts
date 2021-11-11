import {useEffect, useState} from "react";

export function useAPI<T>(action: Promise<T>): T | undefined {
    const [res, setRes] = useState<T>()
    useEffect(() => {
        action.then(setRes)
    }, [action])
    return res
}
