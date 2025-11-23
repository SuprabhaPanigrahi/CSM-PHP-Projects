<?php

// 3. Check if String Contains Another

// Write a PHP script to check whether a string contains a specific string?
// Sample string : 'The quick brown fox jumps over the lazy dog.'
// Check whether the said string contains the string 'jumps'.


$sample = 'The quick brown fox jumps over the lazy dog.';
$search = "jumps";

if (str_contains($sample, $search)) {
    echo "Found";
} else {
    echo "Not found";
}
?>