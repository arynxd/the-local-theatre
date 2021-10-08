<?php

abstract class Middleware {
    /**
     * Applies this middleware to the given connection
     *
     * If the middleware throws an error, the request will fail.
     *
     * @param $conn Connection
     * @return      Array      an array with at least 3 elements,
     *                         the first being the result of this middleware, ideally a boolean,
     *                         the second being an error to display when this middleware fails,
     *                         the third onward being the headers to send when this middleware fails.
     */
    public abstract function apply($conn);
}