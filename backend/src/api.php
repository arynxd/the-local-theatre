<?php

require_once 'backend/util/Logger.php';
Logger ::enableErrors();

require_once 'backend/util/constant/StatusCode.php';
require_once 'backend/util/constant/ErrorStrings.php';
require_once 'backend/request/Connection.php';

$conn = new Connection();

if (!$conn -> route -> validateMethod($conn)) {
    $conn -> res -> sendError("Invalid Method " . $conn -> method, StatusCode::BAD_REQUEST);
    exit;
}

$routeResult = $conn -> route -> validateRequest($conn, $conn -> res);

if (!$routeResult -> isError()) {
    try {
        $conn -> route -> handle($conn, $conn -> res);
    }
    catch (Exception $ex) {
        $conn -> res -> sendInternalError();
    }
}
else {
    $conn -> res -> sendError($routeResult -> error, $routeResult -> httpCode, ...$routeResult -> headers);
    exit;
}



