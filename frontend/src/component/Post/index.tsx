import {User} from "../../model/User";
import styled from "styled-components";
import {DEFAULT_FONT} from "../../Constants";

interface PostProps {
    content: string,
    author: User,
}

const PostContent = styled.p`
  
`

const PostContainer = styled.div`

`

const PostAuthor = styled.h2`
  ${DEFAULT_FONT}
  text-align: left
`

export function Post(props: PostProps) {
    return (
        <>
            <PostAuthor>{props.author.name}</PostAuthor>
            <PostContainer>
                <PostContent>{props.content}</PostContent>
            </PostContainer>
        </>
    )
}