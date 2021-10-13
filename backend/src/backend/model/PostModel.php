<?php

require_once 'Model.php';

class PostModel extends Model {
    public $id;
    public $author;
    public $content;
    public $createdAt;

    public function __construct($id, $author, $content, $createdAt) {
        $this -> id = $id;
        $this -> author = $author;
        $this -> content = $content;
        $this -> createdAt = $createdAt;
    }


    public function toJSON() {
        return [
            'id' => $this -> id,
            'author' => $this -> author -> toJSON(),
            'content' => $this -> content,
            'createdAt' => $this -> createdAt
        ];
    }
}