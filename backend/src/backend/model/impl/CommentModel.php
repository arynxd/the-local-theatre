<?php

namespace TLT\Model\Impl;

use TLT\Model\Model;
use TLT\Util\Data\Map;

class CommentModel extends Model {
    public $id;
    public $authorId;
    public $postId;
    public $content;
    public $createdAt;
    public $editedAt;

    /**
     * @param string $id The comment ID
     * @param string $authorId The author ID
     * @param string $postId The post ID
     * @param string $content The content
     * @param int $createdAt The timestamp when this comment was created
     * @param int $editedAt The timestamp when this comment was last edited
     */
    public function __construct($id, $authorId, $postId, $content, $createdAt, $editedAt) {
        $this -> id = $id;
        $this -> authorId = $authorId;
        $this -> postId = $postId;
        $this -> content = $content;
        $this -> createdAt = $createdAt;
        $this -> editedAt = $editedAt;
    }


    /**
     * @inheritDoc
     */
    public function toMap() {
        return Map::from([
            'id' => $this -> id,
            'authorId' => $this -> authorId,
            'postId' => $this -> postId,
            'content' => $this -> content,
            'createdAt' => $this -> createdAt,
            'editedAt'=>  $this -> editedAt
        ]);
    }
}