<?php

require_once 'backend/util/Logger.php';
Logger ::enableErrors();

require_once 'backend/util/constant/StatusCode.php';
require_once 'backend/util/constant/ErrorStrings.php';
require_once 'backend/request/Session.php';

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
        $sess -> res -> sendInternalError();
    }
}
else {
    $sess -> res -> sendError($routeResult -> error, $routeResult -> httpCode, ...$routeResult -> headers);
    exit;
}



