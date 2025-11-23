<?php


// 1. Read and display contents of a text file

// Write a PHP program to read and display the contents of a text file.

$file = fopen("note.txt","r");

$content = fread($file,filesize("note.txt"));

echo $content;

fclose($file);
?>