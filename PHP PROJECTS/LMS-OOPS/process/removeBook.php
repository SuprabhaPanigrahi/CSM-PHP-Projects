<?php
require_once '../core/database.php';
require_once '../models/Book.php';

if(isset($_POST['id'])){
    $id = intval($_POST['id']);
    if(Book::removeBook($conn, $id)){
        echo "Book removed successfully!";
    } else {
        echo "Failed to remove book.";
    }
}
?>
