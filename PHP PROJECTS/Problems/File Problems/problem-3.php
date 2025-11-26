<?php

// 2. Check if the file exists

// Write a PHP function that takes a file path as input and checks if the file exists.


function exist($path){
    if(file_exists($path)){
        echo "file exists at path: " . $path;
    }
    else{
        echo "file does not exist at path :" . $path;
    }

    $path = "note.txt";
    exist($path);
}

?>