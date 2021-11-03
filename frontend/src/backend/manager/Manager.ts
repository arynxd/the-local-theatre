import {BackendController} from "../BackendController";

/**
 * Generic manager class, holds an instance of the backend which can be used by children to access other Manager(s)
 */
export class Manager {
    constructor(public readonly backend: BackendController) {
    }
}