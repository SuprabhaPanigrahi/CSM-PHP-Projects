<?php

//WAP ACCEPT AN ARRAY AND COUNT FREQUENCY OF ITEMS


// Sample array
$array = ['apple', 'banana', 'apple', 'orange', 'banana', 'apple'];

// Count frequency of items
$frequency = array_count_values($array);

// Display the frequency
foreach ($frequency as $item => $count) {
    echo $item . " => " . $count . "\n";
}

?>