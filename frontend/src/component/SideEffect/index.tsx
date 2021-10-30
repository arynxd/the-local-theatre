export default function SideEffect(props: { fn: Function, value?: any }) {
    props.fn()
    console.log(props.value)
    return (
        <></>
    )
}