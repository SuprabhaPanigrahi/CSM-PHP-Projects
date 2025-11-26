<?php

// Rectangle Class
class Rectangle {
    public $length;
    public $width;

    public function __construct($length, $width) {
        $this->length = $length;
        $this->width = $width;
    }

    public function calculateArea() {
        return $this->length * $this->width;
    }

    public function calculatePerimeter() {
        return 2 * ($this->length + $this->width);
    }
}

// Example usage
$first = new Rectangle(4, 6);

echo "Area: " . $first->calculateArea() . "<br>";
echo "Perimeter: " . $first->calculatePerimeter();

?>
