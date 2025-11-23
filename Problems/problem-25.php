<?php

//WAP TO CREATE A FUNCTION WHICH WILL ACCEPT TWO ARRAY THEN COMPARE THEM AND RETURN THE ITEMS WHICH ARE NOT COMMON IN THOSE TWO ARRAY

function compare($arr1, $arr2) {
    $unique = [];

    // Check elements in arr1 not in arr2
    for ($i = 0; $i < count($arr1); $i++) {
        $found = false;
        for ($j = 0; $j < count($arr2); $j++) {
            if ($arr1[$i] == $arr2[$j]) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            $unique[] = $arr1[$i];
        }
    }

    // Check elements in arr2 not in arr1
    for ($i = 0; $i < count($arr2); $i++) {
        $found = false;
        for ($j = 0; $j < count($arr1); $j++) {
            if ($arr2[$i] == $arr1[$j]) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            $unique[] = $arr2[$i];
        }
    }

    // Display the result
    echo "New array (non-common elements): ";
    print_r($unique);
}

// Sample arrays
$arr1 = [5, 4, 4, 3, 2, 4];
$arr2 = [3, 8, 5, 4, 1];

// Call the function
compare($arr1, $arr2);

?>
