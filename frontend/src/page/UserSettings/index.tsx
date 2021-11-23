import {FormEvent, useState} from "react";
import {ErrorElement, ErrorState, FormElement} from "../../component/FormElement";
import Separator from "../../component/Separator";
import {isStrongPassword} from "../../util/const";
import {Redirect} from "react-router";
import {Paths} from "../../util/paths";
import {useSelfUser} from "../../backend/hook/useSelfUser";

type SettingState = 'idle' | 'attempting' | 'validation_failed' | 'setting_failed' | 'success'

export default function UserSettings() {
    const [state, setState] = useState<SettingState>('idle')
    const [errors, setErrors] = useState<ErrorState>({})

    const selfUser = useSelfUser()

    const [firstName, setFirstName] = useState<string | undefined>(selfUser?.firstName)
    const [lastName, setLastName] = useState<string | undefined>(selfUser?.lastName)
    const [username, setUsername] = useState<string | undefined>(selfUser?.username)
    const [email, setEmail] = useState<string | undefined>(selfUser?.username)
    const [password, setPassword] = useState<string | undefined>()

    const handleSubmit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault()

        const newErrors: ErrorState = {
            password: []
        }

        let err = false

        if (password && !isStrongPassword(password)) {
            newErrors.password?.push("Password is too weak")
            err = true
        }

        setErrors(newErrors)

        if (err) {
            setState('validation_failed')
        }
        else {
            setState('attempting')
        }
    }

    if (state === 'attempting') {
        setState('success')
    }

    if (state === 'success') {
        return (
            <Redirect to={Paths.HOME}/>
        )
    }

    return (
        <div className='flex justify-center w-auto'>
            <div
                className='flex flex-col w-2/3 lg:w-3/5 items-center m-2 rounded-xl shadow-xl bg-gray-200 dark:bg-gray-500'>
                <h2 className='text-2xl text-center font-semibold pt-2 text-gray-900 dark:text-gray-200'>Update
                    details</h2>

                <Separator className='w-2/3'/>
                <form className='flex flex-col w-full items-center' onSubmit={handleSubmit}>
                    <FormElement initialState={firstName} onChange={setFirstName} name='firstName'
                                 placeholder='First name' type='text'
                                 errors={errors}/>
                    <FormElement initialState={lastName} onChange={setLastName} name='lastName' placeholder='Last name'
                                 type='text'
                                 errors={errors}/>
                    <FormElement initialState={username} onChange={setUsername} name='username' placeholder='Username'
                                 type='text'
                                 errors={errors}/>
                    <FormElement initialState={email} onChange={setEmail} name='email' placeholder='Email' type='email'
                                 errors={errors}/>
                    <FormElement onChange={setPassword} name='password' placeholder='Password' type='password'
                                 errors={errors}/>

                    <ErrorElement error={errors.general}/>
                    <button className='p-2 m-2 w-10/12 inline-flex items-center justify-center text-gray-100 font-semibold text-md bg-blue-900 rounded-xl shadow-xl' type='submit'>
                        {state === 'attempting'
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