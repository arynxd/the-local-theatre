<?php

// we must require the autoloader since namespace file resolution is dictated by it
require_once 'autoloader.php';

use TLT\Request\Response;
use TLT\Request\Session;
use TLT\Util\Enum\LogLevel;
use TLT\Util\Log\Logger;

Logger::getInstance()->enablePHPErrors();
Logger::getInstance()->setLogFile(sys_get_temp_dir() . '/php_log.log');
Logger::getInstance()->setLevel(LogLevel::INFO);
Logger::getInstance()->setIncludeLoc(false);
Logger::getInstance()->insertNewLine();

$agent = $_SERVER['HTTP_USER_AGENT'];
Logger::getInstance()->info('Incoming request from user agent ' . $agent);
Logger::getInstance()->info('Starting new session...');

$sess = null;

try {
    $sess = new Session();
} catch (Exception $ex) {
    Logger::getInstance()->error('Failed to start session..');
    // We will have to create a new response since the session is in an undefined state
    (new Response())->internal($ex);
}
Logger::getInstance()->info('Session enabled without error');

$route = $sess->routing->route;

if (!$route->validateMethod($sess)) {
    Logger::getInstance()->error(
        "Attempted to use unsupported method {$sess->http->method} on route {$route->path}"
    );
    $sess->res->status(400)->error('Unsupported method ' . $sess->http->method);
}

Logger::getInstance()->info('Validating route ' . $route->path);
$routeResult = $route->validateRequest($sess, $sess->res);

if ($routeResult->isError()) {
    $sess->res->status($routeResult->httpCode)->error($routeResult->error);
} else {
    Logger::getInstance()->info(
        'Starting route ' . $route->path . ' (' . $sess->http->method . ')'
    );
    try {
        $route->handle($sess, $sess->res);
        $sess->res->internal('No output received from the route');
    } catch (Exception $ex) {
        Logger::getInstance()->error('Route threw an uncaught error');
        $sess->res->internal($ex);
    }
}
