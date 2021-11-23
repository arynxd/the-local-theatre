import {useAPI} from "../../backend/hook/useAPI";
import {getBackend} from "../../backend/global-scope/util/getters";
import Post from "../../component/model/Post";

export default function Blog() {
    const posts = useAPI(() => getBackend().http.listPosts())

    if (!posts) {
        return (
            <p>Loading</p>
        )
    }
    return (
        <div className='flex flex-col items-center justify-center mx-4 md:mx-24 lg:mx-44'>
            {
                posts.map(post => <Post post={post}/>)
            }
        </div>
    )
}
