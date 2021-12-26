<?php

function startsWith($haystack, $needle) {
	return substr($haystack, 0, strlen($needle)) === $needle;
}

// Register an autoloader which can handle our fs layout.
// It will take the incoming class (namespace) and extract the file path from it.
// It achieves this by assuming the namespace name matches the fs layout.
spl_autoload_register(function ($class) {
	if (!startsWith($class, 'TLT')) {
		throw new UnexpectedValueException(
			"Tried to autoload class $class which was not a part of our namespace"
		);
	}

	$parts = explode('\\', $class);
	$parts = array_slice($parts, 1);

	$lowered = array_slice($parts, 0, count($parts) - 1);
	foreach ($lowered as $i => $elem) {
		$lowered[$i] = strtolower($elem);
	}

	$srcPath = join('/', $lowered) . '/' . $parts[count($parts) - 1];
	$backendPrefix = __DIR__ . '/backend/';

	require_once $backendPrefix . $srcPath . '.php';
});
