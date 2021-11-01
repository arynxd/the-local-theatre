<?php

require_once 'Model.php';

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


    public function toJSON() {
        return [
            'id' => $this -> id,
            'author' => $this -> author -> toJSON(),
            'content' => $this -> content,
            'title' => $this -> title,
            'createdAt' => $this -> createdAt
        ];
    }
}
