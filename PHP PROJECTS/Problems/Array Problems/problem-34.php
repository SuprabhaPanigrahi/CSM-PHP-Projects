<?php

// 6. Decode JSON String

// Write a PHP script which decodes the following JSON string.
// Sample JSON code :
// {"Title": "The Cuckoos Calling",
// "Author": "Robert Galbraith",
// "Detail": {
// "Publisher": "Little Brown"
// }}
// Expected Output :
// Title : The Cuckoos Calling
// Author : Robert Galbraith
// Publisher : Little Brown


// Define a function named w3rfunction that echoes the key and value of an array element
function w3rfunction($value, $key) {
    echo "$key : $value" . "\n";
}

// Define a JSON-encoded string representing a nested associative array
$a = '{"Title": "The Cuckoos Calling",
      "Author": "Robert Galbraith",
      "Detail": { 
                  "Publisher": "Little Brown"
                 }
     }';

// Decode the JSON string into a PHP associative array
$j1 = json_decode($a, true);

// Use array_walk_recursive to apply the w3rfunction to each element in the nested array
array_walk_recursive($j1, "w3rfunction");
?>

?>