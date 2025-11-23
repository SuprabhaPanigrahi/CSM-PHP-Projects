<?php
class Member {
    public $name;
    public $memberId;
    public $borrowedBooks = [];

    public function __construct($name, $memberId) {
        $this->name = $name;
        $this->memberId = $memberId;
    }

    public function borrowBook($book) {
        if ($book->isAvailable) {
            $book->borrowBook();
            $this->borrowedBooks[] = $book;
        } else {
            echo "Cannot borrow. Book not available.\n";
        }
    }

    public function returnBook($book) {
        foreach ($this->borrowedBooks as $key => $borrowedBook) {
            if ($borrowedBook === $book) {
                $book->returnBook();
                unset($this->borrowedBooks[$key]);
                return;
            }
        }
        echo "This member did not borrow the book.\n";
    }

    public function displayMemberDetails() {
        echo "Member Name: {$this->name}, ID: {$this->memberId}\n";
        echo "Borrowed Books:\n";
        if (empty($this->borrowedBooks)) {
            echo "- None\n";
        } else {
            foreach ($this->borrowedBooks as $book) {
                echo "- {$book->title}\n";
            }
        }
    }
}
?>
