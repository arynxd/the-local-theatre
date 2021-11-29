<?php

namespace TLT\Model\Impl;

use TLT\Model\Model;
use TLT\Util\Data\Map;

class ShowModel extends Model {
    public $id;
    public $title;
    public $showDate;

    public function __construct($id, $title, $showDate) {
        $this -> id = $id;
        $this -> title = $title;
        $this -> showDate = $showDate;
    }

    public function toMap() {
        Map ::from([
            'id' => $this -> id,
            'title' => $this -> title,
            'showDate' => $this -> showDate,
        ]);
    }
}