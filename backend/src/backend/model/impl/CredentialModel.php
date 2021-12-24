<?php

namespace TLT\Model\Impl;

use TLT\Model\Model;
use TLT\Util\Data\Map;

class CredentialModel extends Model {
	public $userId;
	public $email;
	public $password;
	public $token;

	public function __construct($userId, $email, $password, $token) {
		$this->userId = $userId;
		$this->email = $email;
		$this->password = $password;
		$this->token = $token;
	}

	public function toMap() {
		return Map::from([
			'userId' => $this->userId,
			'email' => $this->email,
			'password' => $this->password,
			'token' => $this->token,
		]);
	}
}
