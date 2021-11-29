<?php

namespace TLT\Model\Impl;

use TLT\Model\Model;
use TLT\Util\Data\Map;

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
        return Map ::from([
            'id' => $this -> id,
            'author' => $this -> author -> toMap(),
            'content' => $this -> content,
            'title' => $this -> title,
            'createdAt' => $this -> createdAt
        ]);
    }
}
