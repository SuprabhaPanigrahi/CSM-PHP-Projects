<?php
class Item {
    public $title;
    public $publisher;
    public $isAvailable;

    public function __construct($title, $publisher) {
        $this->title = $title;
        $this->publisher = $publisher;
        $this->isAvailable = true;
    }

    public function borrowItem() {
        if ($this->isAvailable) {
            $this->isAvailable = false;
            echo "Item borrowed: {$this->title}\n";
        } else {
            echo "Item NOT available: {$this->title}\n";
        }
    }

    public function returnItem() {
        $this->isAvailable = true;
        echo "Item returned: {$this->title}\n";
    }

    public function displayDetails() {
        $status = $this->isAvailable ? "Available" : "Not Available";
        echo "Title: {$this->title}, Publisher: {$this->publisher}, Status: $status\n";
    }
}
?>
