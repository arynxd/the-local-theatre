<?php

// Get or Set a comment from the specified <user_id> on a given <blog_id>, passing in the current <timestamp> as an epoch
// GET / PUT

namespace TLT\Routing\Impl;

use TLT\Middleware\Impl\AuthenticationMiddleware;
use TLT\Middleware\Impl\DatabaseMiddleware;
use TLT\Routing\BaseRoute;
use TLT\Util\Assert\AssertionException;
use TLT\Util\Assert\Assertions;
use TLT\Util\Enum\PermissionLevel;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\HttpResult;
use TLT\Util\Log\Logger;

class CommentRoute extends BaseRoute
{
    public function __construct()
    {
        parent::__construct("comment", [RequestMethod::GET, RequestMethod::POST, RequestMethod::DELETE]);
    }

    public function handle($sess, $res)
    {
        $commentId = $sess->queryParams()['id'];
        Assertions::assertSet($commentId);

        $method = $sess -> http -> method;

        if ($method == RequestMethod::GET) {
            // return by id commentId
        } 
        else if ($method == RequestMethod::POST) {
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
    }

    public function validateRequest($sess, $res)
    {
        $sess->applyMiddleware(new DatabaseMiddleware());

        $method = $sess->http->method;
        $query = $sess->queryParams();
        $body = $sess->jsonParams();

        if ($method === RequestMethod::GET) {
            if (!isset($query['id'])) {
                return HttpResult::BadRequest("No ID provided");
            }
        } 
        else if ($method === RequestMethod::POST) {
            $postId = $body['id'];

            if (!isset($postId)) {  // do this before the middleware to save potential DB calls
                return HttpResult::BadRequest("No ID provided");
            }
            
            $sess -> applyMiddleware(new AuthenticationMiddleware());
            $selfUser = $sess -> cache -> user();

            Assertions::assertSet($selfUser); // the self user should be set if the middleware passes

            if ($selfUser -> permissions < PermissionLevel::USER) {
                return HttpResult:: BadRequest("You do not have permission to post this comment");
            }

            return HttpResult ::Ok();
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
