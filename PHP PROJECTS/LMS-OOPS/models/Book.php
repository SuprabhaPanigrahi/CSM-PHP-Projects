<?php
class Book {
    public static function addBook($conn, $title, $author, $availability='available'){
        $stmt = $conn->prepare("INSERT INTO books(title, author, availability) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $author, $availability);
        return $stmt->execute();
    }

    public static function getBooks($conn){
        $result = $conn->query("SELECT * FROM books");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function removeBook($conn, $id){
        $stmt = $conn->prepare("DELETE FROM books WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public static function updateBook($conn, $id, $title, $author, $availability){
        $stmt = $conn->prepare("UPDATE books SET title=?, author=?, availability=? WHERE id=?");
        $stmt->bind_param("sssi", $title, $author, $availability, $id);
        return $stmt->execute();
    }
}
?>
