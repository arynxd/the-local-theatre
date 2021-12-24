<?php

namespace TLT\Request\Module\Impl;

use TLT\Request\Module\BaseModule;
use TLT\Util\Assert\Assertions;
use TLT\Util\Data\JSONLoader;
use TLT\Util\Log\Logger;

class ConfigModule extends BaseModule {
    /**
     * Whether the DB is enabled for this session
     * @var boolean $dbEnabled
     */
    public $dbEnabled;

    /**
     * The current environment
     *
     * @var string $env
     */
    public $env;

    /**
     * @var string $dbURL
     */
    public $dbURL;

    /**
     * @var string $dbUsername
     */
    public $dbUsername;

    /**
     * @var string $dbPassword
     */
    public $dbPassword;

    public function onEnable() {
        $loader = new JSONLoader('./config.json');
        $loader->load();
        $raw = $loader->data();

        if (!isset($raw)) {
            Logger::getInstance()->fatal('config.json could not be loaded');
        }

        Logger::getInstance()->info(
            'config.json loaded successfully, setting fields'
        );

        $this->dbEnabled = (bool) $raw['db_enabled'];
        $this->dbURL = (string) $raw['db_url'];
        $this->dbUsername = (string) $raw['db_username'];
        $this->dbPassword = (string) $raw['db_password'];
    }
}
