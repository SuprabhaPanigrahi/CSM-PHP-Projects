<?php
  /*
    Problem 1 :
    WAP to accept numbers and find its sum and average.
  */
  $nums = readline("Enter  numbers: ");
  echo "You entered: $nums\n";
 // var_dump($nums);
  $numArray = explode(" ", $nums);
  // print_r($numArray);
  // echo "First number is: ".$numArray[0];
  // var_dump($numArray[0]);
  // $num1 = (int)$numArray[0];
  // var_dump($num1);
  $sum = 0;
  $count = count($numArray);
  for($i=0; $i<$count; $i++){
    $sum += (int)$numArray[$i];
  }
  $avg = $sum / $count;
  echo "Sum = $sum\n";
  echo "Average = $avg\n";
?>