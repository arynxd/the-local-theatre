<?php

function array_last($arr) {
    if (!is_array($arr) || !count($arr)) {
        return null;
    }

    return $arr[count($arr) - 1];
}

function array_map_assoc($cb, $assoc) {
    $res = [];
    foreach ($assoc as $key => $value) {
        $res[$key] = call_user_func($cb, $key, $value);
    }
    return $res;
}