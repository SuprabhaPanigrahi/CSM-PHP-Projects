
<?php
/*
 * WAP accept a number and check whether it is prime or not.
 * 
 */
$num = readline("enter a number :");
$count = 0;
for($i=2;$i<$num;$i++){
    if($num % $i == 0){
        $count++;
    }
}
if($count>0){
    echo "not prime";
}
else{
    echo "prime";
}
?>
