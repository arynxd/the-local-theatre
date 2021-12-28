import { MouseEvent, useState } from 'react'
import { StylableProps } from '../props/StylableProps'

interface ModalProps {
	provideMenu: () => JSX.Element
	onClickAway: () => void
	shouldShow: () => boolean
}

export default function Modal(props: ModalProps & StylableProps) {
	const handleModalBodyClick = (event: MouseEvent<HTMLDivElement>) => {
		event.preventDefault()
		event.stopPropagation()
	}

	const handleModalBackgroundClick = () => {
		props.onClickAway()
	}

	if (!props.shouldShow()) {
		return <> </>
	}

	const styles = `
        fixed w-full bg-opacity-40 bg-black h-full top-0 left-0 z-10
        ${props.className}
    `

	return (
		<>
			<div onClick={handleModalBackgroundClick} className={styles}></div>
			<div onClick={handleModalBodyClick}>{props.provideMenu()}</div>
		</>
	)
}
