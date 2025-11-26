<?php
/*
 * WAP to find sum of all even digits in a number.
 * INPUT: 123456
 * OUTPUT: 12 (2+4+6)
 */

$num = readline("enter a number : ");
$sum = 0;

for ($i = 0; $i < strlen($num); $i++) {
    if ($num[$i] % 2 == 0) {
        $sum += $num[$i];
    }
}

echo $sum;
?>
