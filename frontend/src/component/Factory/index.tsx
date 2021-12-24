import { StylableProps } from '../props/StylableProps'

interface TextProps {
    children: string
}

export function WarningIcon(props: StylableProps) {
    const styles = `
    w-8 h-8 fill-yellow-500
    ${props.className}
  `
    return (
        <svg
            className={styles}
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                fillRule="evenodd"
                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                clipRule="evenodd"
            />
        </svg>
    )
}
export function Warning(props: TextProps) {
    return (
        <>
            <WarningIcon />
            <h2 className="text-lg text-semibold dark:text-gray-100">
                {props.children}
            </h2>
        </>
    )
}

export function Error(props: TextProps & StylableProps) {
    const styles = `
    flex flex-col items-center bg-gray-200 dark:bg-red-900 rounded p-2 m-2 shadow-xl
    ${props.className}
  `
    return (
        <div className={styles}>
            <svg
                className="w-8 h-8 fill-red-500"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    fillRule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                    clipRule="evenodd"
                />
            </svg>
            <h2 className="text-lg text-semibold dark:text-gray-100 dark:bg-red-900">
                {props.children}
            </h2>
        </div>
    )
}
