import { Email, Phone } from "../../component/Icons";
import Separator from "../../component/Separator";

const CONTACT_DETAILS = Object.freeze({
    email: "admin@test.com",
    phone: "00000 000 000"
})


export default function Contact() {
    return (
        <div className='md:flex md:justify-center md:w-auto'>
            <div
                className='flex flex-col w-auto items-center md:w-1/3 h-44 m-2 rounded-2xl shadow-xl bg-gray-200 dark:bg-gray-500'>
                <h2 className='text-2xl text-center font-semibold pt-2 text-gray-900 dark:text-gray-200'>Contact Us</h2>

                <Separator className='w-3/5 md:w-3/12'/>

                <div className='flex flex-row items-center'>
                    <Email className='w-10 h-10 m-2'/>
                    <a href="mailto:admin@test.com"><p
                        className='text-xl m-2 text-gray-800 dark:text-gray-300'>{CONTACT_DETAILS.email}</p></a>
                </div>

                <div className='flex flex-row items-center mb-4'>
                    <Phone className='w-10 h-10 m-1'/>
                    <p className='text-xl m-3 text-gray-800 dark:text-gray-300'>{CONTACT_DETAILS.phone}</p>
                </div>
            </div>
        </div>
    )
}
