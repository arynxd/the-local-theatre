import React, {useState} from "react";
import {Link} from "react-router-dom";
import logo from '../../assets/apple-touch-icon-76x76.png'
import logoWithText from '../../assets/LogoWithTextNoBG.png'
import {Paths} from "../../util/paths";

export default function Navbar() {
    //TODO: get the animation to work when the navbar comes down on mobile
    //TODO: auto close the navbar when a link is selected
    //TODO: add stateful profile logic to the right hand side of the navbar 
    
    const [isMobileOpen, setMobileOpen] = useState(false)

    const hamburgerCloseStyles = `
        ${isMobileOpen ? 'hidden' : 'block'} h-6 w-6
    `

    const hamburgerOpenStyles = `
        ${isMobileOpen ? 'block' : 'hidden'} h-6 w-6
    `

    const mobileNavMenuStyles = `
        sm:hidden flex flex-col z-30 ${isMobileOpen ? 'block' : 'hidden'}
    `

    const mobileBackdropStyles = `
        absolute top-0 w-screen h-screen bg-gray-500 opacity-60 md:hidden z-20 ${isMobileOpen ? 'block' : 'hidden'}
    `

    const DesktopStyledLink = (props: { text: string, path: string }) => {
        const styles = 'bg-blue-700 text-white hover:bg-blue-600 hover:text-white px-3 py-2 rounded-md text-sm font-medium'
        return (
            <Link className={styles} to={props.path}>{props.text}</Link>
        )
    }

    const MobileStyledLink = (props: { text: string, path: string }) => {
        const styles = 'bg-blue-700 block text-white hover:bg-blue-600 hover:text-white px-3 py-2 rounded-md text-sm font-medium'
        return (
            <Link className={styles} to={props.path}>{props.text}</Link>
        )
    }

    // Credit:
    // https://tailwindui.com/components/application-ui/navigation/navbars

    return (
        <>
            <div onClick={() => setMobileOpen(!isMobileOpen)} className={mobileBackdropStyles}/>
            <ul className='bg-blue-800 relative z-30'>
                <div className='px-2 sm:px-6 lg:px-8'>
                    <div className='relative flex items-center justify-between h-16'>
                        <div className='absolute z-40 inset-y-0 left-0 flex items-center sm:hidden'>
                            {/* Hamburger menu, only appears on mobile. */}
                            <button onClick={() => setMobileOpen(!isMobileOpen)}
                                    className="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-blue-600 focus:outline-none">
                                {/* Close icon (X) */}
                                <svg className={hamburgerCloseStyles} fill="none" viewBox="0 0 24 24" stroke="#FFFFFF">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                          d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>

                                {/* Open icon (Hamburger) */}
                                <svg className={hamburgerOpenStyles} fill="none" viewBox="0 0 24 24" stroke="#FFFFFF">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                          d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        {/* Desktop nav menu */}
                        <div className="flex flex-1 items-center justify-center md:justify-start bg-blue-800">
                            <div className="flex-shrink-0 flex items-center">
                                {/* Smaller image, mobile */}
                                <img className="block lg:hidden h-12 w-auto"
                                     src={logo} alt="Workflow"/>

                                {/* Larger image, tablet+ */}
                                <img className="hidden lg:block h-12 w-auto"
                                     src={logoWithText}
                                     alt="Workflow"/>

                            </div>
                            <div className="hidden sm:block sm:ml-6">
                                <div className="flex space-x-4">
                                    <DesktopStyledLink text='Home' path={Paths.HOME}/>
                                    <DesktopStyledLink text='Blog' path={Paths.BLOG}/>
                                    <DesktopStyledLink text='Contact Us' path={Paths.CONTACT}/>
                                    <DesktopStyledLink text='Login' path={Paths.LOGIN}/>
                                    <DesktopStyledLink text='Sign up' path={Paths.SIGNUP}/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {/* _Mobile nav menu */}
                <div className={mobileNavMenuStyles}>
                    <div className="px-2 pt-2 pb-3 space-y-1">
                        <MobileStyledLink text='Home' path={Paths.HOME}/>
                        <MobileStyledLink text='Blog' path={Paths.BLOG}/>
                        <MobileStyledLink text='Contact Us' path={Paths.CONTACT}/>
                        <MobileStyledLink text='Login' path={Paths.LOGIN}/>
                        <MobileStyledLink text='Sign up' path={Paths.SIGNUP}/>
                    </div>
                </div>
            </ul>
        </>
    )
}

