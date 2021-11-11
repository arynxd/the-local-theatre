import {EntityIdentifier} from "../../model/EntityIdentifier";
import {useParams} from "react-router";

export function Post() {
    const id = useParams<{ id: EntityIdentifier }>().id

    return (
        <p>ID POST PAGE {id}</p>
    )
}
