import { ChangeEvent, HTMLInputTypeAttribute, useState, KeyboardEvent } from 'react'

interface ErrorProps {
	errors: ErrorState
}

interface FormElementProps {
	onChange: (data: string) => void
	name: string
	placeholder: string
	type: HTMLInputTypeAttribute
	initialState?: string
	onKeyPress?: (event: KeyboardEvent<HTMLInputElement>) => void
}

export type ErrorState = {
	[key: string]: string[] | undefined
}

export function ErrorElement(props: { error?: string[] }) {
	if (!props.error) {
		return <></>
	}
	return (
		<>
			{props.error.map((err) => {
				return (
					<span className="bg-gray-300 dark:bg-gray-600 dark:text-red-200 text-red-800 p-2 m-1 rounded shadow-xl">
						{err}
					</span>
				)
			})}
		</>
	)
}

export function FormElement(props: FormElementProps & ErrorProps) {
	const [state, setState] = useState<string | undefined>(props.initialState)

	const handleChange = (event: ChangeEvent<HTMLInputElement>) => {
		props.onChange(event.target.value)
		setState(event.target.value)
	}

	const error = props.errors[props.name]
	const hasError = error && error.length > 0

	const styles = `
        p-3 m-2 w-10/12 rounded-xl shadow-xl bg-gray-100 dark:bg-gray-500
        placeholder-opacity-60  placeholder-gray-800 dark:placeholder-gray-400 
        transition duration-300 ease-in-out transform focus:-translate-y-1 
       
        ${
			hasError
				? 'focus:scale-110 focus:outline-none ring-red-900 ring-2'
				: 'ring-0 focus:scale-110 focus:outline-none focus:ring-blue-900 focus:ring-2'
		}
        
        focus:placeholder-gray-900 dark:focus:placeholder-gray-100 
        text-gray-800 dark:text-gray-100
    `

	return (
		<>
			<input
				className={styles}
				placeholder={props.placeholder}
				type={props.type}
				value={state}
				onChange={handleChange}
				onKeyDownCapture={props.onKeyPress}
			/>

			<div className="flex flex-row">
				{hasError ? <ErrorElement error={error} /> : <></>}
			</div>
		</>
	)
}
