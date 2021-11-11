import winston from "winston";

function getConfig(): winston.LoggerOptions {
    return {
        level: LOG_LEVEL,
        format: winston.format.simple()
    }
}

const LOG_LEVEL = process.env.NODE_ENV === 'development' ? 'debug' : 'error'

export const logger = winston.createLogger(getConfig())
    .add(new winston.transports.Console())
