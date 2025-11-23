<?php

  /*
    Problem :Write a PHP program to create a function which will calculate power of a number using recursion
  */

function power($num, $pow) {
    if ($pow == 0) {
        return 1;
    } else {
        return $num * power($num, $pow - 1); 
    }
}

echo power(2, 3);

?>