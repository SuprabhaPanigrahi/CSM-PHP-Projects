<?php

// 5. Extract Filename from Path

// Write a PHP script to extract the file name from the following string.
// Sample String : 'www.example.com/public_html/index.php'
// Expected Output : 'index.php'

$path = 'www.example.com/public_html/index.php';

$file_name = substr(strrchr($path, "/"), 1);   
echo $file_name."\n";                      


//.pdf or .docx 
//1234 - one thousand two hundred thirty four
?>