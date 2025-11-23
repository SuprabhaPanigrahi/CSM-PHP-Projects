<?php 
/*
 * WAP a accept two numbers from user and print all even numbers between them.
 */
$num1 = readline("enter first number :");
$num2 = readline("enter second number :");

for($i=$num1;$i<=$num2;$i++){
    if($i % 2 == 0){
        echo "$i\n";
    }
}
?>
