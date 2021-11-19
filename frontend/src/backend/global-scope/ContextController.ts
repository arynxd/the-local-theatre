import {AuthContext} from "./context/AuthContext";
import {ThemeContext} from "./context/ThemeContext";

export class ContextController {
    public readonly auth: AuthContext
    public readonly theme: ThemeContext

    constructor() {
        this.auth = new AuthContext()
        this.theme = new ThemeContext()
    }
}