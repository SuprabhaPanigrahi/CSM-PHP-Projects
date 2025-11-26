<?php

//CREATE A BOOK STORE APPLICATION WHERE A BOOK CLASS WILL BE CREATED WITH FOLLOWING FEATURES : ID, NAME, PUBLISHER, CATEGORY, AUTHOR, PRICE, 

//CREATE AN ARRAY WHICH WILL MAINTAIN MULTIPLE BOOK INFORMATION
//PERFORM CRUD OPERATION ON THIS ARRAY - CREATE BOOK , PRINT ALL THE BOOKS, SEARCH BY NAME, UPDATE A BOOK BY ID, DELETE BY ID, SORT BY CATEGORY



// Book class definition
class Book {
    private $bookID;
    private $bookName;
    private $publisher;
    private $category;
    private $author;
    private $price;

    public function __construct($bookID, $bookName, $publisher, $category, $author, $price) {
        $this->bookID = $bookID;
        $this->bookName = $bookName;
        $this->publisher = $publisher;
        $this->category = $category;
        $this->author = $author;
        $this->price = $price;
    }

    // Getters
    public function getID() { return $this->bookID; }
    public function getName() { return $this->bookName; }
    public function getPublisher() { return $this->publisher; }
    public function getCategory() { return $this->category; }
    public function getAuthor() { return $this->author; }
    public function getPrice() { return $this->price; }

    // Setters
    public function setName($name) { $this->bookName = $name; }
    public function setPublisher($publisher) { $this->publisher = $publisher; }
    public function setCategory($category) { $this->category = $category; }
    public function setAuthor($author) { $this->author = $author; }
    public function setPrice($price) { $this->price = $price; }

    // Display a book
    public function display() {
        echo "ID: {$this->bookID}, Name: {$this->bookName}, Publisher: {$this->publisher}, Category: {$this->category}, Author: {$this->author}, Price: {$this->price}<br>";
    }
}

// BookStore class for managing books
class BookStore {
    private $books = [];

    // Create or Add a new book
    public function addBook($book) {
        $this->books[] = $book;
    }

    // Display all books
    public function displayBooks() {
        if (empty($this->books)) {
            echo "No books available.<br>";
            return;
        }
        foreach ($this->books as $book) {
            $book->display();
        }
    }

    // Search book by name
    public function searchByName($name) {
        $found = false;
        foreach ($this->books as $book) {
            if (stripos($book->getName(), $name) !== false) {
                $book->display();
                $found = true;
            }
        }
        if (!$found) echo "No book found with name: $name<br>";
    }

    // Update a book by ID
    public function updateBook($id, $newName, $newPublisher, $newCategory, $newAuthor, $newPrice) {
        foreach ($this->books as $book) {
            if ($book->getID() == $id) {
                $book->setName($newName);
                $book->setPublisher($newPublisher);
                $book->setCategory($newCategory);
                $book->setAuthor($newAuthor);
                $book->setPrice($newPrice);
                echo "Book with ID $id updated successfully.<br>";
                return;
            }
        }
        echo "Book with ID $id not found.<br>";
    }

    // Delete book by ID
    public function deleteBook($id) {
        foreach ($this->books as $index => $book) {
            if ($book->getID() == $id) {
                unset($this->books[$index]);
                $this->books = array_values($this->books); // reindex array
                echo "Book with ID $id deleted successfully.<br>";
                return;
            }
        }
        echo "Book with ID $id not found.<br>";
    }

    // Sort books by category
    public function sortByCategory() {
        usort($this->books, function($a, $b) {
            return strcmp($a->getCategory(), $b->getCategory());
        });
        echo "Books sorted by category.<br>";
    }
}

// ---------- TESTING THE APPLICATION ----------

// Create bookstore instance
$store = new BookStore();

// Add books
$store->addBook(new Book(1, "The Great Gatsby", "Scribner", "Fiction", "F. Scott Fitzgerald", 10.99));
$store->addBook(new Book(2, "A Brief History of Time", "Bantam", "Science", "Stephen Hawking", 15.50));
$store->addBook(new Book(3, "1984", "Secker & Warburg", "Fiction", "George Orwell", 12.75));

// Display all books
echo "<h3>All Books:</h3>";
$store->displayBooks();

// Search a book
echo "<h3>Search Result:</h3>";
$store->searchByName("1984");

// Update a book
echo "<h3>Update Book:</h3>";
$store->updateBook(2, "A Briefer History of Time", "Bantam Dell", "Science", "Stephen Hawking", 13.25);

// Delete a book
echo "<h3>Delete Book:</h3>";
$store->deleteBook(1);

// Sort books by category
echo "<h3>Books Sorted by Category:</h3>";
$store->sortByCategory();
$store->displayBooks();

?>
