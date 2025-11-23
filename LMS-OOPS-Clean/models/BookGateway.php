<?php
require_once '../core/Database.php';
require_once 'Book.php';

class BookGateway {

    public static function getAll() {
        global $conn;
        $result = $conn->query("SELECT * FROM books ORDER BY id DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function insert($title, $author) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO books (title, author) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $author);
        return $stmt->execute();
    }

    public static function update($id, $title, $author, $availability) {
        global $conn;
        $stmt = $conn->prepare("UPDATE books SET title=?, author=?, availability=? WHERE id=?");
        $stmt->bind_param("sssi", $title, $author, $availability, $id);
        return $stmt->execute();
    }

    public static function delete($id) {
        global $conn;
        return $conn->query("DELETE FROM books WHERE id=$id");
    }

    public static function getAvailable() {
        global $conn;
        $res = $conn->query("SELECT * FROM books WHERE availability='available'");
        return $res->fetch_all(MYSQLI_ASSOC);
    }
}
