<?php
include "../core/database.php";
include "../core/models/Book.php";

$title = $_POST['title'];

$result = Book::searchBook($conn, $title);
?>
