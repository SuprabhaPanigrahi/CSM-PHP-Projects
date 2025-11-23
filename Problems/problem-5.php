<?php
/*
 * WAP to print multiplication table of a given range.
 * INPUT: 2, 5
 * OUTPUT:
 * 2 x 1 = 2
 * 2 x 2 = 4
 * ...
 * 5 x 10 = 50
 */

$num1 = readline("enter first number :");
$num2 = readline("enter second number :");

for($i=$num1;$i<=$num2;$i++){
    for($j=1;$j<=10;$j++){
        $result = $i * $j;
        echo "$i * $j = $result\n";
    }
    echo "\n";
}
?>
