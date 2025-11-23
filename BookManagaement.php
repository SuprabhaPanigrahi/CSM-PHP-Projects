<?php

// WAP to create a class Book with properties name, price, publisher, category, author.
// Include constructor, getter, and setter methods.

class Book {
    // Properties
    private $name;
    private $price;
    private $publisher;
    private $category;
    private $author;

    // Constructor
    public function __construct($name, $price, $publisher, $category, $author) {
        $this->name = $name;
        $this->price = $price;
        $this->publisher = $publisher;
        $this->category = $category;
        $this->author = $author;
    }

    // Getter and Setter for name
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    // Getter and Setter for price
    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    // Getter and Setter for publisher
    public function getPublisher() {
        return $this->publisher;
    }

    public function setPublisher($publisher) {
        $this->publisher = $publisher;
    }

    // Getter and Setter for category
    public function getCategory() {
        return $this->category;
    }

    public function setCategory($category) {
        $this->category = $category;
    }

    // Getter and Setter for author
    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor($author) {
        $this->author = $author;
    }

    // Method to display book details
    public function displayDetails() {
        echo "Book Details:<br>";
        echo "Name: " . $this->name . "<br>";
        echo "Price: $" . $this->price . "<br>";
        echo "Publisher: " . $this->publisher . "<br>";
        echo "Category: " . $this->category . "<br>";
        echo "Author: " . $this->author . "<br>";
    }
}

// Example usage
$book1 = new Book("The Great Gatsby", 15.99, "Scribner", "Fiction", "F. Scott Fitzgerald");
$book1->displayDetails();

?>
