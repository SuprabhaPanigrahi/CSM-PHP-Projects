<?php
/*
Write a function decimalToBinary($number) that converts a decimal number to its binary equivalent.
*/

function decimalToBinary($number) {
    // Validate input
    if (!is_int($number) || $number < 0) {
        return "Invalid input. Please enter a non-negative integer.";
    }

    // Convert to binary using built-in function
    return decbin($number);
}

// Example usage
$number = (int) readline("Enter a non-negative integer: ");
echo "Binary equivalent of $number is: " . decimalToBinary($number) . "\n";
?>
