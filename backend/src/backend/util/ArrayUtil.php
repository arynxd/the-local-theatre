<?php

namespace TLT\Util;

class ArrayUtil {
    public static function arrayLast($arr) {
        if (!is_array($arr) || !count($arr)) {
            return null;
        }

        return $arr[count($arr) - 1];
    }

    public static function arrayMapAssoc($cb, $assoc) {
        $res = [];
        foreach ($assoc as $key => $value) {
            $res[$key] = call_user_func($cb, $key, $value);
        }
        return $res;
    }

    public static function arrayCopy($arr) {
        $newArray = [];
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $newArray[$key] = self ::arrayCopy($value);
            }
            else if (is_object($value)) {
                $newArray[$key] = clone $value;
            }
            else {
                $newArray[$key] = $value;
            }

        }
        return $newArray;
    }

    public static function arraySliceBackward($arr, $offsetFromBack) {
        if (!is_array($arr) || !count($arr)) {
            return [];
        }
        return array_slice($arr, count($arr) - $offsetFromBack);
    }
}