<?php

  # Accept list  from user as Input 
  echo "Enter an Array";
  $nums = readline();

  # Convert input string into array
  $nums = explode(",",$nums);

  # Check the $nums is an array or not

  if(is_array($nums))
  {
      echo "\n Before Reverse";
      print_r($nums);
      $a = revFuncToNotChangOriginalArray($nums);
      echo "\n After Reverse without changing original array";
      print_r($a);
      revFuncToChangOriginalArray($nums);
      echo "\nAfter Reverse with Changing original array";
      print_r($nums);
      echo "\n";
      echo "\n Now original array is changed";
      print_r($nums);
  }else {
    echo "\n Is not valid array";
  }


  function revFuncToChangOriginalArray(&$arr)
  {
      $arr = array_reverse($arr); 
  }
  function revFuncToNotChangOriginalArray($arr)
  {
      $arr = array_reverse($arr); 
      return $arr;
  }

  function array_reverse($arr){
       //Write array reverse logic here
  }