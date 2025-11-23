<?php

// 14. Print Next Character with Wrap-Around

// Write a PHP script to print the next character of a specific character.
// Sample character : 'a'
// Expected Output : 'b'
// Sample character : 'z'
// Expected Output : 'a'


$cha = 'a';
$next_cha = ++$cha; 
//The following if condition prevent you to go beyond 'z' or 'Z' and will reset to 'a' or 'A'.
if (strlen($next_cha) > 1) 
{
 $next_cha = $next_cha[0];
 }
echo $next_cha."\n";
?>
