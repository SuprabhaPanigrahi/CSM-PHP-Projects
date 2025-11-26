<?php
/*
 * Problem 14: Longest Collatz sequence
 * Write a program to find the number of steps it takes to reach 1 for a given positive integer n.
 * Example: n = 6 → sequence: 6 → 3 → 10 → 5 → 16 → 8 → 4 → 2 → 1 → steps = 8
 */

$n = readline("Enter a positive integer: ");
$steps = 0;

while ($n != 1) {
    if ($n % 2 == 0) {
        $n = $n / 2;
    } else {
        $n = 3 * $n + 1;
    }
    $steps++;
}

echo "Number of steps to reach 1: $steps\n";
?>
