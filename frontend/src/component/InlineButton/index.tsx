import { StylableProps } from "../props/StylableProps";

interface InlineButtonProps extends StylableProps {
  onClick?: () => void
  children: string
}

export default function InlineButton(props: InlineButtonProps) {
  const styles = `
    bg-blue-800 text-blue-100 text-semibold p-1 px-4 shadow-xl rounded-xl
    ${props.className}
  `
  return (
    <button 
        onClick={props.onClick} 
        className={styles}>
        {props.children}
    </button>
  )
}