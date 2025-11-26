<?php
  /*
    Problem : WAP to create function which will return the sum of first 10 natural numbers.

    Implement two functions:
    1.Using loop
    2.Using Recursion
  */
function sum(){
  $sum = 0;
  for($i=1;$i<=10;$i++){
    $sum = $sum + $i;
  }
  return $sum;
}
echo "sum is " . sum();



function sumUsingRecursion($n) {
  if ($n == 1) {
    return 1;
  } else {
    return $n + sumUsingRecursion($n - 1);
  }
}
echo "Sum using recursion: " . sumUsingRecursion(10);

?>