<?php
/*
 * Problem: Population Growth Statistics
 * WAP to accept the population of a town and the annual  
 * growth rate (in percentage).
 * Calculate and display the population after 1, 2, 3, 4 and 5 years.
 * INPUT:
 * Population: 1000
 * Growth Rate: 10%
 * OUTPUT:  1100, 1210, 1331, 1464, 1610
 */

$population = readline("Enter population: ");
$gRate = readline("Enter growth rate (in %): ");
$rate = $gRate / 100;

for ($year = 1; $year <= 5; $year++) {
    $population = $population + ($population * $rate);
    echo "Population after year $year: " . round($population) . "\n";
}
?>
