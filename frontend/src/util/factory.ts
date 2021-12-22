export const DEFAULT_PLACEHOLDER_COUNT = 10

export function createPlaceholders(fn: (index: number) => JSX.Element, count: number = DEFAULT_PLACEHOLDER_COUNT): JSX.Element[] {
    const out: JSX.Element[] = []
    for (let i = 0; i < count; i++) {
        out.push(fn(i))
    }
    return out
}