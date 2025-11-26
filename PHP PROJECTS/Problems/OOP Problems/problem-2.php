<?php

// 2. Circle Class

// Write a PHP class called 'Circle' that has a radius property. Implement methods to calculate the circle's area and circumference.


class Circle{
    public $radius;

    public function __construct($radius)
    {
        $this->radius = $radius;
    }

    public function calculateArea(){
        return 2 * PI * $this->$radius;
    }
}
?>