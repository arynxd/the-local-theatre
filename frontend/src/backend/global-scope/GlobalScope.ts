import {BehaviorSubject} from "rxjs";
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

