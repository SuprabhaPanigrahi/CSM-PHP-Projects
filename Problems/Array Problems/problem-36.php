<?php

// 8. Sort Associative Array by Key and Value

// Write a PHP script to sort the following associative array :
// array("Sophia"=>"31","Jacob"=>"41","William"=>"39","Ramesh"=>"40") in
// a) ascending order sort by value
// b) ascending order sort by Key
// c) descending order sorting by Value
// d) descending order sorting by Key

$arr = [
    "Sophia" => "31",
    "Jacob" => "41",
    "William" => "39",
    "Ramesh" => "40"
];

echo "Ascending order sort by value" . "<br>";
asort($arr);
foreach ($arr as $Name => $Age) {
    echo "The age of $Name is $Age" . "<br>";
}


echo "Ascending order sort by key" . "<br>";
ksort($arr);
foreach ($arr as $Name => $Age) {
    echo "The age of $Name is $Age" . "<br>";
}


echo "Descending order sort by value" . "<br>";
arsort($arr);
foreach ($arr as $Name => $Age) {
    echo "The age of $Name is $Age" . "<br>";
}


echo "Descending order sort by key" . "<br>";
krsort($arr);
foreach ($arr as $Name => $Age) {
    echo "The age of $Name is $Age" . "<br>";
}


?>