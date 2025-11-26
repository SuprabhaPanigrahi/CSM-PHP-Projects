<?php
require_once '../core/database.php';
require_once '../models/library.php';

$member_id = $_POST['member_id'];
$book_title = $_POST['book_title'];

$library = new Library($conn);

if ($library->issueBook($member_id, $book_title)) {
    echo "Book issued successfully!";
} else {
    echo "Error: Book might already be issued.";
}
