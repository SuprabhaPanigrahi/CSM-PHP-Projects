<?php
class Author {
    public $name;
    public $nationality;

    public function __construct($name, $nationality) {
        $this->name = $name;
        $this->nationality = $nationality;
    }
}

class Book {
    public $title;
    public $isbn;
    public $author;  // Composition

    public function __construct($title, $isbn, Author $author) {
        $this->title = $title;
        $this->isbn = $isbn;
        $this->author = $author;
    }
}

class Member {
    public $name;
    public $borrowedBooks = []; // Aggregation

    public function __construct($name) {
        $this->name = $name;
    }

    public function borrowBook(Book $b) {
        $this->borrowedBooks[] = $b;
        echo "Borrowed: $b->title<br>";
    }

    public function returnBook(Book $b) {
        foreach ($this->borrowedBooks as $key => $bk) {
            if ($bk === $b) {
                unset($this->borrowedBooks[$key]);
                echo "Returned: $b->title<br>";
                return;
            }
        }
    }
}

// Demo
$author = new Author("J.K. Rowling", "British");
$book = new Book("Harry Potter", "12345", $author);
$member = new Member("Alice");
$member->borrowBook($book);
?>
