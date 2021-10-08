<?php
require_once 'backend/util/ErrorHandling.php';
ErrorHandling::enableErrors();

require_once 'backend/db/Database.php';
require_once 'backend/route/impl/BaseRoute.php';
require_once 'backend/route/impl/UserRoute.php';
require_once 'backend/route/impl/UserListRoute.php';
require_once 'backend/util/constant/StatusCode.php';
require_once 'backend/route/Router.php';
require_once 'backend/request/Response.php';
require_once 'backend/request/Connection.php';
require_once 'backend/util/array.php';
require_once 'backend/util/JSONLoader.php';
require_once 'backend/util/constant/ErrorStrings.php';

$config = new JSONLoader("./config.json");
$config -> load();
$config = $config -> data();

$res = new Response();

try {
    $db = new Database($config['db_url'], $config['db_username'], $config['db_password'], $config['db_name']);

    $router = new Router([
        new BaseRoute($db),
        new UserRoute($db),
        new UserListRoute($db)
    ]);

    $router -> handleCors();

    $conn = new Connection($db, $router);

    if (!$conn -> route -> validateMethod($conn)) {
        $res -> sendError("Invalid Method " . $conn -> method, StatusCode::BAD_REQUEST);
        exit;
    }

    $route_result = $conn -> route -> validateRequest($conn, $res);

    if ($route_result[0]) {
        $conn -> route -> handle($conn, $res);
    }
    else {
        $res -> sendError($route_result[1], ...array_slice($route_result, 2));
        exit;
    }
}
catch (Exception $ex) {
    ErrorHandling::log($ex);
    $res -> sendError(ErrorStrings::INTERNAL_ERROR);
}



