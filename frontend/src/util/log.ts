import winston from "winston";

function getConfig(): winston.LoggerOptions {
    return {
        level: process.env.NODE_ENV === 'development' ? 'debug' : 'error',
        format: winston.format.simple()
    }
}

export const logger = winston.createLogger(getConfig())
    .add(new winston.transports.Console())
