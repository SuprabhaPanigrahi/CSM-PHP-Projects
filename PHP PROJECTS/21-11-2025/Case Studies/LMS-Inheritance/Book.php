<?php
require_once "Item.php";

class Book extends Item {
    public $author;

    public function __construct($title, $publisher, $author) {
        parent::__construct($title, $publisher);
        $this->author = $author;
    }

    public function displayDetails() {
        parent::displayDetails();
        echo "Author: {$this->author}\n";
    }
}
?>
