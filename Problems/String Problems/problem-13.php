<?php

// 13. Extract Filename Component from URL

// Write a PHP script to get the filename component of the following path.
// Sample path : "https://www.w3resource.com/index.php"
// Expected Output : 'index'

$url = "https://www.w3resource.com/index.php";

$file = basename($url, ".php");

echo $url."\n";
?>