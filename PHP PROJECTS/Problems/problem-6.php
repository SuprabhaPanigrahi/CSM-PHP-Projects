<?php
/*
 * WAP to print multiplication table of a given range in reverse order.
 * INPUT: 2, 5
 */

$num1 = readline("enter first number :");
$num2 = readline("enter second number :");

for($i=$num1;$i<=$num2;$i++){
    for($j=10;$j>0;$j--){
        $result = $i * $j;
        echo "$i * $j = $result\n";
    }
    echo "\n";
}
?>
