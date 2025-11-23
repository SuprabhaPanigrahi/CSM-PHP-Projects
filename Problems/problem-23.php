<?php

/*
Temperature Converter

Write a function celsiusToFahrenheit($celsius) that converts temperature from Celsius to Fahrenheit. The formula is:

𝐹 =(9/5×𝐶)+32
  where F is the temperature in Fahrenheit and C is the temperature in Celsius.

Example:    

Input: 0 → Output: 32

Input: 100 → Output: 212
*/

function celsiusToFahrenheit($celsius){
  $f = (9/5*$celsius)+32;
  return $f;
}
echo celsiusToFahrenheit(100);
?>