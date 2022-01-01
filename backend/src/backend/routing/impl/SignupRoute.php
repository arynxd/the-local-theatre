<?php

// Set a new signup entry
// POST

namespace TLT\Routing\Impl;

use TLT\Middleware\Impl\DatabaseMiddleware;
use TLT\Middleware\Impl\ModelValidatorMiddleware;
use TLT\Model\Impl\CredentialModel;
use TLT\Model\Impl\UserModel;
use TLT\Model\ModelKeys;
use TLT\Routing\BaseRoute;
use TLT\Util\Assert\Assertions;
use TLT\Util\AuthUtil;
use TLT\Util\Data\Map;
use TLT\Util\DBUtil;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\HttpResult;
use TLT\Util\HttpUtil;
use TLT\Util\StringUtil;

class SignupRoute extends BaseRoute {
	public function __construct() {
		parent::__construct('signup', [RequestMethod::POST]);
	}

	public function handle($sess, $res) {
		$body = $sess->jsonParams();

		$cred = $sess->data->credential;
		$sess->data->start();

		$user = $cred->get($body['email']);

		if (isset($user)) {
			$res->status(409)->error('Account already exists');
		} else {
			$token = AuthUtil::generateToken();
			$id = StringUtil::newID();

			$user = $sess->data->user;

			$userModel = new UserModel(
				$id,
				$body['firstName'],
				$body['lastName'],
				DBUtil::DEFAULT_PERMISSIONS,
				$body['dob'],
				DBUtil::currentTime(),
				$body['username']
			);

			$credModel = new CredentialModel(
				$id,
				$body['email'],
				AuthUtil::hashPassword($body['password']),
				$token
			);

			$user->insert($userModel);
			$cred->insert($credModel);

			$sess->data->commit();

			$res->status(200)
				->cors('all')
				->json([
					'token' => $token,
				]);
		}
	}

	public function validate($sess, $res) {
		$sess->routing->middlware('db');

		$data = $sess->jsonParams();

		HttpUtil::validateBody($data, [
			'firstName' => 'string',
			'lastName' => 'string',
			'dob' => 'int',
			'username' => 'string',
			'email' => 'string', 
			'password' => 'string'
		]);
		return HttpResult::Ok();
	}
}
