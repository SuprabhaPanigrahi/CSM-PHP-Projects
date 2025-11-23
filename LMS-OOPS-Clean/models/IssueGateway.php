<?php
require_once '../core/Database.php';

class IssueGateway {

    public static function issue($member_id, $book_id) {
        global $conn;

        // Mark book unavailable
        $conn->query("UPDATE books SET availability='unavailable' WHERE id=$book_id");

        // Insert issued book
        $stmt = $conn->prepare("INSERT INTO issued_books (member_id, book_id) VALUES (?, ?)");
        $stmt->bind_param("si", $member_id, $book_id);
        return $stmt->execute();
    }

    public static function returnBook($member_id, $book_id) {
        global $conn;

        // Delete issue record
        $conn->query("DELETE FROM issued_books WHERE member_id='$member_id' AND book_id=$book_id");

        // Mark book available
        return $conn->query("UPDATE books SET availability='available' WHERE id=$book_id");
    }

    public static function getIssued() {
        global $conn;
        $query = "
            SELECT issued_books.*, books.title, books.author 
            FROM issued_books 
            JOIN books ON books.id = issued_books.book_id
            ORDER BY issued_at DESC
        ";

        $res = $conn->query($query);
        return $res->fetch_all(MYSQLI_ASSOC);
    }
}
