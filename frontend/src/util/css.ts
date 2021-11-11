export type Device = 'sm' | 'md' | 'lg'

/**
 * Determines the size of the current device, using tailwindcss units
 * 
 * @returns The device currently in use
 */
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