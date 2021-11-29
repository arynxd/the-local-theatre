<?php
function strStartsWith($haystack, $needle) {
    $length = strlen($needle);
    return substr($haystack, 0, $length) === $needle;
}

spl_autoload_register(function ($class) {
    if (!strStartsWith($class, "TLT")) {
        throw new UnexpectedValueException("Tried to autoload class $class which was not a part of our namespace");
    }

    $parts = explode('\\', $class);
    $parts = array_slice($parts, 1);

    $toLower = array_slice($parts, 0, count($parts) - 1);
    foreach ($toLower as $i => $elem) {
        $toLower[$i] = strtolower($elem);
    }

    require_once(__DIR__ . "/backend/" . join("/", $toLower) . "/" . $parts[count($parts) - 1] . '.php');
});