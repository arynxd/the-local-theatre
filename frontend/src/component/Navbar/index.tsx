import React, {MouseEvent, useState} from "react";
import {Link} from "react-router-dom";
import logo from '../../assets/apple-touch-icon-76x76.png'
import {ParentProps} from "../props/ParentProps";
import dots from '../../assets/dots-menu.png'

interface Props {
    isOpen: boolean
}

interface ClickableProps extends Props {
    onClick: (event: MouseEvent<HTMLElement>) => void
}

function Logo(props: ClickableProps) {
    const styles = `
        ${props.isOpen ? 'hidden' : 'block'}
        md:justify-start hidden md:block md:col-start-1
    `
    return (
        <Link className={styles} to="/"><img onClick={props.onClick} src={logo} alt='The local theatre logo'/></Link>
    )
}

function MobileNavButton(props: ClickableProps) {
    if (props.isOpen) {
        return (
             <button onClick={props.onClick} className='hidden'>NAV</button>
        )
    }
    return (
        <button onClick={props.onClick} className='justify-start block md:hidden'>NAV</button>
    )
}

function LinkList(props: ClickableProps) {
    const linkStyles= `
        z-rounded-xl text-xl text-gray-900 dark:text-gray-300 hover:border-xl hover:bg-clip-content hover:bg-blue-600 
        text-center p-2 m-4 w-auto shadow-md dark:hover:bg-blue-900 dark:shadow-lg
        bg-blue-500 dark:bg-blue-800 
        transition duration-150 ease-in-out
    `

    const div = `${props.isOpen ? 'hidden' : 'block'}`

    const closeStyles = `
        ${props.isOpen ? 'block' : 'hidden'}
    ` + linkStyles

    return (
        <>
            <Logo isOpen={props.isOpen} onClick={props.onClick}/>

            <div className={div}/>

            <button className={closeStyles} onClick={props.onClick}>Close</button>

            <Link className={linkStyles} to="/">Home</Link>

            <Link className={linkStyles} to="/blog">Blog</Link>

            <Link className={linkStyles} to="/contact">Contact Us</Link>

            <Link className={linkStyles} to="/login">Login</Link>

            <Link className={linkStyles} to="/signup">Signup</Link>
        </>
    )
}

function MobileHeader(props: Props & ParentProps & ClickableProps) {
     // old style ${props.isOpen ? 'translate-y-0' : '-translate-y-1vh'}
     // old style transform transition duration-700 ease-in-out

    const styles = `
        w-10 h-10 p-2
    `

    return (
        <div className='bg-blue-400 dark:bg-blue-900 w-full h-10 md:hidden'>
            <button className={styles} onClick={props.onClick}><img src={dots} alt='Menu to show navigation bar'/></button>
        </div>
    )
}

function HidingNav(props: Props & ParentProps) {
    //TODO get this to switch to the right nav for the device
    //TODO get rid of the whitespace between nav and post on mobile

    // old style ${props.isOpen ? 'translate-y-0' : '-translate-y-1vh'}
    // old style transform transition duration-700 ease-in-out
    const navStyles = `
        ${props.isOpen ? 'block' : 'hidden'}
        
        grid grid-cols-1 grid-rows-5 
        
        bg-blue-400 dark:bg-blue-900
        
        items-center justify-center 
        
        md:grid md:grid-cols-7 md:grid-rows-1
    `

    return (
        <nav className={navStyles}>
          {props.children}
        </nav>
    )
}

export default function Navbar() {
    const [isOpen, setOpen] = useState<boolean>(false)

    const sideBarToggle = (_: MouseEvent<HTMLElement>): void => {
        setOpen(!isOpen)
    }

    return (
        <>
            <MobileHeader isOpen={isOpen} onClick={sideBarToggle}>
                <MobileNavButton isOpen={isOpen} onClick={sideBarToggle} />
            </MobileHeader>

            <HidingNav isOpen={isOpen}>
                <LinkList onClick={sideBarToggle} isOpen={isOpen} />
            </HidingNav>
        </>
    )
}

