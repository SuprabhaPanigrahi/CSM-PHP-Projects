<?php
/*
   Problem 20 : Write a PHP program to find the sum of digits of a number.
*/

function sumOfDigits($num){
    $sum = 0;
    while($num > 0){
        $digit = $num % 10; 
        $sum += $digit;    
        $num = (int)($num / 10); 
    }
    echo "Sum is: $sum";
}

$val = 345;
sumOfDigits($val);
?>
