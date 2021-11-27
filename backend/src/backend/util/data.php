<?php

function readData($fileName, ...$headers) {
    foreach ($headers as $header) {
        header($header);
    }
    readfile(__DIR__ . "/../../data/$fileName");
}

function readDataOrDefault($fileName, $default, ...$headers) {
    $path = __DIR__ . "/../../data/";

    if (file_exists($path . $fileName)) {
        readData($fileName, ...$headers);
    }
    else if (file_exists($path . $default)) {
        readData($default, ...$headers);
    }
    else {
        throw new UnexpectedValueException("File did not exist at fileName ($fileName) or default ($default)");
    }
}