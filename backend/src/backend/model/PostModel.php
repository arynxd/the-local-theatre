<?php

require_once 'Model.php';
require_once __DIR__ . '/../util/Map.php';

class PostModel extends Model {
    public $id;
    public $author;
    public $content;
    public $title;
    public $createdAt;

    public function __construct($id, $author, $content, $title, $createdAt) {
        $this -> id = $id;
        $this -> author = $author;
        $this -> content = $content;
        $this -> title = $title;
        $this -> createdAt = $createdAt;
    }


    public function toMap() {
        return new Map(
            [
                'id' => $this -> id,
                'author' => $this -> author -> toJSON(),
                'content' => $this -> content,
                'title' => $this -> title,
                'createdAt' => $this -> createdAt
            ]
        );
    }
}
