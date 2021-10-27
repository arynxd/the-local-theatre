import React, {MouseEvent, useState} from "react";
import {Link} from "react-router-dom";
import logo from '../../assets/apple-touch-icon-76x76.png'
import {Device, getDevice} from "../../util/css";

const LinkStyle = `
    z-rounded-xl text-xl text-gray-800 dark:text-gray-300 hover:border-xl hover:bg-clip-content hover:bg-blue-500 
    text-center p-2 m-4 w-auto shadow-md dark:hover:bg-blue-800 dark:shadow-lg
 
    transition duration-150 ease-in-out z-20
`

interface Props {
    isOpen: boolean,
    device: Device
}

interface ClickableProps extends Props {
    onClick: (event: MouseEvent<HTMLElement>) => void
}

interface NavProps extends Props {
    children: JSX.Element
}

function Logo(props: ClickableProps) {
    return (
        <Link className='md:justify-start hidden md:block md:col-start-1 z-10' to="/"><img onClick={props.onClick} src={logo} alt='The local theatre logo'/></Link>
    )
}

function MobileNavButton(props: ClickableProps) {
    if (props.isOpen) {
        return (
             <button onClick={props.onClick} className='hidden'>NAV</button>
        )
    }
    return (
        <button onClick={props.onClick} className='justify-start z-10 block md:hidden'>NAV</button>
    )
}

function LinkList(props: ClickableProps) {
    const closeStyles = `
        ${props.isOpen ? 'block' : 'hidden'}
    ` + LinkStyle
    return (
        <>
            <Logo device={props.device} isOpen={props.isOpen} onClick={props.onClick}/>

            <div/>

            <button className={closeStyles} onClick={props.onClick}>Close</button>

            <Link className={LinkStyle} to="/">Home</Link>

            <Link className={LinkStyle} to="/blog">Blog</Link>

            <Link className={LinkStyle} to="/contact">Contact Us</Link>

            <Link className={LinkStyle} to="/login">Login</Link>

            <Link className={LinkStyle} to="/signup">Signup</Link>
        </>
    )
}

function HidingNav(props: NavProps) {
    const styles = `
        ${props.isOpen ? 'grid' : 'hidden'}
        grid-cols-1 grid-rows-5 md:grid md:grid-cols-7 md:grid-rows-1
        bg-blue-400 
        items-center justify-center dark:bg-blue-900 z-20
    `

    return (
        // apparently nav doesnt work with a z-index so we have this hack
        <div className='grid z-20'>
            <nav className={styles}>
              {props.children}
            </nav>
       </div>

    )
}

function MobileBackdrop(props: ClickableProps) {
    const styles = `
        z-0 bg-blue-100 w-full h-full fixed top-0
        ${props.isOpen ? 'opacity-20 visible' : 'opacity-0 hidden'}
        md:hidden
    `
    return (
        <div className={styles} onClick={props.onClick}/>
    )
}

export default function Navbar() {
    const [isOpen, setOpen] = useState<boolean>(false)

    const sideBarToggle = (_: MouseEvent<HTMLElement>): void => {
        setOpen(!isOpen)
    }

    const device = getDevice()

    return (
        <>
            <MobileNavButton device={device} isOpen={isOpen} onClick={sideBarToggle} />
            <MobileBackdrop onClick={sideBarToggle} isOpen={isOpen} device={device}/>

            <HidingNav device={device} isOpen={isOpen}>
                <LinkList device={device} onClick={sideBarToggle} isOpen={isOpen} />
            </HidingNav>


        </>
    )
}

