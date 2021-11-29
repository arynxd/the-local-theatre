<?php
require_once 'autoloader.php';

use TLT\Request\Session;
use TLT\Util\Enum\StatusCode;
use TLT\Util\Logger;

Logger ::enableErrors();

$sess = new Session();

$route = $sess -> routing -> route;

if (!$route -> validateMethod($sess)) {
    $sess -> res -> sendError("Unsupported method " . $sess -> http -> method, StatusCode::BAD_REQUEST);
    exit;
}

$routeResult = $route -> validateRequest($sess, $sess -> res);

if ($routeResult -> isError()) {
    $sess -> res -> sendError($routeResult -> error, $routeResult -> httpCode, ...$routeResult -> headers);
}
else {
    try {
        $route -> handle($sess, $sess -> res);
        $sess -> res -> sendInternalError("No output received from the route");
    }
    catch (Exception $ex) {
        $sess -> res -> sendInternalError($ex);
    }
}



