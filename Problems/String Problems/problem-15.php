<?php

// 15. Remove Part of String from Beginning

// Write a PHP script to remove a part of a string from the beginning.
// Sample string : 'rayy@example.com'
// Expected Output : 'example.com'

$sub_string = 'rayy@';
$str = 'rayy@example.com';
if(substr($str,0,strlen($sub_string)) == $sub_string){
    $str = substr($str,strlen($sub_string));
}

echo $str . "\n";
?>