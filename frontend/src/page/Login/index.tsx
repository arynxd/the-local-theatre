import Separator from "../../component/Separator"
import {FormEvent, useState} from "react"
import {getAuth} from "../../backend/global-scope/util/getters";
import {Redirect} from "react-router";
import {Paths} from "../../util/paths";
import {logger} from "../../util/log";
import {ErrorElement, ErrorState, FormElement} from "../../component/FormElement";

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
                    {state === 'logging_in'
                        ? <p>loading</p>
                        : <input
                            className='p-2 m-2 w-10/12 text-gray-100 font-semibold text-md bg-blue-900 rounded-xl shadow-xl'
                            type="submit" value="Submit"/>
                    }
                </form>
            </div>
        </div>
    )
}
