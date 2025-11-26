<?php
session_start();

// Collect form data
$bookName   = $_POST['bookname'] ?? '';
$author     = $_POST['author'] ?? '';
$publisher  = $_POST['publisher'] ?? '';
$category   = $_POST['category'] ?? '';
$available_as = $_POST['available_as'] ?? [];
$price      = $_POST['price'] ?? '';
$review     = $_POST['review'] ?? '';
$rating     = $_POST['rating'] ?? '';
$bookImage  = '';

// Handle checkboxes
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

// Create associative array
$book = [
    'bookname' => $bookName,
    'author' => $author,
    'publisher' => $publisher,
    'category' => $category,
    'available_as' => $available_as,
    'price' => $price,
    'review' => $review,
    'rating' => $rating,
    'bookImage' => $bookImage
];

// Store in session
if (!isset($_SESSION['books'])) {
    $_SESSION['books'] = [];
}

$_SESSION['books'][] = $book;

// Redirect
header('Location: ../pages/view-books.php');
exit;
?>
