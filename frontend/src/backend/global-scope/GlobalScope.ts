import {BehaviorSubject, skip} from "rxjs";
import {JSONObject} from "../JSONObject";
import {BackendController} from "../BackendController";
import {ContextController} from "./ContextController";

export type LocalStorage = JSONObject

export class Globals {
    public readonly backend: BackendController
    public readonly context: ContextController

    constructor() {
        this.backend = new BackendController()
        this.context = new ContextController()
    }
}

export const GlobalScope = new BehaviorSubject<Globals>(new Globals())

GlobalScope
    .pipe(
        skip(1) // skip the first element, which is the current value
    )
    .subscribe(() => {
        throw new TypeError("Globals have changed, this is a bug.")
    }
)

