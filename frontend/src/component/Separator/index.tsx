import React from 'react'
import { StylableProps } from '../props/StylableProps'

export default function Separator(props: StylableProps) {
    const styles = `
         h-0.5 block border-0 border-t-2 border-gray-600
         dark:border-gray-400
        ${props.className}
    `
    return <hr className={styles} />
}
