<?php

function readData($fileName, ...$headers) {
    foreach ($headers as $header) {
        header($header);
    }
    readfile(__DIR__ . "/../../data/$fileName");
}
