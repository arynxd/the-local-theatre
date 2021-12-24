<?php

namespace TLT\Routing;


use TLT\Request\Response;
use TLT\Request\Session;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\HttpResult;

/**
 * A route to be used in a Router.
 * @see Router
 */
abstract class BaseRoute {
    public $path;
    private $methods;

    /**
     * A subclass constructor for the Route
     * @param string $path the path for the route (eg; 'user/list')
     * @param RequestMethod[] $methods the request methods this route accepts
     */
    protected function __construct($path, $methods) {
        $this -> path = $path;
        $this -> methods = $methods;
    }

    /**
     * Handle an incoming request, ideally this function never throws.
     * All data validation should be performed in validate($sess, $res)
     *
     * @param Session $sess the current session
     * @param Response $res the response to send data to
     */
    public abstract function handle($sess, $res);

    /**
     * Validates this routing based on an incoming request.
     * If the validation result is false, the request fails, otherwise it will continue.
     * If the validation fails, the request fails.
     * Validation should only check the shape of the data, in a pure form. It should not query outside sources.
     *
     * @param Session $sess the current session
     * @param Response $res the response to send data to
     * @return HttpResult  the result of the validation
     */
    public abstract function validateRequest($sess, $res);

    /**
     * Validates this route based on an incoming request's methods.
     *
     * @param Session $sess the current session
     * @return  boolean     true, if the validation passed, false otherwise
     */
    public function validateMethod($sess) {
        return in_array($sess -> http -> method, $this -> methods);
    }
}