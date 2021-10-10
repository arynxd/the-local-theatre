<?php
require_once 'backend/util/ErrorHandler.php';
ErrorHandler ::enableErrors();

require_once 'backend/util/constant/StatusCode.php';
require_once 'backend/request/Connection.php';

$conn = new Connection();

if (!$conn -> route -> validateMethod($conn)) {
    $conn -> res -> sendError("Invalid Method " . $conn -> method, StatusCode::BAD_REQUEST);
    exit;
}

$routeResult = $conn -> route -> validateRequest($conn, $conn -> res);

if (!$routeResult -> isError()) {
    $conn -> route -> handle($conn, $conn -> res);
}
else {
    $conn -> res -> sendError($routeResult -> error, $routeResult -> httpCode, ...$routeResult -> headers);
    exit;
}



