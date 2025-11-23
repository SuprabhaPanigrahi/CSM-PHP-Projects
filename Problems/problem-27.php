<?php
//declare(strict_types=1);
#Create an array of numbers
$numbers = array(1, 2, 3, 4, 5,2.5);
var_dump($numbers);
print_r($numbers);
echo "\n";
echo implode(" ",$numbers);
# WAP to check if each item of the arry is int or not , if integer the print the sum of all integers else print "Array contains non integer values"

function checkArrayIsNumeric($arr){
    $flag = true;
    for($i=0;$i<count($arr);$i++){
        if( !is_float($arr[$i]) ){
            $flag = false;
            break;
        }
    }
    return $flag;
}

echo "\n";
echo "The given array is NUMERIC : ".checkArrayIsNumeric($numbers);

if(checkArrayIsNumeric($numbers)){    
    $sum = 0;
    for($i=0;$i<count($numbers);$i++){
        $sum += $numbers[$i];
    }
    echo "\n The sum of all integers in the array is : ".$sum;
} else {
    echo "\n Array contains non integer values";
}
