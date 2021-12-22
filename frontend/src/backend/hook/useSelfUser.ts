import {useCallback, useState} from "react";
import { SelfUser } from "../../model/SelfUser";
import {User} from "../../model/User";
import {getAuth} from "../global-scope/util/getters";
import {useSubscription} from "./useSubscription";

export function useSelfUser(): SelfUser | undefined {
    const user$$ = getAuth().observeUser$$
    const [selfUser, setSelfUser] = useState<SelfUser | undefined>(user$$.value)

    useSubscription(user$$, useCallback(newUser => setSelfUser(newUser), []))
    return selfUser
}