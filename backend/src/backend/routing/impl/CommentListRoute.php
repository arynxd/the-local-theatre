<?php

// List all comments within <start> and <limit> for a blog with the matching <blog_id> (start may be null for unknown)
// GET

namespace TLT\Routing\Impl;

use PDO;
use TLT\Middleware\Impl\DatabaseMiddleware;
use TLT\Model\Impl\CommentModel;
use TLT\Model\Impl\PostModel;
use TLT\Model\Impl\UserModel;
use TLT\Routing\BaseRoute;
use TLT\Util\Assert\Assertions;
use TLT\Util\Data\Map;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\HttpResult;
use TLT\Util\StringUtil;

class CommentListRoute extends BaseRoute {
    public function __construct() {
        parent ::__construct("comment/list", [RequestMethod::GET]);
    }

    public function handle($sess, $res) {
        $id = $sess -> queryParams()['id'];
        Assertions::assertSet($id);

        $st = $sess -> db -> query("SELECT * FROM comment c 
                LEFT JOIN user u on u.id = c.authorId
            WHERE c.postId = :id", 
            ['id' => $id]
        );

        $db = $st -> fetchAll(PDO::FETCH_NAMED);

        $items = Map ::none();

        foreach ($db as $item) {
            $model = new CommentModel(
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
                $item['postId'],
                $item['content'],
                (int)$item['createdAt'],
                (int)$item['editedAt']
            );
            $items -> push($model -> toMap());
        }

        $res -> status(200)
             -> cors("all")
             -> json([
                 'comments' => $items,
                 'count' => $item -> length()
             ]);
    }

    public function validateRequest($sess, $res) {
        if (!isset($sess -> queryParams()['id'])) {
            return HttpResult::BadRequest("No ID provided");
        }
        $sess -> applyMiddleware(new DatabaseMiddleware());
        return HttpResult ::Ok();
    }
}
