<?php

// Get or Set information about a specific blog with the specified <id>
// GET / POST

namespace TLT\Routing\Impl;


use PDO;
use TLT\Model\Impl\PostModel;
use TLT\Model\Impl\UserModel;
use TLT\Request\Session;
use TLT\Routing\Route;
use TLT\Util\Assert\Assertions;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\HttpResult;

class PostRoute extends Route {
    public function __construct() {
        parent ::__construct("post", [RequestMethod::GET, RequestMethod::POST]);
    }

    public function handle($sess, $res) {
        $id = $sess -> queryParams()['id'];

        Assertions ::assertSet($id);

        $model = $this -> getById($sess, $id);

        if (!isset($model)) {
            $res -> sendError("Post not found", StatusCode::NOT_FOUND);
        }
        $res -> sendJSON($model -> toMap(), StatusCode::OK);
    }

    /**
     * Gets a post by its ID
     *
     * @param Session $sess
     * @param string $id
     * @return PostModel|null The model, or null if not found
     */
    private function getById($sess, $id) {
        $query = "SELECT * FROM post p 
                    LEFT JOIN user u on p.authorId = u.id 
                   WHERE p.id = :id;";

        $st = $sess -> db -> query($query, [
            'id' => $id
        ]);

        //TODO investigate a better way to handle dupe col names through PDO
        $dbData = $st -> fetch(PDO::FETCH_NAMED);

        if (!$dbData) {
            return null;
        }

        return new PostModel(
            $dbData['id'][0],
            new UserModel(
                $dbData['id'][1],
                $dbData['firstName'],
                $dbData['lastName'],
                $dbData['permissions'],
                $dbData['dob'],
                $dbData['joinDate'],
                $dbData['username']
            ),
            $dbData['content'],
            $dbData['title'],
            $dbData['createdAt']
        );
    }

    public function validateRequest($sess, $res) {
        if (!isset($sess -> queryParams()['id'])) {
            return HttpResult ::BadRequest("No id provided");
        }
        return HttpResult ::Ok();
    }
}