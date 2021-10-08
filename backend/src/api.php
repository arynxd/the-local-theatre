<?php
require_once 'backend/util/ErrorHandler.php';
ErrorHandler::enableErrors();

require_once 'backend/util/constant/StatusCode.php';
require_once 'backend/request/Connection.php';

$conn = new Connection();

if (!$conn -> route -> validateMethod($conn)) {
    $conn -> res -> sendError("Invalid Method " . $conn -> method, StatusCode::BAD_REQUEST);
    exit;
}

$route_result = $conn -> route -> validateRequest($conn, $conn -> res);

if ($route_result[0]) {
    $conn -> route -> handle($conn, $conn -> res);
}
else {
    $conn -> res -> sendError($route_result[1], ...array_slice($route_result, 2));
    exit;
}



