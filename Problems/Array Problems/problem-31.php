<?php
// Define an associative array with countries as keys and capitals as values
$ceu = array(
    "Italy" => "Rome", "Luxembourg" => "Luxembourg",
    "Belgium" => "Brussels", "Denmark" => "Copenhagen",
    "Finland" => "Helsinki", "France" => "Paris",
    "Slovakia" => "Bratislava", "Slovenia" => "Ljubljana",
    "Germany" => "Berlin", "Greece" => "Athens",
    "Ireland" => "Dublin", "Netherlands" => "Amsterdam",
    "Portugal" => "Lisbon", "Spain" => "Madrid",
    "Sweden" => "Stockholm", "United Kingdom" => "London",
    "Cyprus" => "Nicosia", "Lithuania" => "Vilnius",
    "Czech Republic" => "Prague", "Estonia" => "Tallin",
    "Hungary" => "Budapest", "Latvia" => "Riga",
    "Malta" => "Valetta", "Austria" => "Vienna",
    "Poland" => "Warsaw"
);

// Sort the associative array by values (capitals) in ascending order
asort($ceu);

// Iterate through the sorted array and echo the country and its capital
foreach ($ceu as $country => $capital) {
    echo "The capital of $country is $capital" . "<br>";
}
?>
