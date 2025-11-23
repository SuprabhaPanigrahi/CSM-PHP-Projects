<?php
/*
  Problem 2: Write a PHP script to find the largest of three numbers.
  Example: Input: 10, 20, 15
           Output: 20

*/
$num1 = readline("enter the first number :");
$num2 = readline("enter second number :");
$num3 = readline("enter third number :");

if($num1>$num2 && $num1>$num3){
  echo "$num1 is largest";
}
else if($num2>$num1 && $num2>$num3){
  echo "$num2 is largest";
}
else{
  echo "$num3 is largest";
}
?>