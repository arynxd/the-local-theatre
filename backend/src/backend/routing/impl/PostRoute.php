<?php

// Get or Set information about a specific blog with the specified <id>
// GET / POST

namespace TLT\Routing\Impl;

use PDO;
use TLT\Middleware\Impl\AuthenticationMiddleware;
use TLT\Middleware\Impl\DatabaseMiddleware;
use TLT\Middleware\Impl\ModelValidatorMiddleware;
use TLT\Model\Impl\PostModel;
use TLT\Model\Impl\UserModel;
use TLT\Model\ModelKeys;
use TLT\Request\Session;
use TLT\Routing\BaseRoute;
use TLT\Util\Assert\Assertions;
use TLT\Util\Data\Map;
use TLT\Util\DBUtil;
use TLT\Util\Enum\PermissionLevel;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\HttpResult;
use TLT\Util\Log\Logger;
use TLT\Util\StringUtil;

class PostRoute extends BaseRoute {
	public function __construct() {
		parent::__construct('post', [
			RequestMethod::GET,
			RequestMethod::POST,
			RequestMethod::DELETE,
		]);
	}

	public function handle($sess, $res) {
		$method = $sess->http->method;
		$post = $sess->data->post;

		if ($method == RequestMethod::GET) {
			$id = $sess->queryParams()['id'];

			Assertions::assertSet($id);

			$model = $post->get($id);

			if (!isset($model)) {
				$res->status(404)
					->cors('all')
					->error('Post not found');
			}

			$res->status(200)
				->cors('all')
				->json($model->toMap());
		} elseif ($method == RequestMethod::POST) {
			Assertions::assert($sess->auth->isAuthenticated());
			Assertions::assert($this->isModMakingRequest($sess));

			$selfUser = $sess->cache->user();
			Assertions::assertSet($selfUser);

			$body = $sess->jsonParams();
			$model = null;

			if (isset($body['id'])) {
				// Update existing entity
				$sess -> data -> start();
				$model = $post->get($body['id']);

				if (!isset($model)) {
					$res->status(404)
						->cors('all')
						->error('Post not found');
				}

				$model->title = $body['title'];
				$model->content = $body['content'];
				$model->editedAt = DBUtil::currentTime();

				$post->edit($model);
				$sess -> data -> commit();
			} else {
				// Insert new entity

				$model = new PostModel(
					StringUtil::newID(),
					$selfUser,
					$body['content'],
					$body['title'],
					DBUtil::currentTime(),
					null
				);

				$post->insert($model);
			}

			Assertions::assertSet($model);

			$res->status(200)
				->cors('all')
				->json($model->toMap());
		} elseif ($method == RequestMethod::DELETE) {
			Assertions::assert($sess->auth->isAuthenticated());
			Assertions::assert($this->isModMakingRequest($sess));

			$id = $sess->queryParams()['id'];
			Assertions::assertSet($id);

			$sess -> data -> start();
			$model = $post->get($id);

			if (!isset($model)) {
				$res->status(404)
					->cors('all')
					->error('Post not found');
			}

			$post->delete($model->id);
			$sess -> data -> commit();

			$res->status(200)
				->cors('all')
				->json($model->toMap());
		} else {
			Logger::getInstance()->fatal("Unknown method $method");
		}
	}

	/**
	 * @param Session $sess
	 * @return boolean whether a mod is making this request
	 */
	private function isModMakingRequest($sess) {
		$self = $sess->cache->user();
		if (!isset($self)) {
			return false;
		}
		return $self->permissions >= PermissionLevel::MODERATOR;
	}

	public function validate($sess, $res) {
		$sess->applyMiddleware(new DatabaseMiddleware());

		$method = $sess->http->method;

		if ($method == RequestMethod::GET) {
			if (!isset($sess->queryParams()['id'])) {
				return HttpResult::BadRequest('No id provided');
			}
		} elseif ($method == RequestMethod::POST) {
			$body = $sess->jsonParams();
			$sess->applyMiddleware(
				new ModelValidatorMiddleware(
					ModelKeys::POST_MODEL(),
					$body,
					'Invalid data provided'
				)
			);
			$sess->applyMiddleware(new AuthenticationMiddleware());

			if (!$this->isModMakingRequest($sess)) {
				return HttpResult::BadRequest(
					'You do not have permission to create this post'
				);
			}
		} elseif ($method == RequestMethod::DELETE) {
			$sess->applyMiddleware(new AuthenticationMiddleware());

			if (!isset($sess->queryParams()['id'])) {
				return HttpResult::BadRequest('No id provided');
			}

			if (!$this->isModMakingRequest($sess)) {
				return HttpResult::BadRequest(
					'You do not have permission to create this post'
				);
			}
		} else {
			Logger::getInstance()->fatal("Unknown method $method");
		}

		return HttpResult::Ok();
	}
}
