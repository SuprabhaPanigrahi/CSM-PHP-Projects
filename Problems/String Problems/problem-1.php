<?php

// 1. Transform String Case Conversions

// Write a PHP script to : -
// a) transform a string all uppercase letters.
// b) transform a string all lowercase letters.
// c) make a string's first character uppercase.
// d) make a string's first character of all the words uppercase.

$str = "Situ";
$str1 = "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quos, commodi.";

echo strtoupper($str . "\n");
echo strtolower($str . "\n");
echo ucfirst($str. "\n");
echo ucwords($str1 . "\n");

?>