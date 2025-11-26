<?php
require_once 'Book.php';
require_once 'Member.php';

class Library {
    private static $conn;

    // Initialize connection for static methods
    public static function init($conn){
        self::$conn = $conn;
    }

    public static function issueBook($member_id, $book_id){
        $book = self::$conn->query("SELECT availability FROM books WHERE id=$book_id")->fetch_assoc();
        if(!$book || $book['availability'] == 0) return false;

        $stmt = self::$conn->prepare("INSERT INTO issued_books(member_id, book_id) VALUES(?, ?)");
        $stmt->bind_param("ii", $member_id, $book_id);
        $stmt->execute();

        self::$conn->query("UPDATE books SET availability=0 WHERE id=$book_id");
        return true;
    }

    public static function returnBook($member_id, $book_id){
        $stmt = self::$conn->prepare("DELETE FROM issued_books WHERE member_id=? AND book_id=?");
        $stmt->bind_param("ii", $member_id, $book_id);
        $stmt->execute();

        self::$conn->query("UPDATE books SET availability=1 WHERE id=$book_id");
        return true;
    }

    public static function getIssuedBooks(){
        return self::$conn->query("
            SELECT ib.id, m.name as member_name, b.title as book_title 
            FROM issued_books ib
            JOIN members m ON ib.member_id = m.id
            JOIN books b ON ib.book_id = b.id
        ")->fetch_all(MYSQLI_ASSOC);
    }
}
?>
