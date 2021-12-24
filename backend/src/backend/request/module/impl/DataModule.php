<?php

namespace TLT\Request\Module\Impl;

use TLT\Request\Module\BaseModule;
use TLT\Respository\Impl\UserRepository;

class DataModule extends BaseModule {
    /**
     * @var UserRepository $user
     */
    public $user;

    public function onEnable() {
        $this -> user = new UserRepository($this -> sess);
    }
}