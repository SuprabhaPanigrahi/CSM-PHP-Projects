<?php

//WAP TO COUNT NO OF WORDS PRESENT , NO OF WORDS START WITH VOWEL, COUNT NO OF LINES


// WAP to create a new file in the current folder
$filename = "note.txt";

// Open the file for writing (create if not exists)
$file = fopen($filename, "w");

// Write text to the file
fwrite($file, "Hello world");

// Close the file
fclose($file);

// Reopen the file for reading
$file = fopen($filename, "r");

// Read the contents
$content = fread($file, filesize($filename));

// Display the contents
echo $content;

// Close the file
fclose($file);

?>
