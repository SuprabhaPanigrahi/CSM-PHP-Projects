<?php
/*
Cafe GST Billing: Receipt Total & Rounding
Scenario: You’re coding the billing logic for a small café in India. Prices are stored pre-tax; the bill must show a GST-inclusive total.
Write:
function calcTotal(float $price, float $taxRate): float
Requirements:
$taxRate is a percentage (e.g., 18 for 18% GST).
Compute total = price + (price * taxRate / 100).
Return value rounded to 2 decimals using round($value, 2).
Validation:
If $price < 0 or $taxRate < 0, treat as invalid and return 0.00.
Sample I/O:
calcTotal(499.99, 18) → 589.99
calcTotal(120, 0) → 120.00
Common pitfalls: Floating-point rounding errors; forgetting % conversion.
Stretch: Add a second optional parameter $discountPercent (applied before tax).
*/

$price = readline("enter your total price :");
$taxRate = readline("enter the tax rate :");
$total;


function calcTotal($price,$taxRate){
    if($price < 0 || $taxRate < 0){
        return 0.00;
    }
    else{
        $total = $price + ($price * $taxRate / 100);
        return round($total,2);
    }
}

echo "The total price you have to pay is : " . calcTotal($price,$taxRate);

?>