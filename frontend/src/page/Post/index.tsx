import {EntityIdentifier} from "../../model/EntityIdentifier";
import {useParams} from "react-router";
import PostElement from '../../component/model/Post'
import {useAPI} from "../../backend/hook/useAPI";
import {getBackend} from "../../backend/global-scope/util/getters";

export function Post() {
    const id = useParams<{id: EntityIdentifier}>().id
    const post = useAPI(() => getBackend().http.loadPost(id))

    if (!post) {
        return (
            <p>Loading</p>
        )
    }

    return (
        <div className='flex flex-col items-center justify-center mx-4 md:mx-24 lg:mx-44'>
            <PostElement post={post} />
        </div>
    )
}
