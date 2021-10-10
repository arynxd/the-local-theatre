<?php

require_once 'array.php';

/**
 * A wrapper type surrounding PHP's standard associative array.
 * Provides extra functionality and safety features.
 */
class Map implements ArrayAccess {
    private $arr;

    public function __construct($arr = []) {
        $this -> arr = $arr;
    }

    function values() {
        return array_values($this -> raw());
    }

    public function raw() {
        return $this -> arr;
    }

    function toAssocRecursive() {
        return $this -> mapValuesRecursive(function ($item) {
            if ($item instanceof Map) {
                return $item -> raw();
            }
            return $item;
        }) -> raw();
    }

    function mapValuesRecursive($mapper) {
        $func = function ($_, $value) use ($mapper) {
            return call_user_func($mapper, $value);
        };

        return $this -> mapRecursive($func);
    }

    function mapRecursive($mapper) {
        $func = function ($key, $value) use (&$func, &$mapper) {
            $r = call_user_func($mapper, $key, $value);

            if ($r instanceof Map) {
                return new Map(array_map_assoc($func, $r -> raw()));
            }
            else if (is_array($r)) {
                return array_map_assoc($func, $r);
            }

            return $r;
        };

        return new Map(array_map_assoc($func, $this -> raw()));
    }

    function mapValues($mapper) {
        $func = function ($_, $value) use ($mapper) {
            return call_user_func($mapper, $value);
        };

        return $this -> map($func);
    }

    function map($mapper) {
        $res = new Map();
        foreach ($this -> raw() as $key => $value) {
            $res[$key] = call_user_func($mapper, $key, $value);
        }
        return $res;
    }

    function filter($filter) {
        $res = new Map();
        foreach ($this -> raw() as $key => $value) {
            if (call_user_func($filter, $key, $value)) {
                $res[$key] = $value;
            }
        }
    }

    function first() {
        return $this[0];
    }

    function last() {
        return $this[$this -> length() - 1];
    }

    function length() {
        return count($this -> raw());
    }

    public function orFalse($key) {
        return $this -> orDefault($key, false);
    }

    public function orDefault($key, $default) {
        if (!$this -> exists($key)) {
            return $default;
        }
        return $this -> raw()[$key];
    }

    public function exists($key) {
        return array_key_exists($key, $this -> raw());
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this -> raw()[] = $value;
        }
        else {
            $this -> raw()[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this -> raw()[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this -> arr[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this -> raw()[$offset]) ? $this -> raw()[$offset] : null;
    }

    public function __toString() {
        $res = "object(Map){";

        foreach ($this -> raw() as $key => $value) {
            $res .= "$key => $value,";
        }
        return $res . "}";
    }
}