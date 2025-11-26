<?php
class Book {
    public $title;
    public $author;
    public $isAvailable;

    public function __construct($title, $author) {
        $this->title = $title;
        $this->author = $author;
        $this->isAvailable = true;
    }

    public function borrowBook() {
        if ($this->isAvailable) {
            $this->isAvailable = false;
            echo "Book borrowed successfully: {$this->title}\n";
        } else {
            echo "Book is not available: {$this->title}\n";
        }
    }

    public function returnBook() {
        $this->isAvailable = true;
        echo "Book returned: {$this->title}\n";
    }

    public function displayBookDetails() {
        $status = $this->isAvailable ? "Available" : "Not Available";
        echo "Title: {$this->title}, Author: {$this->author}, Status: $status\n";
    }
}
?>
