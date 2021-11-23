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

        const errors: ErrorState = {
            password: []
        }

        let err = false

        if (password && !isStrongPassword(password)) {
            errors.password?.push("Password is too weak")
            err = true
        }

        setErrors(errors)

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
            <Redirect to={Paths.HOME} />
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
                    <FormElement initialState={firstName} onChange={setFirstName} name='firstName' placeholder='First name' type='text'
                                 errors={errors}/>
                    <FormElement initialState={lastName} onChange={setLastName} name='lastName' placeholder='Last name' type='text'
                                 errors={errors}/>
                    <FormElement initialState={username} onChange={setUsername} name='username' placeholder='Username' type='text'
                                 errors={errors}/>
                    <FormElement initialState={email} onChange={setEmail} name='email' placeholder='Email' type='email'
                                 errors={errors}/>
                    <FormElement onChange={setPassword} name='password' placeholder='Password' type='password'
                                 errors={errors}/>

                    <ErrorElement error={errors.general}/>
                    {state === 'attempting'
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