<?php

namespace TLT\Middleware;

use TLT\Request\Session;
use TLT\Util\HttpResult;

/**
 * An abstract class representing a middleware within a connection.
 *
 * Middlewares can be run within a routing to perform stateful actions based on the current context.
 *
 * Ideally middlewares should not throw exceptions.
 *
 * However, if this does occur, the connection will be terminated with a 500 internal error code.
 */
abstract class BaseMiddleware {
    /**
     * Applies this middleware to the given session
     *
     * If the middleware throws an error, the request will fail.
     *
     * @param Session $sess the current session
     * @return HttpResult  the result of this middleware
     */
    abstract public function apply($sess);
}
