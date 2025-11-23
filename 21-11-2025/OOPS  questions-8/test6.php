<?php
declare(strict_types=1);

interface IVehiculo {
    public function drive(): void;
    public function refuel(int $amount): bool;
}

class Car implements IVehiculo {

    private int $gasoline;

    // Constructor
    public function __construct(int $initialGasoline)
    {
        $this->gasoline = $initialGasoline;
    }

    public function drive(): void
    {
        if ($this->gasoline > 0) {
            echo "The car is driving...\n";
        } else {
            echo "The car cannot drive. No gasoline available.\n";
        }
    }

    public function refuel(int $amount): bool
    {
        $this->gasoline += $amount;
        echo "Car refueled. New gasoline amount: {$this->gasoline}\n";
        return true;
    }
}

// --------- Testing in Main-like section ---------

// Create a Car object with 0 gasoline
$myCar = new Car(0);

// Ask the user for gasoline amount
echo "Enter the amount of gasoline to refuel: ";
$amount = intval(readline());

// Refuel the car
$myCar->refuel($amount);

// Attempt to drive
$myCar->drive();

?>
