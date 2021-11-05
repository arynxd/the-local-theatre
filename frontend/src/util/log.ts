import winston from "winston";

function getConfig(): winston.LoggerOptions {
    return {
        level: process.env.NODE_ENV === 'development' || process.env.NODE_ENV === 'test' ? 'debug' : 'error',
        format: winston.format.simple()
    }
}

/**
 * The primary logger object. **ALL** logs should be put through this object.
 */
export const logger = winston.createLogger(getConfig())
    .add(new winston.transports.Console())
