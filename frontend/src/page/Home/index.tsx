import {Post} from "../../component/Post";
import {useEffect, useState} from "react";
import {User} from "../../model/User";
import {BackendProps} from "../../component/props/BackendProps";
import {LoadingIcon} from "../../component/LoadingIcon";
import {logger} from "../../util/log";

function Home(props: BackendProps) {
    const [postAuthor, setPostAuthor] = useState<User>()
    const [postContent, setPostContent] = useState<string>()

    logger.debug('Rendering home page')

    useEffect(() => {
        logger.debug('Sending request for home page post')
        props.backend.http.listPosts(1)
            .then(posts => {
                if (posts) {
                    const post = posts[0]

                    setPostContent(post.content)
                    setPostAuthor(post.author)

                    logger.debug('Received valid post ' + JSON.stringify(post))
                } else {
                    logger.debug('No posts received')
                    setPostContent("ERROR")
                }
            })
    }, [props.backend])

    if (!postAuthor || !postContent) {
        logger.debug('Home page post not loaded, rendering loading icon')
        return (
            <LoadingIcon/>
        )
    }


    return (
        <Post content={postContent} author={postAuthor}/>
    )
}

export default Home