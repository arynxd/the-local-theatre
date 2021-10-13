import {Post} from "../../component/Post";
import {useEffect, useState} from "react";
import {LoadingUser, User} from "../../model/User";
import {BackendProps} from "../../component/props/BackendProps";

function Home(props: BackendProps) {
    const [postAuthor, setPostAuthor] = useState<User>(LoadingUser)
    const [postContent, setPostContent] = useState<String>("Loading")

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
    })


    return (
        <Post content={ postContent as string } author={ postAuthor } />
    )
}

export default Home