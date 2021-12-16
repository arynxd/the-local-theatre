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
        parent ::__construct("post", [RequestMethod::GET, RequestMethod::POST, RequestMethod::DELETE]);
    }

    public function handle($sess, $res) {
        $method = $sess -> http -> method;

        if ($method == RequestMethod::GET) {
            $id = $sess -> queryParams()['id'];

            Assertions ::assertSet($id);

            $model = $this -> getById($sess, $id);

            if (!isset($model)) {
                $res -> sendError("Post not found", StatusCode::NOT_FOUND);
            }

            $res -> sendJSON($model -> toMap(), StatusCode::OK);
        }
        else if ($method == RequestMethod::POST) {
            Assertions ::assert($sess -> auth -> isAuthenticated());
            Assertions ::assert($this -> isModMakingRequest($sess));
            $selfUser = $sess -> cache -> user();
            Assertions ::assertSet($selfUser);

            $body = $sess -> jsonParams();
            
            $data = Map ::from([
                'authorId' => $selfUser -> id,
                'content' => $body['content'],
                'title' => $body['title'],
                'createdAt' => DBUtil ::currentTime(),
                'editedAt' => $body -> orDefault("editedAt", null)
            ]);

            if (isset($body['id'])) {
                $data['id'] = $body['id'];
                if (!$this -> updateById($data, $sess)) {
                    $res -> sendError("Post not found", StatusCode::NOT_FOUND);
                }
            }
            else { 
                $data['id'] = StringUtil ::newID();
                $this -> insertNew($data, $sess);
            }


            $id = $sess -> queryParams()['id'];
            Assertions ::assertSet($id);
            $model = $this -> getById($sess, $id);
            Assertions ::assertSet($model);

            $res -> sendJSON($model -> toMap(), StatusCode::OK);
        }
        else if ($method == RequestMethod::DELETE) {
            Assertions ::assert($sess -> auth -> isAuthenticated());
            Assertions ::assert($this -> isModMakingRequest($sess));

            $id = $sess -> queryParams()['id'];
            Assertions ::assertSet($id);

            if ($this -> deleteById($id, $sess)) {
                $res -> sendJSON("{}", StatusCode::OK);
            }
            else {
                $res -> sendError("Post not found", StatusCode::NOT_FOUND);
            }
        }
        else {
            Logger::getInstance() -> fatal("Unknown method $method");
        }
    }

    /**
     * @param string $id
     * @param Session $sess
     * @return boolean if the deletion succeeded or not
     */
    private function deleteById($id, $sess) {
        $query = "DELETE FROM post WHERE id = :id";

        $res = $sess -> db -> query($query, ['id' => $id]);

        return $res -> rowCount() > 0; // true if it was modified, false otherwise
    }

    /**
     * @param Map $data
     * @param Session $sess
     * @return boolean whether any data was updated
     */
    private function updateById($data, $sess) {
        $query = "INSERT INTO post (id, content, title, authorId, createdAt, editedAt)
                VALUES (
                    :id, :content, :title, :authorId, :createdAt, :editedAt
                )ON DUPLICATE KEY UPDATE
                    content=:content,
                    editedAt=:editedAt
                ;
        ";

        $res = $sess -> db -> query($query, [
            'id' => $data['id'],
            'title' => $data['title'],
            'authorId' => $data['authorId'],
            'content' => $data['content'],
            'createdAt' => $data['createdAt'],
            'editedAt' => $data['editedAt']
        ]);

        return $res -> rowCount() > 0;
    }


    /**
     * @param Map $data
     * @param string $authorId
     * @param Session $sess
     */
    private function insertNew($data, $sess) {
        $query = "INSERT INTO post (id, content, title, authorId, createdAt, editedAt)
                VALUES (
                    :id, :content, :title, :authorId, :createdAt, :editedAt      
                );
        ";

        $sess -> db -> query($query, [
            'id' => $data['id'],
            'title' => $data['title'],
            'authorId' => $data['authorId'],
            'content' => $data['content'],
            'createdAt' => $data['createdAt'],
            'editedAt' => $data['editedAt']
        ]);
    }

    /**
     * Gets a post by its ID
     *
     * @param Session $sess
     * @param string $id
     * @return PostModel|null The model, or null if not found
     */
    private function getById($sess, $id) {
        $st = $sess -> db -> query("SELECT * FROM post p 
                LEFT JOIN user u on u.id = p.authorId
            WHERE p.id = :id", 
            ['id' => $id]
        );

        $res = $st -> fetch(PDO::FETCH_NAMED);

        if (!$res) {
            return null;
        }

        $ids = $res['id'];

        return new PostModel(
            $ids[0],
            new UserModel(
                $ids[1],
                $res['firstName'],
                $res['lastName'],
                (int)$res['permissions'],
                (int)$res['dob'],
                (int)$res['joinDate'],
                $res['username']
            ),
            $res['postId'],
            $res['content'],
            (int)$res['createdAt'],
            (int)$res['editedAt']
        );
    }

    /**
     * @param Session $sess
     * @return boolean whether a mod is making this request
     */
    private function isModMakingRequest($sess) {
        $self = $sess -> cache -> user();
        if (!isset($self)) {
            return false;
        }
        return $self -> permissions >= PermissionLevel ::MODERATOR;
    }

    public function validateRequest($sess, $res) {
        $sess -> applyMiddleware(new DatabaseMiddleware());

        $method = $sess -> http -> method;

        if ($method == RequestMethod ::GET) {
            if (!isset($sess -> queryParams()['id'])) {
                return HttpResult ::BadRequest("No id provided");
            }
        }
        else if ($method == RequestMethod ::POST) {
            $body = $sess -> jsonParams();
            $sess -> applyMiddleware(
                new ModelValidatorMiddleware(ModelKeys::POST_MODEL, $body, "Invalid data provided"),
                new AuthenticationMiddleware()
            );

            if (!$this -> isModMakingRequest($sess)) {
                return HttpResult:: BadRequest("You do not have permission to create this post");
            }
        }
        else if ($method == RequestMethod ::DELETE) {
            $sess -> applyMiddleware(new AuthenticationMiddleware());

            if (!isset($sess -> queryParams()['id'])) {
                return HttpResult ::BadRequest("No id provided");
            }

            if (!$this -> isModMakingRequest($sess)) {
                return HttpResult:: BadRequest("You do not have permission to create this post");
            }
        }
        else {
            Logger::getInstance() -> fatal("Unknown method $method");
        }

        return HttpResult ::Ok();
    }
}