<?php

// 11. Find First Character Difference Between Strings

// Write a PHP script to find the first character that is different between two strings.
// String1 : 'football'
// String2 : 'footboll'
// Expected Result : First difference between two strings at position 5: "a" vs "o"


$string1 = 'football';
$string2 = 'footboll';

$len1 = strlen($string1);
$len2 = strlen($string2);
$min_len = min($len1, $len2);

$pos = -1;

for ($i = 0; $i < $min_len; $i++) {
    if ($string1[$i] !== $string2[$i]) {
        $pos = $i + 1; // +1 to make it human-readable (1-based index)
        echo "First difference between two strings at position $pos: \"{$string1[$i]}\" vs \"{$string2[$i]}\"";
        break;
    }
}

if ($pos === -1) {
    if ($len1 !== $len2) {
        echo "Strings differ in length.";
    } else {
        echo "Strings are identical.";
    }
}
?>

