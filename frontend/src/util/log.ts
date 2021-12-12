import Logger from "js-logger"

const LOG_LEVEL = process.env.NODE_ENV === 'development' ? Logger.INFO : Logger.ERROR

// ESLint thinks this is a react hook, it's not
// eslint-disable-next-line
Logger.useDefaults();

const consoleHandler = Logger.createDefaultHandler();
 
Logger.setHandler(consoleHandler);

Logger.setLevel(LOG_LEVEL);

/**
 * The primary logger object. **ALL** logs should be put through this object.
 */
export const logger = Logger
