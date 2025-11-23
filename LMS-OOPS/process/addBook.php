<?php
require_once '../core/database.php';
require_once '../models/Book.php';

if(isset($_POST['title'], $_POST['author'], $_POST['availability'])){
    $title = $_POST['title'];
    $author = $_POST['author'];
    $availability = $_POST['availability']; // string: "available" or "unavailable"

    if(Book::addBook($conn, $title, $author, $availability)){
        echo "Book added successfully!";
    } else {
        echo "Failed to add book. Error: " . $conn->error;
    }
}
?>
