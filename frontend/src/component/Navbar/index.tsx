import React, {MouseEvent, useState} from "react";
import {Link} from "react-router-dom";
import logo from '../../assets/apple-touch-icon-76x76.png'
import {ParentProps} from "../props/ParentProps";
import dots from '../../assets/dots-menu.png'
import ThemeToggle from "../ThemeToggle";
import {BackendProps} from "../props/BackendProps";
import {StylableProps} from "../props/StylableProps";

interface Props {
    isOpen: boolean
}

interface ClickableProps extends Props {
    onClick: (event: MouseEvent<HTMLElement>) => void
}

function Logo(props: Props & StylableProps) {
    const styles = `
        ${props.isOpen ? 'hidden' : 'block'}
        w-12 h-12 
        
        ${props.className}
    `
    return (
        <Link className={styles} to="/~20006203/"><img src={logo} alt='The local theatre logo'/></Link>
    )
}

function MobileNavButton(props: ClickableProps) {
    const mobileHeaderStyles = `
        w-12 h-12 p-2
        md:hidden
        ${props.isOpen ? 'hidden' : 'block'}
    `

    return (
        <button className={mobileHeaderStyles} onClick={props.onClick}><img src={dots}
                                                                            alt='Menu to show navigation bar'/></button>
    )
}

function LinkList(props: ClickableProps & BackendProps) {
    const linkStyles = `
        z-rounded-xl text-sm font-semibold text-gray-300 hover:border-xl hover:bg-clip-content hover:bg-blue-600 
        text-center p-2 m-3 w-10/12 shadow-md dark:hover:bg-blue-900 dark:shadow-lg
        bg-blue-400 bg-blue-800 
        transition duration-150 ease-in-out
    `

    const closeStyles = `
        ${props.isOpen ? 'block' : 'hidden'}
        md:hidden
    ` + linkStyles

    const theme = `
        ${props.isOpen ? 'hidden' : 'block'}
        md:block
        h-10 w-10
    `

    const divStyles = `${props.isOpen ? 'hidden' : 'block'}`

    return (
        <>
            <Logo isOpen={props.isOpen} className='hidden md:block'/>
            <button className={closeStyles} onClick={props.onClick}>Close</button>

            <ThemeToggle className={theme} backend={props.backend}/>

            <div className={divStyles}/>
            <div className={divStyles}/>


            <Link className={linkStyles} to="/~20006203/">Home</Link>

            <Link className={linkStyles} to="/~20006203/blog">Blog</Link>

            <Link className={linkStyles} to="/~20006203/contact">Contact Us</Link>

            <Link className={linkStyles} to="/~20006203/login">Login</Link>

            <Link className={linkStyles} to="/~20006203/signup">Signup</Link>
        </>
    )
}

function MobileHeader(props: Props & ParentProps & ClickableProps) {
    const div = `
        bg-blue-900 w-full h-16 md:hidden grid grid-rows-1 grid-cols-7 items-center justify-center
        ${props.isOpen ? 'hidden' : 'block'}
    `
    return (
        <div className={div}>
            {props.children}
        </div>
    )
}

function HidingNav(props: Props & ParentProps) {
    const navStyles = `
        ${props.isOpen ? 'block' : 'hidden'}
        
        grid grid-cols-1 grid-rows-6
        
        bg-blue-900
        
        place-items-center
        
        md:grid md:grid-cols-9 md:grid-rows-1
    `

    return (
        <nav className={navStyles}>
            {props.children}
        </nav>
    )
}

function MobileThemeToggle(props: Props & BackendProps) {
    const mobileHeaderStyles = `
        w-14 h-14 p-2
        md:hidden
        ${props.isOpen ? 'hidden' : 'block'}
    `

    return (
        <ThemeToggle backend={props.backend} className={mobileHeaderStyles}/>
    )
}

export default function Navbar(props: BackendProps) {
    const [isOpen, setOpen] = useState<boolean>(false)

    const sideBarToggle = (_: MouseEvent<HTMLElement>): void => {
        setOpen(!isOpen)
    }

    return (
        <>
            <MobileHeader isOpen={isOpen} onClick={sideBarToggle}>
                <MobileNavButton isOpen={isOpen} onClick={sideBarToggle}/>
                <MobileThemeToggle backend={props.backend} isOpen={isOpen}/>
                <Logo isOpen={isOpen} className='col-start-4'/>
            </MobileHeader>

            <HidingNav isOpen={isOpen}>
                <LinkList backend={props.backend} onClick={sideBarToggle} isOpen={isOpen}/>
            </HidingNav>
        </>
    )
}

