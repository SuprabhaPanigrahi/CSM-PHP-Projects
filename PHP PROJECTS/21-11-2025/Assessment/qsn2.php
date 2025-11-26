<?php
abstract class Vehicle {
    public $vehicleNumber;
    public $brand;
    public $baseRate;

    public function __construct($number, $brand, $rate) {
        $this->vehicleNumber = $number;
        $this->brand = $brand;
        $this->baseRate = $rate;
    }

    abstract public function calculateRent($hours);
}

class Car extends Vehicle {
    public function calculateRent($hours) {
        return 200 * $hours;
    }
}

class Bike extends Vehicle {
    public function calculateRent($hours) {
        return 50 * $hours;
    }
}

class Truck extends Vehicle {
    public function calculateRent($hours) {
        return 500 * $hours;
    }
}

// Driver Code
$type = "Car";
$hours = 5;

if ($type == "Car") $v = new Car("C101", "Toyota", 200);
else if ($type == "Bike") $v = new Bike("B201", "Honda", 50);
else $v = new Truck("T301", "Tata", 500);

echo "Total Rent: ₹" . $v->calculateRent($hours);
?>
