import Separator from "../../component/Separator"
import {FormEvent, useState} from "react"
import {getAuth} from "../../backend/global-scope/util/getters";
import {Redirect} from "react-router";
import {Paths} from "../../util/paths";
import {ErrorElement, ErrorState, FormElement} from "../../component/FormElement";
import {logger} from "../../util/log";

type LoginState = 'logging_in' | 'validation_failed' | 'login_failed' | 'idle' | 'success'

export default function Login() {
    const [state, setState] = useState<LoginState>('idle')
    const [errors, setErrors] = useState<ErrorState>({})
    const [email, setEmail] = useState<string>()
    const [password, setPassword] = useState<string>()

    const handleSubmit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault()

        const newErrors: ErrorState = {
            email: [],
            password: []
        }

        let err = false

        if (!email) {
            newErrors.email?.push("No email provided")
            err = true
        }

        if (!password) {
            newErrors.password?.push("No password provided")
            err = true
        }

        setErrors(newErrors)

        if (err) {
            setState('validation_failed')
        }
        else {
            setState('logging_in')
        }
    }

    if (getAuth().isAuthenticated() || state === 'success') {
        return (
            <Redirect to={Paths.HOME}/>
        )
    }

    if (state === 'logging_in') {
        if (!email || !password) {
            throw new TypeError("Email or password was not set")
        }

        setTimeout(() => {
            getAuth().login(email, password)
            .then(isLoggedIn => {
                if (isLoggedIn) {
                    setState('success')
                }
                else {
                    setErrors({
                        general: ["Email or password incorrect"]
                    })
                    setState('login_failed')
                }
            })
            .catch((err) => {
                setErrors({
                    general: ["An error occurred whilst logging in, please try again"]
                })
                setState("login_failed")
                logger.error(err)
            })
        }, 5_000)
    }

    return (
        <div className='flex justify-center w-auto'>
            <div
                className='flex flex-col w-2/3 lg:w-3/5 items-center m-2 rounded-xl shadow-xl bg-gray-200 dark:bg-gray-500'>
                <h2 className='text-2xl text-center font-semibold pt-2 text-gray-900 dark:text-gray-200'>Login</h2>

                <Separator className='w-2/3'/>
                <form className='flex flex-col w-full items-center' onSubmit={handleSubmit}>
                    <FormElement onChange={setEmail} name='email' placeholder='Email' type='email'
                                 errors={errors}/>
                    <FormElement onChange={setPassword} name='password' placeholder='Password' type='password'
                                 errors={errors}/>

                    <ErrorElement error={errors.general}/>

                    <button className='p-2 m-2 w-10/12 inline-flex items-center justify-center text-gray-100 font-semibold text-md bg-blue-900 rounded-xl shadow-xl' type='submit'>
                        {state === 'logging_in'
                            ? <svg className="animate-spin mx-2 h-5 w-5 text-white"
                                   xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                <path className="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            : <> </>
                        }
                        Submit
                    </button>

                </form>
            </div>
        </div>
    )
}
