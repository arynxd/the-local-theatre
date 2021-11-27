<?php

// List all blogs within <start> and <limit> (start may be null for unknown)
// GET

require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . '/../../model/PostModel.php';
require_once __DIR__ . '/../../util/identifier.php';
require_once __DIR__ . '/../../util/Map.php';
require_once __DIR__ . '/../../util/constant/Constants.php';

class PostListRoute extends Route {
    public function __construct() {
        parent ::__construct("post/list", [RequestMethod::GET]);
    }

    public function handle($sess, $res) {
        $query = "SELECT * FROM post p LEFT JOIN user u on p.authorId = u.id;";
        $st = $sess -> database -> query($query);

        $dbData = $st -> fetchAll(PDO::FETCH_NAMED);
        $posts = new Map();

        foreach ($dbData as $item) {
            print_r($item);
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
        return Ok();
    }
}
