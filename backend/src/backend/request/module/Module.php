<?php

class Module {
    /**
     * @var Session $sess The current session
     */
    protected $sess;

    public function __construct($sess) {
        $this -> sess = $sess;
    }

    protected function onEnable() {
        // By default, this function does nothing
    }
}