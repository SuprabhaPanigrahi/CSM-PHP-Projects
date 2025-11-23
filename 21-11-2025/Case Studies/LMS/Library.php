<?php
class Library {
    public $books = [];
    public $members = [];

    public function addBook($book) {
        $this->books[] = $book;
        echo "Book added: {$book->title}\n";
    }

    public function removeBook($bookTitle) {
        foreach ($this->books as $key => $book) {
            if ($book->title === $bookTitle) {
                unset($this->books[$key]);
                echo "Book removed: $bookTitle\n";
                return;
            }
        }
        echo "Book not found: $bookTitle\n";
    }

    public function addMember($member) {
        $this->members[] = $member;
        echo "Member added: {$member->name}\n";
    }

    public function removeMember($memberId) {
        foreach ($this->members as $key => $member) {
            if ($member->memberId === $memberId) {
                unset($this->members[$key]);
                echo "Member removed: $memberId\n";
                return;
            }
        }
        echo "Member not found: $memberId\n";
    }
}
?>
