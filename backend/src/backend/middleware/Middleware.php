<?php
namespace TLT\Middleware;

use TLT\Request\Session;
use TLT\Util\Result;

/**
 * An abstract class representing a middleware within a connection.
 *
 * Middlewares can be run within a routing to perform stateful actions based on the current context.
 *
 * Ideally middlewares should not throw exceptions.
 *
 *  However, if this does occur, the connection will be terminated with a 500 internal error code.
 */
abstract class Middleware {
    /**
     * Applies this middleware to the given connection
     *
     * If the middleware throws an error, the request will fail.
     *
     * @param Session $sess the current connection
     * @return Result  the result of this middleware
     */
    public abstract function apply($sess);
}