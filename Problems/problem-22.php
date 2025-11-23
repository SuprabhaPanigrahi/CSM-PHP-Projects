<?php
/*
Write a function isPrime($number) that returns true if the number is prime, otherwise false.

*/

function isPrime($num){
    $count = 2;
    for($i=2;$i<$num;$i++){
        if($num % $i == 0){
            $count++;
        }
    }
    if($count>2){
        echo "not prime";
    }
    else{
        echo "prime";
    }
}
isPrime(12);
?>