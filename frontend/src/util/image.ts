export function toURL(blob: Blob): string {
    return URL.createObjectURL(blob)
}