<?php

// Get or Set a comment from the specified <user_id> on a given <blog_id>, passing in the current <timestamp> as an epoch
// GET / PUT

namespace TLT\Routing\Impl;

use TLT\Middleware\Impl\AuthenticationMiddleware;
use TLT\Middleware\Impl\DatabaseMiddleware;
use TLT\Middleware\Impl\ModelValidatorMiddleware;
use TLT\Model\Impl\CommentModel;
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
use TLT\Util\HttpUtil;
use TLT\Util\Log\Logger;
use TLT\Util\StringUtil;

class CommentRoute extends BaseRoute {
	public function __construct() {
		parent::__construct('comment', [
			RequestMethod::GET,
			RequestMethod::POST,
			RequestMethod::DELETE,
		]);
	}

	public function handle($sess, $res) {
		$method = $sess->http->method;
		$comment = $sess->data->comment;

		if ($method == RequestMethod::GET) {
			$commentId = $sess->queryParams()['id'];
			Assertions::assertSet($commentId);

			$model = $comment->get($commentId);

			if (!isset($model)) {
				$res->status(404)
					->cors('all')
					->error('Comment not found');
			} else {
				$res->status(200)
					->cors('all')
					->json($model->toMap());
			}
		} elseif ($method == RequestMethod::POST) {
			Assertions::assert($sess->auth->isAuthenticated());

			$selfUser = $sess->cache->user();
			Assertions::assertSet($selfUser);

			$body = $sess->jsonParams();
			$commentId = $body['id'];
			$sess->data->start();

			if (isset($commentId)) {
				$model = $comment->get($commentId);

				if (!isset($model)) {
					$res->status(404)
						->cors('all')
						->error('Comment not found');
				}

				if ($model->author->id != $selfUser->id) {
					$res->status(401)
						->cors('all')
						->error('Cannot edit someone elses comment');
				}

				$model->content = $body['content'];
				$model->editedAt = DBUtil::currentTime();

				$comment->edit($model);
			} else {
				$postId = $body['postId'];
				$content = $body['content'];
				$author = $selfUser;

				Assertions::assertSet($postId);
				Assertions::assertSet($content);

				$model = new CommentModel(
					StringUtil::newID(),
					$author,
					$postId,
					$content,
					DBUtil::currentTime(),
					null
				);

				$comment->insert($model);
				$sess->data->commit();

				$res->status(200)
					->cors('all')
					->json($model->toMap());
			}
		} elseif ($method == RequestMethod::DELETE) {
			$id = $sess->queryParams()['id'];
			Assertions::assertSet($id);

			Assertions::assert($sess->auth->isAuthenticated());
			$selfUser = $sess->cache->user();
			Assertions::assertSet($selfUser);

			$isMod = $selfUser->permissions >= PermissionLevel::MODERATOR;

			$sess->data->start();
			$model = $comment->get($id);

			if (!isset($model)) {
				$sess->data->commit();
				$res->status(404)
					->cors('all')
					->error('Unknown comment');
			}

			if ($isMod) {
				$comment->delete($model->id);
				$sess->data->commit();
				$res->status(200)
					->cors('all')
					->json($model->toMap());
			}

			if ($model->author->id != $selfUser->id) {
				$sess->data->commit();
				$res->status(401)
					->cors('all')
					->error('Cannot delete comments you did not make');
			}

			$comment->delete($model->id);
			$sess->data->commit();

			$res->status(200)
				->cors('all')
				->json($model->toMap());
		} else {
			Logger::getInstance()->fatal("Unexpected RequestMethod $method");
		}
	}

	public function validate($sess, $res) {
		$sess->routing->middlware('db');

		$method = $sess->http->method;
		$query = $sess->queryParams();

		if ($method === RequestMethod::GET) {
			if (!isset($query['id'])) {
				return HttpResult::BadRequest('No ID provided');
			}
		} elseif ($method === RequestMethod::POST) {
			$body = $sess->jsonParams();

			if (isset($body['id'])) {
				$bodyValid = HttpUtil::validateBody($body, []);

				if ($bodyValid -> isError()) {
					return $bodyValid;
				}
			}
			$sess->applyMiddleware(new AuthenticationMiddleware());
		} elseif ($method === RequestMethod::DELETE) {
			$sess->applyMiddleware(new AuthenticationMiddleware());

			if (!isset($query['id'])) {
				return HttpResult::BadRequest('No ID provided');
			}
		} else {
			Logger::getInstance()->fatal("Unknown method $method");
		}
		return HttpResult::Ok();
	}
}
