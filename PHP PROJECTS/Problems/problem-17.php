<?php

/*
  Problem : Write a PHP program to create a function which will reverse  a number and return it.
  Note: The function should take an integer as input and return the reversed integer. and It will handle negative numbers as well. The reversed number should maintain the negative sign if the input number is negative. and The function shlould modify the argument passed by reference.
*/

function reverseNumber(&$num)
{
  $isNegative = $num < 0;

  $strNum = strval(abs($num));

  $reversedStr = strrev($strNum);

  // Convert the reversed string back to an integer
  $reversedNum = intval($reversedStr);

  // Restore the negative sign if the original number was negative
  if ($isNegative) {
    $reversedNum = -$reversedNum;
  }

  // Modify the input variable by reference
  $num = $reversedNum;

  // Return the reversed number
  return $reversedNum;
}

// Example usage
$number = -12345;
$reversed = reverseNumber($number);
echo "Reversed number: $reversed\n";   // Output: -54321
echo "Modified original variable: $number\n"; // Output: -54321
?>