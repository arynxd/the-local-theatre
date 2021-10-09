<?php

abstract class Middleware {
    /**
     * Applies this middleware to the given connection
     *
     * If the middleware throws an error, the request will fail.
     *
     * @param $conn Connection
     * @return      RouteValidationResult the validation result
     */
    public abstract function apply($conn);
}