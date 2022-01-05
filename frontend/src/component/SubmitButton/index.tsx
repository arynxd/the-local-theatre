import { MouseEvent } from 'react'
import { StylableProps } from '../props/StylableProps'

export type SubmitState = 'waiting' | 'submitting' | 'error'

interface SubmitButtonProps<T> {
	onSubmit: () => Promise<T>
	onSuccess: (result: T) => void
	onError: (error: any) => void
	shouldDisplayLoading: () => boolean
	onClick?: (event: MouseEvent<HTMLButtonElement>) => void
}

export default function SubmitButton<T>(props: SubmitButtonProps<T> & StylableProps) {
	const handleClick = (event: MouseEvent<HTMLButtonElement>) => {
		props.onClick?.(event)
		props
			.onSubmit()
			.then((v) => {
				props.onSuccess(v)
			})
			.catch((e) => {
				props.onError(e)
			})
	}

	const styles = `
		p-2 m-2 inline-flex items-center justify-center 
		text-gray-100 font-semibold text-md bg-blue-900 rounded-xl shadow-xl
		${props.className}
	`

	return (
		<button
			className={styles}
			type="submit"
			onClick={handleClick}
		>
			{props.shouldDisplayLoading() ? (
				<svg
					className="animate-spin mx-2 h-5 w-5 text-white"
					xmlns="http://www.w3.org/2000/svg"
					fill="none"
					viewBox="0 0 24 24"
				>
					<circle
						className="opacity-25"
						cx="12"
						cy="12"
						r="10"
						stroke="currentColor"
						strokeWidth="4"
					/>
					<path
						className="opacity-75"
						fill="currentColor"
						d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
					/>
				</svg>
			) : (
				<> </>
			)}
			Submit
		</button>
	)
}
