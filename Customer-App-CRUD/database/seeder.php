<?php
include 'connection.php';
mysqli_select_db($conn, 'customer_db');

$countries = [
    ['name' => 'United States', 'code' => 'US'],
    ['name' => 'Canada', 'code' => 'CA'],
    ['name' => 'United Kingdom', 'code' => 'UK'],
    ['name' => 'Australia', 'code' => 'AUS'],
    ['name' => 'India', 'code' => 'IND'],
];

foreach ($countries as $country){
    $name = mysqli_real_escape_string($conn, $country['name']);
    $code = mysqli_real_escape_string($conn, $country['code']);
    $insert_sql = "INSERT INTO countries (name, code) VALUES ('$name', '$code')";
    if (mysqli_query($conn, $insert_sql)){
        echo "Inserted Country: " . $name . "\n";
    } else {
        die("Error inserting country \n" . $name . ": " . mysqli_error($conn));
    }
}

$states = [
    ['name' => 'United States', 'countryID' => 1],
    ['name' => 'Texas', 'countryID' => 1],
    ['name' => 'Ontaio', 'countryID' => 2],
    ['name' => 'Quebec', 'countryID' => 2],
    ['name' => 'Maharastra', 'countryID' => 5],
    ['name' => 'Tamilnadu', 'countryID' => 5],
    ['name' => 'Karnataka', 'countryID' => 5],
    ['name' => 'New South Wales', 'countryID' => 4],
    ['name' => 'Victoria', 'countryID' => 4],
    ['name' => 'England', 'countryID' => 3],
];

foreach ($states as $state){
    $name = mysqli_real_escape_string($conn, $state['name']);
    $countryID = (int)($state['countryID']);
    $insert_sql = "INSERT INTO states (name, countryID) VALUES ('$name', '$countryID')";
    if (mysqli_query($conn, $insert_sql)){
        echo "Inserted State: " . $name . "\n";
    } else {
        die("Error inserting State \n" . $name . ": " . mysqli_error($conn));
    }
}

$cities = [
    ['name' => 'Los Angles', 'stateID' => 1],
    ['name' => 'San Fransisco', 'stateID' => 1],
    ['name' => 'Houston', 'stateID' => 2],
    ['name' => 'Dallas', 'stateID' => 2],
    ['name' => 'Toronto', 'stateID' => 3],
    ['name' => 'Montreal', 'stateID' => 4],
    ['name' => 'Mumbai', 'stateID' => 5],
    ['name' => 'Pune', 'stateID' => 5],
    ['name' => 'Chennai', 'stateID' => 6],
    ['name' => 'Bangalore', 'stateID' => 7],
    ['name' => 'Bangalore', 'stateID' => 5],
    ['name' => 'Cuttack', 'stateID' => 5],
];

foreach ($cities as $city){
    $name = mysqli_real_escape_string($conn, $city['name']);
    $stateID = (int)($city['stateID']);
    $insert_sql = "INSERT INTO cities (name, stateID) VALUES ('$name', '$stateID')";
    if (mysqli_query($conn, $insert_sql)){
        echo "Inserted City: " . $name . "\n";
    } else {
        die("Error inserting City \n" . $name . ": " . mysqli_error($conn));
    }
}
mysqli_close($conn);