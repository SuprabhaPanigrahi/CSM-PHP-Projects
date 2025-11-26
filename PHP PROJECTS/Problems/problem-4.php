<?php
/*
 * WAP accept a number from user and check whether it is  
 * palindrome or not.
 * INPUT: 121
 * OUTPUT: 121 is a palindrome number.
 * INPUT: 123
 * OUTPUT: 123 is not a palindrome number.
 */

$num = readline("enter a number to check if its palindrome or not : ");
$revNum = "";

for ($i = strlen($num) - 1; $i >= 0; $i--) {
    $revNum .= $num[$i];
}

// Check if palindrome
if ($num === $revNum) {
    echo "$num is a palindrome number.";
} else {
    echo "$num is not a palindrome number.";
}
?>
