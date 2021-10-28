import React, {MouseEvent, useState} from "react";
import {Link} from "react-router-dom";
import logo from '../../assets/apple-touch-icon-76x76.png'
import {ParentProps} from "../props/ParentProps";
import dots from '../../assets/dots-menu.png'
import ThemeToggle from "../ThemeToggle";

interface Props {
    isOpen: boolean
}

interface ClickableProps extends Props {
    onClick: (event: MouseEvent<HTMLElement>) => void
}

function Logo(props: Props) {
    const styles = `
        ${props.isOpen ? 'hidden' : 'block'}
        
        md:justify-start hidden md:block md:col-start-1 w-20 h-20
    `
    return (
        <Link className={styles} to="/"><img src={logo} alt='The local theatre logo'/></Link>
    )
}

function MobileNavButton(props: ClickableProps) {
    const mobileHeaderStyles = `
        w-10 h-10 p-2
        md:hidden
        ${props.isOpen ? 'hidden' : 'block'}
    `

    return (
        <button className={mobileHeaderStyles} onClick={props.onClick}><img src={dots} alt='Menu to show navigation bar'/></button>
    )
}

function LinkList(props: ClickableProps) {
    const linkStyles= `
        z-rounded-xl text-xl text-gray-900 dark:text-gray-300 hover:border-xl hover:bg-clip-content hover:bg-blue-600 
        text-center p-2 m-4 w-auto shadow-md dark:hover:bg-blue-900 dark:shadow-lg
        bg-blue-500 dark:bg-blue-800 
        transition duration-150 ease-in-out
    `

    const closeStyles = `
        ${props.isOpen ? 'block' : 'hidden'}
        md:hidden
    ` + linkStyles

    const theme = `
        ${props.isOpen ? 'hidden' : 'block'}
        md:block
        w-20 h-20
    `

    return (
        <>
            <Logo isOpen={props.isOpen}/>
            <button className={closeStyles} onClick={props.onClick}>Close</button>

            <ThemeToggle className={theme}/>


            <Link className={linkStyles} to="/">Home</Link>

            <Link className={linkStyles} to="/blog">Blog</Link>

            <Link className={linkStyles} to="/contact">Contact Us</Link>

            <Link className={linkStyles} to="/login">Login</Link>

            <Link className={linkStyles} to="/signup">Signup</Link>
        </>
    )
}

function MobileHeader(props: Props & ParentProps & ClickableProps) {
    const div = `
        bg-blue-400 dark:bg-blue-900 w-full h-10 md:hidden grid grid-rows-1 grid-cols-10 items-center justify-center
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

function MobileThemeToggle(props: Props) {
    const mobileHeaderStyles = `
        w-10 h-10 p-2
        md:hidden
        ${props.isOpen ? 'hidden' : 'block'}
    `

    return (
        <ThemeToggle className={mobileHeaderStyles}/>
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
                <MobileThemeToggle isOpen={isOpen} />
            </MobileHeader>

            <HidingNav isOpen={isOpen}>
                <LinkList onClick={sideBarToggle} isOpen={isOpen} />
            </HidingNav>
        </>
    )
}

