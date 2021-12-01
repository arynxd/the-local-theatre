<?php

// List all blogs within <start> and <limit> (start may be null for unknown)
// GET

namespace TLT\Routing\Impl;


use PDO;
use TLT\Middleware\Impl\DatabaseMiddleware;
use TLT\Model\Impl\PostModel;
use TLT\Model\Impl\UserModel;
use TLT\Routing\BaseRoute;
use TLT\Util\Data\Map;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\HttpResult;

class PostListRoute extends BaseRoute {
    public function __construct() {
        parent ::__construct("post/list", [RequestMethod::GET]);
    }

    public function handle($sess, $res) {
        $query = "SELECT * FROM post p LEFT JOIN user u on p.authorId = u.id;";
        $st = $sess -> db -> query($query);

        $dbData = $st -> fetchAll(PDO::FETCH_NAMED);
        $posts = Map ::none();

        foreach ($dbData as $item) {
            $model = new PostModel(
                $item['id'][0],
                new UserModel(
                    $item['id'][1],
                    $item['firstName'],
                    $item['lastName'],
                    $item['permissions'],
                    $item['dob'],
                    $item['joinDate'],
                    $item['username']
                ),
                $item['content'],
                $item['title'],
                $item['createdAt']
            );
            $posts -> push($model -> toMap());
        }

        $res -> sendJSON($posts, StatusCode::OK);
    }

    public function validateRequest($sess, $res) {
        $sess -> applyMiddleware(new DatabaseMiddleware());
        return HttpResult ::Ok();
    }
}
