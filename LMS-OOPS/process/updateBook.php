<?php
require_once '../core/database.php';
require_once '../models/Book.php';

if(isset($_POST['id'], $_POST['title'], $_POST['author'], $_POST['availability'])){
    $id = intval($_POST['id']);
    $title = $_POST['title'];
    $author = $_POST['author'];
    $availability = $_POST['availability']; // string

    if(Book::updateBook($conn, $id, $title, $author, $availability)){
        echo "Book updated successfully!";
    } else {
        echo "Failed to update book. Error: " . $conn->error;
    }
}
?>
