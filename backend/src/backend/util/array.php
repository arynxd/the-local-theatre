<?php

function array_last($arr) {
    if (!is_array($arr) || !count($arr)) {
        return null;
    }
    return $arr[count($arr) - 1];
}