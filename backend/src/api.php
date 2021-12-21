<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// we must require the autoloader since namespace file resolution is dictated by it
require_once 'autoloader.php';

use TLT\Request\Response;
use TLT\Request\Session;
use TLT\Util\Enum\LogLevel;
use TLT\Util\Enum\StatusCode;
use TLT\Util\Log\Logger;

Logger::getInstance() -> enablePHPErrors();
Logger::getInstance() -> setLogFile(sys_get_temp_dir() . "/php_log.log");
Logger::getInstance() -> setLevel(LogLevel::DISABLED);
Logger::getInstance() -> setIncludeLoc(false);
Logger::getInstance() -> insertNewLine();

$agent = $_SERVER['HTTP_USER_AGENT'];
Logger::getInstance() -> info("Incoming request from user agent " . $agent);
Logger::getInstance() -> info("Starting new session...");

$sess = null;

try {
    $sess = new Session();
}
catch (Exception $ex) {
    Logger::getInstance() -> error("Failed to start session..");
    // We will have to create a new response since the session is in an undefined state
    $res = new Response();
    $res -> sendInternalError($ex);
}
Logger::getInstance() -> info("Session enabled without error");

$route = $sess -> routing -> route;

if (!$route -> validateMethod($sess)) {
    Logger::getInstance() -> error("Attempted to use unsupported method {$sess -> http -> method} on route {$route -> path}");
    $sess -> res -> sendError("Unsupported method " . $sess -> http -> method, [StatusCode::BAD_REQUEST]);
}

Logger ::getInstance() -> info("Validating route " . $route -> path);
$routeResult = $route -> validateRequest($sess, $sess -> res);

if ($routeResult -> isError()) {
    $sess -> res -> sendError($routeResult -> error, $routeResult -> httpCode, $routeResult -> headers);
}
else {
    Logger::getInstance() -> info("Starting route " . $route -> path);
    try {
        $route -> handle($sess, $sess -> res);
        $sess -> res -> sendInternalError("No output received from the route");
    }
    catch (Exception $ex) {
        Logger::getInstance() -> error("Route threw an uncaught error");
        $sess -> res -> sendInternalError($ex);
    }
}



