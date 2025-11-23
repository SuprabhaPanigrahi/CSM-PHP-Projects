<?php

// 4. Delete Element & Normalize Keys

// $x = array(1, 2, 3, 4, 5);
// Delete an element from the above PHP array. After deleting the element, integer keys must be normalized.
// Sample Output :
// array(5) { [0]=> int(1) [1]=> int(2) [2]=> int(3) [3]=> int(4) [4]=> int(5) } 
// array(4) { [0]=> int(1) [1]=> int(2) [2]=> int(3) [3]=> int(5) }

$arr = [1,2,3,4,5];
var_dump($arr);

unset($arr[2]);
$arr = array_values($arr);

echo '';
echo "<br>";
var_dump($arr);

?>