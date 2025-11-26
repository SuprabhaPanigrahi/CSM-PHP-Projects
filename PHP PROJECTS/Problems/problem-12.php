<?php
/*
 * Problem: Discount On Sales
 * In Dewali Sale , a shop is giving discount on the sales of items as per the following rules:
 * If the cost of purchased items is more than 1000 then a discount of 10% is given on the total cost.
 * If the cost of purchased items is more than 500 but less than or equal to 1000 then a discount of 5% is given on the total cost.
 * If the cost of purchased items is less than or equal to 500 then no discount is given.
 * Write a program to accept the cost of purchased items and calculate the discount and net amount to be paid by the customer.
 */

$cost = readline("enter your purchase cost:");
$discount;
$amountPay;
if ($cost > 1000) {
    $discount = $cost * 0.10;
    $amountPay = $cost - $discount;
    echo "amount to pay : $amountPay";
} else if ($cost > 500 && $cost <= 1000) {
    $discount = $cost * 0.05;
    $amountPay = $cost - $discount;
    echo "amount to pay : $amountPay";

}
if ($cost <= 500) {
    $amountPay = $cost;
    echo "amount to pay : $amountPay";

}
?>