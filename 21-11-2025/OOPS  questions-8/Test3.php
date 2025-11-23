<?php

// --------------------
// Person Class
// --------------------
class Person {
    protected $age;

    public function greet() {
        echo "Hello\n";
    }

    public function setAge($age) {
        $this->age = $age;
    }
}

// --------------------
// Student Class
// --------------------
class Student extends Person {

    public function study() {
        echo "I'm studying\n";
    }

    public function showAge() {
        echo "My age is: {$this->age} years old\n";
    }
}

// --------------------
// Professor Class
// --------------------
class Professor extends Person {

    public function explain() {
        echo "I'm explaining\n";
    }
}

// --------------------
// Test Class
// --------------------
class StudentProfessorTest {

    public function main() {

        $person = new Person();
        $person->greet();

        echo "\n";

        // 2. Create a new Student, set age, say hello, show age
        $student = new Student();
        $student->setAge(20);
        $student->greet();
        $student->showAge();
        $student->study();

        echo "\n";

        // 3. Create a new Professor, set age, say hello, and explain
        $professor = new Professor();
        $professor->setAge(40);
        $professor->greet();
        $professor->explain();
    }
}


$test = new StudentProfessorTest();
$test->main();

?>
