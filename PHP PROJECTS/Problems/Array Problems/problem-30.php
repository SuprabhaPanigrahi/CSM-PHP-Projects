<?php

// 2. Display Colors in Specific Format

// $color = array('white', 'green', 'red'')
// Write a PHP script which will display the colors in the following way :
// Output :
// white, green, red,

// green
// red
// white


$color = array('white', 'green', 'red');

foreach ($color as $c) {
    echo "$c, ";
}

sort($color);

echo "<ul>";

foreach ($color as $y) {
    echo "<li>$y</li>";
}

echo "</ul>";

?>