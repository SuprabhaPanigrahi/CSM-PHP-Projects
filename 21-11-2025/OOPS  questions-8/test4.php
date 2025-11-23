<?php

class PhotoBook {
    private int $numPages;

    // Default constructor: 16 pages
    public function __construct(int $numPages = 16) {
        $this->numPages = $numPages;
    }

    public function getNumberPages(): int {
        return $this->numPages;
    }
}

class BigPhotoBook extends PhotoBook {
    public function __construct() {
        parent::__construct(64);
    }
}

// Test class
class PhotoBookTest {

    public function main() {

        // Default photo book
        $book1 = new PhotoBook();
        echo "Default pages: " . $book1->getNumberPages() . "\n";

        // Photo book with 24 pages
        $book2 = new PhotoBook(24);
        echo "24-page book: " . $book2->getNumberPages() . "\n";

        // Big photobook (64 pages)
        $book3 = new BigPhotoBook();
        echo "Big book pages: " . $book3->getNumberPages() . "\n";
    }
}

// Run the test
$test = new PhotoBookTest();
$test->main();

?>
