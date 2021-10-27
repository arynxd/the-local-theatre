
import React, {MouseEvent, useState} from "react";
import {Link} from "react-router-dom";
import logo from '../../assets/apple-touch-icon-76x76.png'

const LinkStyle = `
    rounded-xl text-xl text-gray-800 dark:text-gray-300 hover:border-xl hover:bg-clip-content hover:bg-blue-500 
    text-center p-2 m-4 shadow-md dark:hover:bg-blue-800 dark:shadow-lg
 
    transition duration-150 ease-in-out
`

function LogoOrMobileNav() {
    return (
        <Link to="/"><img src={logo} alt='The local theatre logo' className='justify-start'/></Link>
    )
}

export default function Navbar() {
    const [isOpen, setOpen] = useState<boolean>(false)

    const sideBarToggle = (_: MouseEvent<HTMLButtonElement | HTMLDivElement>): void => {
        setOpen(!isOpen)
    }

    return (
        <>

            <div onClick={sideBarToggle}/>

            <nav className='grid grid-cols-7 bg-blue-400 items-center justify-center
                    dark:bg-blue-900
                '>
                {/*<h1 className="lg:text-6xl md:text-4xl sm:text-3xl text-center dark:text-gray-300 col-span-2">The Local Theatre</h1>*/}

                <LogoOrMobileNav />

                <div />

                <Link className={LinkStyle} to="/">Home</Link>

                <Link className={LinkStyle} to="/blog">Blog</Link>

                <Link className={LinkStyle} to="/contact">Contact Us</Link>

                <Link className={LinkStyle} to="/login">Login</Link>

                <Link className={LinkStyle} to="/signup">Signup</Link>
            </nav>
        </>
    )
}

