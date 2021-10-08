<?php
require_once __DIR__ . '/../util/constant/StatusCode.php';

abstract class Route {
    public $path;
    public $middlewares;

    private $methods;

    public function __construct($path, $methods, $middlewares) {
        $this -> path = $path;
        $this -> methods = $methods;
        $this -> middlewares = $middlewares;
    }

    /**
     * Handle an incoming request, ideally this function never throws.
     * All validation should be performed in validate($conn, $res, ...$params)
     *
     * @param $conn       Connection the current connection
     * @param $res        Response   the response to send data to
     * @return            null       the return value is ignored
     */
    public abstract function handle($conn, $res);

    /**
     * Validates this route based on an incoming request.
     * If the validation result is false, the request fails, otherwise it will continue.
     * If the validation fails, the request fails.
     * Validation should only check the state of the data, in a pure form. It should not query outside sources.
     *
     * @param $conn       Connection the current connection
     * @param $res        Response   the response to send data to
     * @return            Array      an array with at least 3 elements, 
     *                               the first being the result of the validation, ideally a boolean,
     *                               the second being an error to display when validation fails,
     *                               the third onward being the headers to send when validation fails.
     */
    public abstract function validateRequest($conn, $res);

    /**
     * Validates this route based on an incoming request's methods.
     *
     * @param $conn       Connection the current connection
     * @return            boolean    true, if the validation passed, false otherwise
     */
    public function validateMethod($conn) {
        return in_array($conn -> method, $this -> methods);
    }
}