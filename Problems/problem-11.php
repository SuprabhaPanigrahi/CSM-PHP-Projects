<?php
/*
   Problem : Electricity Bill
   Accept Current and Previous Month Meter Reading from User and Calculate the Electricity Bill as per the following criteria :
   For first 100 Units Rs. 1.20/unit  
   For next 100 Units Rs. 2.00/unit
   For next 100 Units Rs. 3.00/unit
   For units above 300 Rs. 5.00/unit  
*/

$prevReading = readline("Enter previous month meter reading: ");
$currReading = readline("Enter current month meter reading: ");

$units = $currReading - $prevReading;
$bill = 0;

if ($units <= 100) {
    $bill = $units * 1.20;
} elseif ($units <= 200) {
    $bill = (100 * 1.20) + (($units - 100) * 2.00);
} elseif ($units <= 300) {
    $bill = (100 * 1.20) + (100 * 2.00) + (($units - 200) * 3.00);
} else {
    $bill = (100 * 1.20) + (100 * 2.00) + (100 * 3.00) + (($units - 300) * 5.00);
}

echo "Units consumed: $units\n";
echo "Total Electricity Bill: ₹" . round($bill, 2) . "\n";
?>
