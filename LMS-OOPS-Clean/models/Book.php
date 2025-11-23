<?php
class Book {
    public $id;
    public $title;
    public $author;
    public $availability;

    public function __construct($row) {
        $this->id = $row['id'];
        $this->title = $row['title'];
        $this->author = $row['author'];
        $this->availability = $row['availability'];
    }
}
