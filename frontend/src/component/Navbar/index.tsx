
import React, {MouseEvent, useState} from "react";
import {Link} from "react-router-dom";

const LinkStyle = `
    
`

function Navbar() {
    const [isOpen, setOpen] = useState<boolean>(false)

    const sideBarToggle = (_: MouseEvent<HTMLButtonElement | HTMLDivElement>): void => {
        setOpen(!isOpen)
    }

    return (
        <>
            <h1 className="lg:text-7xl md:text-5xl sm:text-3xl text-center dark:text-gray-300">The Local Theatre</h1>
            <button className='lg:hidden md:hidden sm:visible' onClick={sideBarToggle}>☰</button>
            <div onClick={sideBarToggle}/>

            <nav>
                <ul className='grid grid-cols-5 gap-4 text-center lg:text-2xl md:text-xl sm:grid-cols-1 sm:grid-rows-5 sm:top-0 sm:fixed sm:pt-52 sm:gap-20'>
                    <Link to="/">Home</Link>

                    <Link to="/blog">Blog</Link>

                    <Link to="/contact">Contact Us</Link>

                    <Link to="/login">Login</Link>

                    <Link to="/signup">Signup</Link>
                </ul>
            </nav>
        </>
    )
}

export default Navbar;