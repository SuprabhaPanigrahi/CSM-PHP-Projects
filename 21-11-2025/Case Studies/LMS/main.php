<?php
require 'Book.php';
require 'Member.php';
require 'Library.php';

$library = new Library();

// Create Books
$book1 = new Book("Harry Potter", "J.K. Rowling");
$book2 = new Book("The Hobbit", "J.R.R. Tolkien");

// Add Books
$library->addBook($book1);
$library->addBook($book2);

// Create Members
$member1 = new Member("John Doe", 101);
$member2 = new Member("Jane Smith", 102);

// Add Members
$library->addMember($member1);
$library->addMember($member2);

// Borrow a Book
$member1->borrowBook($book1);

// Display Details
echo "\n--- Book Details ---\n";
$book1->displayBookDetails();
$book2->displayBookDetails();

echo "\n--- Member Details ---\n";
$member1->displayMemberDetails();
$member2->displayMemberDetails();

// Return Book
$member1->returnBook($book1);
?>
