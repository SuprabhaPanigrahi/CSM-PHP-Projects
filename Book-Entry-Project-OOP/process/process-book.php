<?php
session_start();
require_once '../models/Book.php';

// Collect submitted data
$bookName   = $_POST['bookname'] ?? '';
$author     = $_POST['author'] ?? '';
$publisher  = $_POST['publisher'] ?? '';
$category   = $_POST['category'] ?? '';
$available_as = $_POST['available_as'] ?? [];
$price      = $_POST['price'] ?? '';
$review     = $_POST['review'] ?? '';
$rating     = $_POST['rating'] ?? '';
$bookImage  = '';

// Handle checkbox array
if (is_array($available_as)) {
    $available_as = implode(', ', $available_as);
}

// Handle image upload
if (!empty($_FILES['bookImage']['name'])) {
    $uploadDir = '../uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $fileName = time() . '_' . basename($_FILES['bookImage']['name']);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['bookImage']['tmp_name'], $targetFile)) {
        $bookImage = $fileName;
    }
}

// Create a Book object
$book = new Book($bookName, $author, $publisher, $category, $available_as, $price, $review, $bookImage, '', $rating);

// Save in session
if (!isset($_SESSION['books'])) {
    $_SESSION['books'] = [];
}

$_SESSION['books'][] = serialize($book);

// Redirect
header('Location: ../pages/view-books.php');
exit;
?>
