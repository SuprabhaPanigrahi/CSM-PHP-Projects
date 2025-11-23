<?php

// 7. Insert New Array Item by Position

// Write a PHP script that inserts a new item in an array in any position.
// Expected Output :
// Original array : 
// 1 2 3 4 5 
// After inserting '$' the array is :
// 1 2 3 $ 4 5


$original = array( '1', '2', '3', '4', '5' );
echo 'Original array : ' . "\n";
foreach ($original as $x) {
    echo "$x ";
}
$inserted = '$';
array_splice($original, 3, 0, $inserted);
echo " \n After inserting '$' the array is : " . "\n";

foreach ($original as $x) {
    echo "$x ";
}

echo "\n";
?>
