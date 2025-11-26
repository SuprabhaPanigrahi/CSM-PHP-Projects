<?php

class Person {
    private $name;

    public function __construct($name) {
        $this->name = $name;
    }

    public function __toString() {
        return $this->name;
    }
}

$persons = [];

$p1 = readline("Enter the first person's name: ");
$p2 = readline("Enter the second person's name: ");
$p3 = readline("Enter the third person's name: ");

$persons[] = new Person($p1);
$persons[] = new Person($p2);
$persons[] = new Person($p3);

// Display them
echo "\nPeople you entered:\n";
foreach ($persons as $person) {
    echo $person . "\n";
}

?>
