<?php

namespace TLT\Model\Impl;

use TLT\Model\Model;
use TLT\Util\Data\Map;

class CommentModel extends Model {
    public $id;
    public $author;
    public $postId;
    public $content;
    public $createdAt;
    public $editedAt;

    /**
     * @param string $id The comment ID
     * @param UserModel $authorId The author
     * @param string $postId The post ID
     * @param string $content The content
     * @param int $createdAt The timestamp when this comment was created
     * @param int $editedAt The timestamp when this comment was last edited
     */
    public function __construct(
        $id,
        $author,
        $postId,
        $content,
        $createdAt,
        $editedAt
    ) {
        $this->id = $id;
        $this->author = $author;
        $this->postId = $postId;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->editedAt = $editedAt;
    }

    /**
     * @inheritDoc
     */
    public function toMap() {
        return Map::from([
            'id' => $this->id,
            'author' => $this->author->toMap(),
            'postId' => $this->postId,
            'content' => $this->content,
            'createdAt' => $this->createdAt,
            'editedAt' => $this->editedAt,
        ]);
    }
}
