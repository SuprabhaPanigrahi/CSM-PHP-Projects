<?php
require_once "Item.php";

class Magazine extends Item {
    public $issueNumber;

    public function __construct($title, $publisher, $issueNumber) {
        parent::__construct($title, $publisher);
        $this->issueNumber = $issueNumber;
    }

    public function displayDetails() {
        parent::displayDetails();
        echo "Issue Number: {$this->issueNumber}\n";
    }
}
?>
