<?php

namespace TLT\Routing\Impl;

use TLT\Middleware\Impl\DatabaseMiddleware;
use TLT\Model\Impl\UserModel;
use TLT\Routing\BaseRoute;
use TLT\Util\Data\Map;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\HttpResult;

class UserListRoute extends BaseRoute {
	public function __construct() {
		parent::__construct('user/list', [RequestMethod::GET]);
	}

	public function handle($sess, $res) {
		$users = $sess->data->user->getAll();

		$users = $users->map(function ($_, $value) {
			return $value->toMap();
		});

		$res->status(200)
			->cors('all')
			->json($users);
	}

	public function validate($sess, $res) {
		$sess->applyMiddleware(new DatabaseMiddleware());

		return HttpResult::Ok();
	}
}
