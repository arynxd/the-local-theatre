import {Post} from "../../component/Post";
import {useEffect, useState} from "react";
import {User} from "../../model/User";
import {BackendProps} from "../../component/props/BackendProps";
import {LoadingIcon} from "../../component/LoadingIcon";

function Home(props: BackendProps) {
    const [postAuthor, setPostAuthor] = useState<User>()
    const [postContent, setPostContent] = useState<string>()

    useEffect(() => {
        props.backend.http.listPosts(1)
            .then(posts => {
                if (posts) {
                    const post = posts[0]

                    setPostContent(post.content)
                    setPostAuthor(post.author)
                }
                else {
                    setPostContent("ERROR")
                }
            })
    }, [props.backend])

    if (!postAuthor || !postContent) {
        return (
            <LoadingIcon />
        )
    }


    return (
        <Post content={ postContent } author={ postAuthor } />
    )
}

export default Home