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

if (!$routeResult -> isError()) {
    try {
        $route -> handle($sess, $sess -> res);
    }
    catch (Exception $ex) {
        $sess -> res -> sendInternalError($ex);
    }
}
else {
    $sess -> res -> sendError($routeResult -> error, $routeResult -> httpCode, ...$routeResult -> headers);
    exit;
}



