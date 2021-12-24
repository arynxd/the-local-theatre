<?php

// List all blogs within <start> and <limit> (start may be null for unknown)
// GET

namespace TLT\Routing\Impl;


use PDO;
use TLT\Middleware\Impl\DatabaseMiddleware;
use TLT\Model\Impl\PostModel;
use TLT\Model\Impl\UserModel;
use TLT\Routing\BaseRoute;
use TLT\Util\Assert\Assertions;
use TLT\Util\Data\Map;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\HttpResult;
use TLT\Util\Log\Logger;

class PostListRoute extends BaseRoute {
    public function __construct() {
        parent ::__construct("post/list", [RequestMethod::GET]);
    }

    public function handle($sess, $res) {
        $st = $sess -> db -> query("SELECT * FROM post p 
                LEFT JOIN user u on u.id = p.authorId");

        $db = $st -> fetchAll(PDO::FETCH_NAMED);
        $posts = Map ::none();

        foreach ($db as $item) {
            $model = new PostModel(
                $item['id'][0],
                new UserModel(
                    $item['id'][1],
                    $item['firstName'],
                    $item['lastName'],
                    (int)$item['permissions'],
                    (int)$item['dob'],
                    (int)$item['joinDate'],
                    $item['username']
                ),
                $item['content'],
                $item['title'],
                (int)$item['createdAt'],
                (int)$item['editedAt']
            );
            $posts -> push($model -> toMap());
        }

        $res -> status(200)
             -> cors("all")
             -> json($posts);
    }

    public function validateRequest($sess, $res) {
        $sess -> applyMiddleware(new DatabaseMiddleware());
        return HttpResult ::Ok();
    }
}
