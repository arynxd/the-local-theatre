import winston from "winston";

const LOG_LEVEL = process.env.NODE_ENV === 'development' ? 'info' : 'error'

const config: winston.LoggerOptions = {
    level: LOG_LEVEL,
    format: winston.format.simple()
}

/**
 * The primary logger object. **ALL** logs should be put through this object.
 */
export const logger = winston.createLogger(config)
    .add(new winston.transports.Console())
