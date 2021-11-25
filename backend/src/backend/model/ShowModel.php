<?php

require_once 'Model.php';
require_once __DIR__ . '/../util/Map.php';

class ShowModel extends Model {
    public $id;
    public $title;
    public $showDate;
    public $imageURL;

    public function __construct($id, $title, $showDate, $imageURL) {
        $this -> id = $id;
        $this -> title = $title;
        $this -> showDate = $showDate;
        $this -> imageURL = $imageURL;
    }

    public function toMap() {
        map([
            'id' => $this -> id,
            'title' => $this -> title,
            'showDate' => $this -> showDate,
            'image' => $this -> imageURL
        ]);
    }
}