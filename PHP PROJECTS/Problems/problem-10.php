<?php
/*
  Problem: Calculate Compound Interest
  Suppose you invest $1000 at an annual interest rate of 5% compounded annually.
  Write a PHP program to calculate the amount after 10 years.
  Formula: A = P(1 + r/n)^(nt)
  where:
   A = the future value of the investment/loan, including interest
   P = the principal investment amount
   r = the annual interest rate (decimal)
   n = the number of times interest is compounded per year
   t = the time in years
*/

$principal = readline("Enter principal amount: ");
$interest = readline("Enter annual interest rate (in %): ");
$time = readline("Enter time period (in years): ");
$n = readline("Enter number of times interest compounded per year: ");

$iRate = $interest / 100; // convert percentage to decimal

// Calculate amount using compound interest formula
$amount = $principal * pow((1 + $iRate / $n), $n * $time);

echo "Amount after $time years: " . round($amount, 2) . "\n";
?>
