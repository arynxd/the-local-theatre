import Logger from 'js-logger'

interface ILogger {
	trace(...x: any[]): void
	debug(...x: any[]): void
	info(...x: any[]): void
	log(...x: any[]): void
	warn(...x: any[]): void
	error(...x: any[]): void
}

const LOG_LEVEL =
	process.env.NODE_ENV === 'development' ? Logger.INFO : Logger.ERROR

// ESLint thinks this is a react hook, it's not
// eslint-disable-next-line
Logger.useDefaults()
Logger.setHandler(Logger.createDefaultHandler())
Logger.setLevel(LOG_LEVEL)

/**
 * The primary logger object. **ALL** logs should be put through this object.
 */
export const logger: ILogger = Logger
