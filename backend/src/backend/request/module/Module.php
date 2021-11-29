<?php
namespace TLT\Request\Module;

use TLT\Request\Session;

class Module {
    /**
     * @var Session $sess The current session
     */
    protected $sess;

    public function __construct($sess) {
        $this -> sess = $sess;
    }

    public function onEnable() {
        // By default, this function does nothing
    }
}