import { AuthContext } from './AuthContext'
import { ThemeContext } from './ThemeContext'

export class ContextController {
	public readonly auth: AuthContext
	public readonly theme: ThemeContext

	constructor() {
		this.auth = new AuthContext()
		this.theme = new ThemeContext()
	}
}
