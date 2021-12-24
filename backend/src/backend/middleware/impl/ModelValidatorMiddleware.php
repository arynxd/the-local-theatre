<?php

namespace TLT\Middleware\Impl;

use TLT\Middleware\BaseMiddleware;
use TLT\Util\Data\Map;
use TLT\Util\HttpResult;

class ModelValidatorMiddleware extends BaseMiddleware {
    private $required;
    private $data;
    private $err;

    /**
     * @param string[] $required The required keys
     * @param Map $data The input data
     * @param string $err The error string
     */
    public function __construct($required, $data, $err) {
        $this->required = $required;
        $this->data = $data;
        $this->err = $err;
    }

    //TODO: have this take a Map<string, function> and perform extra validation
    public function apply($sess) {
        foreach ($this->required as $key) {
            if (!$this->data->exists($key)) {
                return HttpResult::BadRequest($this->err);
            }
        }
        return HttpResult::Ok();
    }
}
