import {Link} from 'react-router-dom'
import InlineButton from '../../component/InlineButton'
import { Paths } from '../../util/paths'

export function NotFound() {
    return (
        <div className='w-auto rounded shadow-xl m-4 p-4 bg-gray-200 dark:bg-gray-600 flex flex-col items-center'>
            <h1 className='text-4xl text-bold dark:text-gray-100'>404 Not Found</h1>
            <p className='text-lg text-semibold dark:text-gray-100'>You tried to access a page that doesnt exist!</p>
            <div className='h-16'/>
            <InlineButton><Link to={Paths.HOME}>Go to the homepage</Link></InlineButton>
        </div>
    )
}
