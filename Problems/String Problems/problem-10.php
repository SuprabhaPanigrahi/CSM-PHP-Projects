<?php

// 10. Replace First Occurrence of "the"

// Write a PHP script to replace the first 'the' of the following string with 'That'.
// Sample date : 'the quick brown fox jumps over the lazy dog.'
// Expected Result : That quick brown fox jumps over the lazy dog.


$data ='the quick brown fox jumps over the lazy dog.';

echo preg_replace('/the/', 'That', $data, 1) . "\n";
?>