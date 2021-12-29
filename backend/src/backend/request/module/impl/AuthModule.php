<?php

namespace TLT\Request\Module\Impl;

use TLT\Request\Module\BaseModule;
use TLT\Util\Log\Logger;

class AuthModule extends BaseModule {
	/**
	 * @var string|null $token
	 */
	public $token;

	/**
	 * @var boolean $isAuthenticated
	 */
	private $isAuthenticated;

	public function onEnable() {
		$this->token = $this->sess->http->headers['Authorisation'];

		if (!isset($this->token) || !$this->sess->db->isEnabled()) {
			Logger::getInstance()->info(
				'Authorisation header was not set or the DB was not enabled'
			);
			Logger::getInstance()->info(
				'this request will be treated as UNAUTHENTICATED..'
			);
			$this->isAuthenticated = false;
		}
	}

	public function isAuthenticated() {
		if (isset($this->isAuthenticated)) {
			Logger::getInstance()->debug(
				'Short circuiting isAuthenticated with value'
			);
			if ($this->isAuthenticated) {
				Logger::getInstance()->debug("\t true");
			} else {
				Logger::getInstance()->debug("\t false");
			}

			return $this->isAuthenticated;
		}

		Logger::getInstance()->debug('Token is set, looking up from the DB');

		$query = 'SELECT COUNT(*) FROM credential WHERE token = :token';
		$dbRes = $this->sess->db
			->query($query, [
				'token' => $this->token,
			])
			->fetchColumn();

		if ($dbRes > 0) {
			Logger::getInstance()->info(
				'Request auth validated, this is now an AUTHENTICATED request'
			);
			$this->isAuthenticated = true;
		} else {
			Logger::getInstance()->info(
				'Request auth validation failed, this is now an UNAUTHENTICATED request'
			);
			$this->isAuthenticated = false;
		}

		return $this->isAuthenticated;
	}
}
