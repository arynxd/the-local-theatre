<?php

namespace TLT\Util;

class ArrayUtil {
    public static function array_last($arr) {
        if (!is_array($arr) || !count($arr)) {
            return null;
        }

        return $arr[count($arr) - 1];
    }

    public static function array_map_assoc($cb, $assoc) {
        $res = [];
        foreach ($assoc as $key => $value) {
            $res[$key] = call_user_func($cb, $key, $value);
        }
        return $res;
    }

    public static function array_copy($arr) {
        $newArray = array();
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $newArray[$key] = self::array_copy($value);
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
}