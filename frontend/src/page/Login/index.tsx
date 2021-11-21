import Separator from "../../component/Separator"
import {ChangeEvent, FormEvent, HTMLInputTypeAttribute, useState} from "react"
import {getAuth} from "../../backend/global-scope/util/getters";

interface ErrorProps {
    errors: ErrorState
}

interface FormElementProps {
    onChange: (data: string) => void
    name: string
    placeholder: string
    type: HTMLInputTypeAttribute

}

type ErrorState = {
    [key: string]: string[] | undefined
}

type LoginState = 'logging_in' | 'validation_failed' | 'login_failed' | 'idle' | 'logged_in'

const PASSWORD_VALIDATION_REGEX = /(?=^.{8,}$)(?=.*\d)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/

function ErrorElement(props: { error: string[] }) {
    return (
        <>{
            props.error.map(err => {
                return <span className='bg-gray-300 text-red-800 p-2 m-1 rounded shadow-xl'>{err}</span>
            })
        }</>
    )
}

function FormElement(props: FormElementProps & ErrorProps) {
    const [state, setState] = useState<string>()

    const handleChange = (event: ChangeEvent<HTMLInputElement>) => {
        props.onChange(event.target.value)
        setState(event.target.value);
    }

    const error = props.errors[props.name]
    const hasError = error && error.length > 0

    const styles = `
        p-3 m-2 w-10/12 rounded-xl shadow-xl bg-gray-100 dark:bg-gray-500
        placeholder-opacity-60  placeholder-gray-800 dark:placeholder-gray-400 
        transition duration-300 ease-in-out transform focus:-translate-y-1 
       
        ${hasError
            ? 'focus:scale-110 focus:outline-none ring-red-900 ring-2'
            : 'ring-0 focus:scale-110 focus:outline-none focus:ring-blue-900 focus:ring-2'
        }
        
        focus:placeholder-gray-900 dark:focus:placeholder-gray-100 
        text-gray-800 dark:text-gray-100
    `

    return (
        <>
            <input className={styles} placeholder={props.placeholder} type={props.type} value={state}
                   onChange={handleChange}/>

            <div className='flex flex-row'>
                {hasError
                    ? <ErrorElement error={error}/>
                    : <></>
                }
            </div>
        </>
    )
}

function Login() {
    const [state, setState] = useState<LoginState>('idle')
    const [errors, setErrors] = useState<ErrorState>({})
    const [email, setEmail] = useState<string>()
    const [password, setPassword] = useState<string>()

    const hasAnyErrors = (obj: ErrorState): boolean => {
        for (const [,errs] of Object.entries(obj)) {
            if (errs && errs.length > 0) {
                return true
            }
        }
        return false
    }

    const handleSubmit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault()

        const newErrors: ErrorState = {
            email: [],
            password: []
        }

        if (!email) {
            newErrors.email?.push("No email provided")
        }

        if (!password) {
            newErrors.password?.push("No password provided")
        }

        if (password && !PASSWORD_VALIDATION_REGEX.test(password)) {
            newErrors.password?.push("Password too weak")
            newErrors.password?.push("Must must be 8 or more characters, contain at least one uppercase character, lowercase character and number")
        }

        setErrors(newErrors)

        if (hasAnyErrors(newErrors)) {
            setState('validation_failed')
        }
        else {
           setState('logging_in')
        }
    }

    if (state === 'logging_in') {
        if (!email || !password || hasAnyErrors(errors)) {
            throw new TypeError("Email or password was not set, or validation failed")
        }

        getAuth().login(email, password).then(isLoggedIn => {
            if (isLoggedIn) {
                setState('logged_in')
            }
            else {
                const newErrors: ErrorState = {
                    email: ["Email or password incorrect"],
                    password: ["Email or password incorrect"]
                }

                setErrors(newErrors)
                setState('login_failed')
            }
        })
        return (
            <p>logging in</p>
        )
    }

    if (state === 'logged_in') {
        return (
            <p>logged in successfully!</p>
        )
    }

    return (
        <div className='flex justify-center w-auto'>
            <div
                className='flex flex-col w-2/3 lg:w-3/5 items-center m-2 rounded-xl shadow-xl bg-gray-200 dark:bg-gray-500'>
                <h2 className='text-2xl text-center font-semibold pt-2 text-gray-900 dark:text-gray-200'>Login</h2>

                <Separator className='w-2/3'/>
                <form className='flex flex-col w-full items-center' onSubmit={handleSubmit}>
                    <FormElement onChange={setEmail} name='email' placeholder='Email' type='text'
                                 errors={errors}/>
                    <FormElement onChange={setPassword} name='password' placeholder='Password' type='password'
                                 errors={errors}/>
                    <input className='p-2 m-2 w-10/12 text-gray-100 font-semibold text-md bg-blue-900 rounded-xl shadow-xl'
                           type="submit" value="Submit"/>
                </form>
            </div>
        </div>
    )
}

export default Login