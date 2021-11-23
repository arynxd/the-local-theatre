import {ThemeContext} from "../context/ThemeContext";
import {ManagerController} from "../../manager/ManagerController";
import {AuthContext} from "../context/AuthContext";
import {BehaviorSubject} from "rxjs";
import {Globals, GlobalScope} from "../GlobalScope";

export function getGlobals(): Globals {
    return getGlobalScope().getValue()
}

export function getTheme(): ThemeContext {
    return getGlobals().context.theme
}

export function getAuth(): AuthContext {
    return getGlobals().context.auth
}

export function getBackend(): ManagerController {
    return getGlobals().backend
}

export function getGlobalScope(): BehaviorSubject<Globals> {
    return GlobalScope
}