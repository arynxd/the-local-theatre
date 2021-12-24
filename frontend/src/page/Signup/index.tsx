import { FormEvent, useState } from 'react'
import {
    ErrorElement,
    ErrorState,
    FormElement,
} from '../../component/FormElement'
import { getAuth } from '../../backend/global-scope/util/getters'
import { logger } from '../../util/log'
import { Redirect } from 'react-router'
import { Paths } from '../../util/paths'
import Separator from '../../component/Separator'
import { isStrongPassword } from '../../util/const'

type SignupState =
    | 'signing_up'
    | 'validation_failed'
    | 'signup_failed'
    | 'idle'
    | 'signed_up'

export default function Signup() {
    const [state, setState] = useState<SignupState>('idle')

    const [errors, setErrors] = useState<ErrorState>({})

    const [firstName, setFirstName] = useState<string>()
    const [lastName, setLastName] = useState<string>()
    const [username, setUsername] = useState<string>()
    const [email, setEmail] = useState<string>()
    const [dob, setDob] = useState<string>()
    const [password, setPassword] = useState<string>()

    const handleSubmit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault()

        const newErrors: ErrorState = {
            firstName: [],
            lastName: [],
            username: [],
            email: [],
            dob: [],
            password: [],
        }

        let err = false

        if (!firstName) {
            newErrors.firstName?.push('First name is required')
            err = true
        }

        if (!lastName) {
            newErrors.lastName?.push('Last name is required')
            err = true
        }

        if (!username) {
            newErrors.username?.push('Username is required')
            err = true
        }

        if (!email) {
            newErrors.email?.push('Email is required')
            err = true
        }

        if (!dob) {
            newErrors.dob?.push('Date of birth is required')
            err = true
        }

        if (!password) {
            newErrors.password?.push('Password is required')
            err = true
        }

        if (password && !isStrongPassword(password)) {
            newErrors.password?.push('Password is too weak')
            err = true
        }

        if (err) {
            setState('validation_failed')
        } else {
            setState('signing_up')
        }

        setErrors(newErrors)
    }

    if (state === 'signing_up') {
        if (
            !firstName ||
            !lastName ||
            !username ||
            !email ||
            !dob ||
            !password
        ) {
            throw new TypeError('Not all required fields in signup were set')
        }

        getAuth()
            .signup({
                firstName,
                lastName,
                username,
                email,
                dob: new Date(dob),
                password,
            })
            .then(() => {
                setState('signed_up')
            })
            .catch((err) => {
                setErrors({
                    general: [
                        'An error occurred whilst logging in, please try again',
                    ],
                })
                setState('signup_failed')
                logger.error(err)
            })
    }

    if (state === 'signed_up') {
        return <Redirect to={Paths.HOME} />
    }

    return (
        <div className="flex justify-center w-auto">
            <div className="flex flex-col w-2/3 lg:w-3/5 items-center m-2 rounded-xl shadow-xl bg-gray-200 dark:bg-gray-500">
                <h2 className="text-2xl text-center font-semibold pt-2 text-gray-900 dark:text-gray-200">
                    Signup
                </h2>

                <Separator className="w-2/3" />
                <form
                    className="flex flex-col w-full items-center"
                    onSubmit={handleSubmit}
                >
                    <FormElement
                        onChange={setFirstName}
                        name="firstName"
                        placeholder="First name"
                        type="text"
                        errors={errors}
                    />
                    <FormElement
                        onChange={setLastName}
                        name="lastName"
                        placeholder="Last name"
                        type="text"
                        errors={errors}
                    />
                    <FormElement
                        onChange={setUsername}
                        name="username"
                        placeholder="Username"
                        type="text"
                        errors={errors}
                    />
                    <FormElement
                        onChange={setEmail}
                        name="email"
                        placeholder="Email"
                        type="email"
                        errors={errors}
                    />
                    <FormElement
                        onChange={setDob}
                        name="dob"
                        placeholder="Date of birth"
                        type="date"
                        errors={errors}
                    />
                    <FormElement
                        onChange={setPassword}
                        name="password"
                        placeholder="Password"
                        type="password"
                        errors={errors}
                    />

                    <ErrorElement error={errors.general} />
                    <button
                        className="p-2 m-2 w-10/12 inline-flex items-center justify-center text-gray-100 font-semibold text-md bg-blue-900 rounded-xl shadow-xl"
                        type="submit"
                    >
                        {state === 'signing_up' ? (
                            <svg
                                className="animate-spin mx-2 h-5 w-5 text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    className="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                                <path
                                    className="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                        ) : (
                            <> </>
                        )}
                        Submit
                    </button>
                </form>
            </div>
        </div>
    )
}
