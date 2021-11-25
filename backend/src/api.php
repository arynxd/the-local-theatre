<?php

require_once 'backend/util/Logger.php';
Logger ::enableErrors();

require_once 'backend/util/constant/StatusCode.php';
require_once 'backend/util/constant/ErrorStrings.php';
require_once 'backend/request/Session.php';

$sess = new Session();

if (!$sess -> route -> validateMethod($sess)) {
    $sess -> res -> sendError("Invalid Method " . $sess -> method, StatusCode::BAD_REQUEST);
    exit;
}

$routeResult = $sess -> route -> validateRequest($sess, $sess -> res);

if (!$routeResult -> isError()) {
    try {
        $sess -> route -> handle($sess, $sess -> res);
    }
    catch (Exception $ex) {
        $sess -> res -> sendInternalError();
    }
}
else {
    $sess -> res -> sendError($routeResult -> error, $routeResult -> httpCode, ...$routeResult -> headers);
    exit;
}



