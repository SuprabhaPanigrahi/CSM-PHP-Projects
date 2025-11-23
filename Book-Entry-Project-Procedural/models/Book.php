<?php
class Book
{
    private $bookname;
    private $author;
    private $publisher;
    private $category;
    private $available_as;
    private $price;
    private $review;
    private $bookImage;
    private $bookPreview;
    private $rating;

    public function __construct($bookname = '', $author = '', $publisher = '', $category = '', $available_as = '', $price = '', $review = '', $bookImage = '', $bookPreview = '', $rating = '')
    {
        $this->bookname = $bookname;
        $this->author = $author;
        $this->publisher = $publisher;
        $this->category = $category;
        $this->available_as = $available_as;
        $this->price = $price;
        $this->review = $review;
        $this->bookImage = $bookImage;
        $this->bookPreview = $bookPreview;
        $this->rating = $rating;
    }

    // Getters
    public function getBookname() { return $this->bookname; }
    public function getAuthor() { return $this->author; }
    public function getPublisher() { return $this->publisher; }
    public function getCategory() { return $this->category; }
    public function getAvailableAs() { return $this->available_as; }
    public function getPrice() { return $this->price; }
    public function getReview() { return $this->review; }
    public function getBookImage() { return $this->bookImage; }
    public function getBookPreview() { return $this->bookPreview; }
    public function getRating() { return $this->rating; }

    // Setters
    public function setBookname($bookname) { $this->bookname = $bookname; }
    public function setAuthor($author) { $this->author = $author; }
    public function setPublisher($publisher) { $this->publisher = $publisher; }
    public function setCategory($category) { $this->category = $category; }
    public function setAvailableAs($available_as) { $this->available_as = $available_as; }
    public function setPrice($price) { $this->price = $price; }
    public function setReview($review) { $this->review = $review; }
    public function setImage($bookImage) { $this->bookImage = $bookImage; }
    public function setBookPreview($bookPreview) { $this->bookPreview = $bookPreview; }
    public function setRating($rating) { $this->rating = $rating; }



    public function  __toString()
    {
        return "Book Details:<br>" .
            "Book Name: " . $this->bookname . "<br>" .
            "Author: " . $this->author . "<br>" .
            "Publisher: " . $this->publisher . "<br>" .
            "Category: " . $this->category . "<br>" .
            "Available As: " . $this->available_as . "<br>" .
            "Price: " . $this->price . "<br>" .
            "Book Image: ". $this->bookImage . "<br>" .
            "Review: " . $this->review . "<br>" .
            "bookPreview: " . $this->bookPreview . "<br>" .
            "rating: " . $this->rating . "<br>";
    }
}
?>