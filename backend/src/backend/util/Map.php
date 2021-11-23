<?php

require_once 'array.php';

/**
 * A wrapper type surrounding PHP's standard associative array.
 * Provides extra functionality and safety features.
 */
class Map implements ArrayAccess, JsonSerializable {
    private $arr;
    private $frozen;

    public function __construct($arr = []) {
        $this -> arr = $arr;
        $this -> frozen = false;
    }

    public function values() {
        return array_values($this -> raw());
    }

    public function raw() {
        return array_copy($this -> arr);
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

        return new Map(array_map_assoc($func, $this -> rawInternal()));
    }

    private function rawInternal() {
        return $this -> arr;
    }

    function mapValues($mapper) {
        $func = function ($_, $value) use ($mapper) {
            return call_user_func($mapper, $value);
        };

        return $this -> map($func);
    }

    function map($mapper) {
        $res = new Map();
        foreach ($this -> rawInternal() as $key => $value) {
            $res[$key] = call_user_func($mapper, $key, $value);
        }
        return $res;
    }

    function filter($filter) {
        $res = new Map();
        foreach ($this -> rawInternal() as $key => $value) {
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
        return count($this -> rawInternal());
    }

    public function orFalse($key) {
        return $this -> orDefault($key, false);
    }

    public function orDefault($key, $default) {
        if (!$this -> exists($key)) {
            return $default;
        }
        return $this -> rawInternal()[$key];
    }

    public function exists($key) {
        return array_key_exists($key, $this -> rawInternal());
    }

    public function offsetSet($offset, $value) {
        $this -> throwIfFrozen();

        if (is_null($offset)) {
            $this -> rawInternal()[] = $value;
        }
        else {
            $this -> rawInternal()[$offset] = $value;
        }
    }

    private function throwIfFrozen() {
        if ($this -> frozen) {
            throw new ValueError('Map is frozen and cannot be modified');
        }
    }

    public function offsetExists($offset) {
        return isset($this -> rawInternal()[$offset]);
    }

    public function offsetUnset($offset) {
        $this -> throwIfFrozen();
        unset($this -> arr[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this -> rawInternal()[$offset]) ? $this -> rawInternal()[$offset] : null;
    }

    public function freeze() {
        $this -> frozen = true;
        return $this;
    }

    public function isFrozen() {
        return $this -> frozen;
    }

    public function toJSON() {
        return json_encode($this -> raw());
    }

    public function push($value) {
        $this -> throwIfFrozen();
        $array = $this -> rawInternal();
        array_push($array, $value);
        $this -> arr = $array;
    }

    public function __toString() {
        $res = "object(Map){";

        foreach ($this -> rawInternal() as $key => $value) {
            $res .= "$key => $value,";
        }
        return $res . "}";
    }

    public function jsonSerialize() {
        return $this -> raw();
    }
}

function is_map($value) {
    return $value instanceof Map;
}

function map($arr) {
    return new Map($arr);
}
