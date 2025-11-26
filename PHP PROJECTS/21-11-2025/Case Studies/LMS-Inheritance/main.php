<?php
require 'Book.php';
require 'Magazine.php';
require 'Member.php';
require 'Library.php';

$library = new Library();

// Create Items
$book1 = new Book("Harry Potter", "Bloomsbury", "J.K. Rowling");
$mag1  = new Magazine("Time Magazine", "Time Inc", 202);

// Add Items
$library->addItem($book1);
$library->addItem($mag1);

// Create Members
$member1 = new Member("Alice", 101);

// Add Members
$library->addMember($member1);

// Borrow items
$member1->borrowItem($book1);
$member1->borrowItem($mag1);

// Display item details
echo "\n--- Item Details ---\n";
$book1->displayDetails();
$mag1->displayDetails();

// Display member details
echo "\n--- Member Details ---\n";
$member1->displayMemberDetails();

// Return an item
$member1->returnItem($book1);

?>
