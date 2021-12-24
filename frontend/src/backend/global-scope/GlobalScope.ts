import { BehaviorSubject, skip } from 'rxjs'
import { JSONObject } from '../JSONObject'
import { ManagerController } from '../manager/ManagerController'
import { ContextController } from './context/ContextController'

export type LocalStorage = JSONObject

export class Globals {
    public readonly backend: ManagerController
    public readonly context: ContextController

    constructor() {
        this.backend = new ManagerController() // passing this so that the backend works for context init
        this.context = new ContextController() // passing this so that contexts can use the backend to init
    }
}

export const GlobalScope = new BehaviorSubject<Globals>(new Globals())

GlobalScope.pipe(
    skip(1) // skip the first element, which is the current value
).subscribe(() => {
    throw new TypeError('Globals have changed, this is a bug.')
})
