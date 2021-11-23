import {useSelfUser} from "../../backend/hook/useSelfUser";
import {hasPermission, PermissionLevel, toLevel} from "../../model/Permission";
import {Redirect} from "react-router";
import {Paths} from "../../util/paths";
import Separator from "../../component/Separator";
import {User} from "../../model/User";
import {useAPI} from "../../backend/hook/useAPI";
import {getBackend} from "../../backend/global-scope/util/getters";
import {useState} from "react";

interface ModerationUserProps {
    user: User
}

type ModerationUserState = 'idle' | 'changing_permissions' | 'deletion'

interface ModalProps {
    done: (newLevel: PermissionLevel) => void
}

function PermissionModal(props: ModerationUserProps & ModalProps) {
    const [level, setLevel] = useState(0)

    return (
        <div
            className='absolute flex flex-col items-center justify-center bg-gray-100 w-max h-auto top-0 right-5 z-10 rounded shadow p-2 ring-1'>
            <h2 className='font-md'>Editing {props.user.username}'s permissions</h2>
            <Separator className='w-4/5'/>

            <select onChange={ev => setLevel(parseInt(ev.target.value))} className='text-sm p-2 bg-gray-100 ring-1'>
                <option value="">--Set a permission level--</option>
                <option value={0}>View only</option>
                <option value={1}>Regular user</option>
                <option value={2}>Moderator</option>
            </select>

            <Separator className='w-2/5'/>

            <button className='text-sm bg-gray-100 px-2 py-1 shadow rounded'
                    onClick={() => props.done(toLevel(level))}>Done
            </button>
        </div>
    )
}

function ModerationUser(props: ModerationUserProps) {
    const user = props.user
    const buttonStyles = (colour: string) => `block text-sm md:text-md bg-${colour} shadow rounded p-2`
    const [state, setState] = useState<ModerationUserState>('changing_permissions')

    const handlePermissions = () => {
        setState('changing_permissions')
    }

    const handleDelete = () => {
        setState('deletion')
    }

    return (
        <div className='w-auto bg-gray-100 shadow rounded m-2 p-2'>
            <div className='grid grid-rows-1 grid-cols-3 md:grid-cols-5 gap-4 place-items-center justify-center'>
                <p className='md:col-span-3 place-self-start font-semibold '>{user.firstName} {user.lastName} ({user.username})</p>


                <button onClick={handleDelete} className={buttonStyles('red-300')}>Delete</button>

                <div className='relative'>
                    {state === 'changing_permissions'
                        ? <>
                            <div/>
                            <PermissionModal user={props.user} done={() => setState('idle')}/>
                        </>
                        : <button onClick={handlePermissions} className={buttonStyles('blue-100')}>Permissions</button>
                    }
                </div>
            </div>
        </div>
    )
}

function UserList() {
    const users = useAPI(() => getBackend().http.loadUsers())

    if (!users) {
        return (
            <p>Loading</p>
        )
    }

    return (
        <ul>
            {
                users.map(u => <ModerationUser user={u}/>)
            }
        </ul>
    )
}

export default function Moderation() {
    const selfUser = useSelfUser()

    if (!selfUser || (!hasPermission(selfUser.permissions, 'moderator'))) {
        return (
            <Redirect to={Paths.HOME}/>
        )
    }
    return (
        <div className='w-auto h-auto m-4 p-2 bg-gray-200'>
            <h2 className='p-2 font-semibold text-lg'>Moderation</h2>
            <Separator/>

            <UserList/>
        </div>
    )
}