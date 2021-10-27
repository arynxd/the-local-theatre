export type Device = 'sm' | 'md' | 'lg'

export function getDevice(): Device {
    const width = window.innerWidth

    if (width <= 640) {
        return 'sm'
    }
    else if (width <= 768) {
        return 'md'
    }
    return 'lg'
}