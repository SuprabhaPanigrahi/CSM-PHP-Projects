<?php

// --------------------
// Base Person Class
// --------------------
class Person {
    protected $name;

    public function __construct($name) {
        $this->name = $name;
    }

    public function __toString(){
        return $this->name;
    }
}

// --------------------
// Student Class
// --------------------
class Student extends Person {

    public function __construct($name) {
        parent::__construct($name);
    }

    public function study(){
        echo "{$this->name} is studying.\n";
    }
}

// --------------------
// Teacher Class
// --------------------
class Teacher extends Person {

    public function __construct(string $name) {
        parent::__construct($name);
    }

    public function explain(): void {
        echo "{$this->name} is explaining.\n";
    }
}


// Prompt for three names
$name1 = readline("Enter the first student's name: ");
$name2 = readline("Enter the second student's name: ");
$name3 = readline("Enter the teacher's name: ");

// Create two Students and one Teacher
$people[] = new Student($name1);
$people[] = new Student($name2);
$people[] = new Teacher($name3);

// Display info
foreach ($people as $person) {
    echo $person . "\n";

    // Call specific methods
    if ($person instanceof Student) {
        $person->study();
    } elseif ($person instanceof Teacher) {
        $person->explain();
    }
}
?>
