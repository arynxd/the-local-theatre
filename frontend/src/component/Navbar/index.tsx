import { useCallback, useState } from 'react'
import { Link } from 'react-router-dom'
import logo from '../../assets/ico76x76.png'
import logoWithText from '../../assets/LogoWithTextNoBG.png'
import { Paths } from '../../util/paths'
import { getAuth } from '../../backend/global-scope/util/getters'
import { useSubscription } from '../../backend/hook/useSubscription'
import Separator from '../Separator'
import { useSelfUser } from '../../backend/hook/useSelfUser'
import Avatar from '../Avatar'
import { hasPermission } from '../../model/Permission'
import ThemeToggle from '../ThemeToggle'
import { Hamburger, Close } from '../Icons'

function ProfileMenu() {
	const [isOpen, setOpen] = useState(false)

	const selfUser = useSelfUser()

	if (!selfUser) {
		return <></>
	}

	const imageStyles = `
        origin-top-right right-0 mt-2 w-48 rounded-md shadow-xl py-1 bg-gray-100 dark:bg-gray-600 focus:outline-none
        ${isOpen ? 'absolute' : 'hidden'} 
   `

	return (
		<>
			<button
				onClick={() => setOpen(!isOpen)}
				className="bg-gray-800 flex text-sm rounded-full focus:outline-none"
			>
				<span className="sr-only">Open user menu</span>
				<Avatar className="h-10 w-10 rounded-full" user={selfUser} />
			</button>

			<div className={imageStyles}>
				<div className="grid grid-cols-1 gap-3 p-4 pt-2 place-items-center w-auto h-full">
					<h2 className="font-semibold text-xl dark:text-gray-200">
						Profile ({selfUser.firstName} {selfUser.lastName})
					</h2>
					<Separator className="w-2/3" />

					<div className="flex flex-row items-center">
						<h2 className="p-2 dark:text-gray-200">
							Toggle theme:{' '}
						</h2>
						<ThemeToggle className="h-8 w-8" />
					</div>
					<Separator className="w-6/12" />

					<Link
						className="dark:text-gray-200"
						onClick={() => setOpen(false)}
						to={Paths.USER_SETTINGS}
					>
						Edit your details
					</Link>
					<Separator className="w-1/3" />

					<Link
						to={Paths.HOME}
						className="dark:text-gray-200"
						onClick={() => {
							getAuth().logout()
							setOpen(false)
						}}
					>
						Sign out
					</Link>
				</div>
			</div>
		</>
	)
}

export default function Navbar() {
	const auth$$ = getAuth().observeAuth$$

	const [authState, setAuthState] = useState(auth$$.value)
	const [isMobileOpen, setMobileOpen] = useState(false)
	const selfUser = useSelfUser()

	useSubscription(
		auth$$,
		useCallback((newAuth) => setAuthState(newAuth), [])
	)

	const hamburgerCloseStyles = `
        fill-white
        ${isMobileOpen ? 'hidden' : 'block'} h-6 w-6
    `

	const hamburgerOpenStyles = `
        fill-white
        ${isMobileOpen ? 'block' : 'hidden'} h-6 w-6
    `

	const mobileNavMenuStyles = `
        sm:hidden flex flex-col z-30 ${isMobileOpen ? 'block' : 'hidden'}
    `

	const mobileBackdropStyles = `
        absolute top-0 w-screen h-screen bg-gray-500 opacity-60 md:hidden z-20 ${
			isMobileOpen ? 'block' : 'hidden'
		}
    `

	const DesktopStyledLink = (props: { text: string; path: string }) => {
		const styles =
			'bg-blue-700 text-white hover:bg-blue-600 hover:text-white px-3 py-2 rounded-md text-sm font-medium select-none'
		return (
			<Link className={styles} to={props.path}>
				{props.text}
			</Link>
		)
	}

	const MobileStyledLink = (props: { text: string; path: string }) => {
		const styles =
			'bg-blue-700 block text-white hover:bg-blue-600 hover:text-white px-3 py-2 rounded-md text-sm font-medium select-none'
		return (
			<Link
				className={styles}
				onClick={() => setMobileOpen(false)}
				to={props.path}
			>
				{props.text}
			</Link>
		)
	}

	// Credit:
	// https://tailwindui.com/components/application-ui/navigation/navbars

	return (
		<>
			<div
				onClick={() => setMobileOpen(!isMobileOpen)}
				className={mobileBackdropStyles}
			/>
			<nav className="bg-blue-800 relative z-30">
				<div className="px-2 sm:px-6 lg:px-8">
					<div className="relative flex items-center justify-between h-16">
						<div className="absolute z-40 inset-y-0 left-0 flex items-center sm:hidden">
							{/* Hamburger menu, only appears on mobile. */}
							<button
								onClick={() => setMobileOpen(!isMobileOpen)}
								className="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-blue-600 focus:outline-none"
							>
								{/* Open icon (Hamburger) */}
								<Hamburger className={hamburgerCloseStyles} />

								{/* Close icon (X) */}
								<Close className={hamburgerOpenStyles} />
							</button>
						</div>

						{/* Desktop nav menu */}
						<div className="flex flex-1 items-center justify-center md:justify-start bg-blue-800">
							<div className="flex-shrink-0 flex items-center">
								{/* Smaller image, mobile */}
								<img
									className="block lg:hidden h-12 w-auto"
									src={logo}
									alt="Workflow"
								/>

								{/* Larger image, tablet+ */}
								<img
									className="hidden lg:block h-12 w-auto"
									src={logoWithText}
									alt="Workflow"
								/>
							</div>
							<div className="hidden sm:block sm:ml-6">
								<div className="flex space-x-4">
									<DesktopStyledLink
										text="Home"
										path={Paths.HOME}
									/>
									<DesktopStyledLink
										text="Blog"
										path={Paths.BLOG}
									/>
									<DesktopStyledLink
										text="Contact Us"
										path={Paths.CONTACT}
									/>
									{authState !== 'authenticated' ? (
										<>
											<DesktopStyledLink
												text="Login"
												path={Paths.LOGIN}
											/>
											<DesktopStyledLink
												text="Sign up"
												path={Paths.SIGNUP}
											/>
										</>
									) : (
										<>
											{selfUser &&
											hasPermission(
												selfUser.permissions,
												'moderator'
											) ? (
												<>
													<DesktopStyledLink
														text="Moderation"
														path={Paths.MODERATION}
													/>
												</>
											) : (
												<></>
											)}
										</>
									)}
								</div>
							</div>
						</div>
						<div className="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
							<div className="ml-3 relative">
								{authState === 'authenticated' ? (
									<ProfileMenu />
								) : (
									<></>
								)}
							</div>
						</div>
					</div>
				</div>
				{/* _Mobile nav menu */}
				<div className={mobileNavMenuStyles}>
					<div className="px-2 pt-2 pb-3 space-y-1">
						<MobileStyledLink text="Home" path={Paths.HOME} />
						<MobileStyledLink text="Blog" path={Paths.BLOG} />
						<MobileStyledLink
							text="Contact Us"
							path={Paths.CONTACT}
						/>

						{authState !== 'authenticated' ? (
							<>
								<MobileStyledLink
									text="Login"
									path={Paths.LOGIN}
								/>
								<MobileStyledLink
									text="Sign up"
									path={Paths.SIGNUP}
								/>
							</>
						) : (
							<>
								{selfUser &&
								hasPermission(
									selfUser.permissions,
									'moderator'
								) ? (
									<>
										<MobileStyledLink
											text="Moderation"
											path={Paths.MODERATION}
										/>
									</>
								) : (
									<></>
								)}
							</>
						)}
					</div>
				</div>
			</nav>
		</>
	)
}
