import {useCallback, useState} from "react";
import {User} from "../../model/User";
import {getAuth} from "../global-scope/util/getters";
import {useSubscription} from "./useSubscription";

export function useSelfUser(): User | undefined {
    const user$$ = getAuth().observeUser$$
    const [selfUser, setSelfUser] = useState<User | undefined>(user$$.value)

    useSubscription(user$$, useCallback(newUser => setSelfUser(newUser), []))
    return selfUser
}