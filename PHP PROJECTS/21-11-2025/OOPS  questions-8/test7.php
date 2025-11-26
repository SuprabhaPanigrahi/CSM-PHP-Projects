<?php
declare(strict_types=1);

abstract class Animal {
    private string $name;

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }

    // Abstract method
    public abstract function eat(): void;
}

class Dog extends Animal {

    public function eat(): void {
        echo $this->getName() . " is eating.\n";
    }
}

// ---------- Test Section (equivalent to Main) ----------

// Ask user for dog name
echo "Enter the dog's name: ";
$dogName = trim(readline());

// Create Dog object
$myDog = new Dog();

// Set the dog's name
$myDog->setName($dogName);

// Output name and eating action
echo "Dog's name is: " . $myDog->getName() . "\n";
$myDog->eat();

?>


