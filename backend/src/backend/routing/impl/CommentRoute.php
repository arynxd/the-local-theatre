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
use TLT\Util\Enum\PermissionLevel;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\HttpResult;
use TLT\Util\Log\Logger;

class CommentRoute extends BaseRoute {
    public function __construct() {
        parent::__construct("comment", [RequestMethod::GET, RequestMethod::POST, RequestMethod::DELETE]);
    }

    /**
     * @param string $id
     * @param Session $sess
     * @return CommentModel|null
     */
    private function getById($id, $sess) {
        $st = $sess -> db -> query("SELECT * FROM comment WHERE id = :id", ['id' => $id]);
        $res = Map ::from($st -> fetchAll());

        if ($res -> length() == 0) {
            return null;
        }

        return CommentModel ::fromJSON($res);
    }

    /**
     * @param string $id
     * @param Map $data
     * @param Session $sess
     */
    private function updateById($id, $data, $sess) {
        $query = "UPDATE comment
            SET column1 = value1, 
                column2 = value2,
            WHERE id = :id; 
        ";
        $sess -> db -> query($query, [

        ]);
    }
    
    public function handle($sess, $res) {
        $method = $sess -> http -> method;

        if ($method == RequestMethod::GET) {
            $commentId = $sess -> queryParams()['id'];
            Assertions ::assertSet($commentId);

            $model = $this -> getById($commentId, $sess);

            if (!isset($model)) {
                $res -> sendError("Post not found", StatusCode::NOT_FOUND);
            }
            else {
                $res -> sendJSON($model -> toMap(), StatusCode::OK);
            }
        } 
        else if ($method == RequestMethod::POST) {
            Assertions ::assert($sess -> auth -> isAuthenticated());
            $selfUser = $sess -> cache -> user();
            Assertions ::assertSet($selfUser);

            $body = $sess -> jsonParams();

            if (isset($body['id'])) {
                $this -> updateById($body['id'], $body, $sess);
            }
            else { 
                // insert new post
            }
            // assert authenticated
            // get user id
            // insert the new comment
        } 
        else if ($method == RequestMethod::DELETE) {
            // assert authenticated
            // check if sending user can delete (mod or own comment)
            // delete
        } 
        else {
            Logger ::getInstance() -> fatal("Unexpected RequestMethod $method");
        }
        $res -> sendJSON(Map::from(['ok' => true]));
    }

    public function validateRequest($sess, $res)
    {
        $sess->applyMiddleware(new DatabaseMiddleware());

        $method = $sess->http->method;
        $query = $sess -> queryParams();
        $body = $sess -> jsonParams();

        if ($method === RequestMethod::GET) {
            if (!isset($query['id'])) {
                return HttpResult::BadRequest("No ID provided");
            }
        } 
        else if ($method === RequestMethod::POST) {
            $sess -> applyMiddleware(
                new ModelValidatorMiddleware(ModelKeys::COMMENT_MODEL, $body, "Invalid data provided"),
                new AuthenticationMiddleware()
            );
            $selfUser = $sess -> cache -> user();

            Assertions::assertSet($selfUser); // the self user should be set if the middleware passes

            if ($selfUser -> permissions < PermissionLevel::USER) {
                return HttpResult:: BadRequest("You do not have permission to post this comment");
            }
        } 
        else if ($method === RequestMethod::DELETE) {
            $res -> sendError("Unimplemented method " . $method);
        } 
        else {
            Logger ::getInstance() -> fatal("Unknown method $method");
        }
        return HttpResult::Ok();
    }
}
