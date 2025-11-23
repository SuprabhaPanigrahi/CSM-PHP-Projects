<?php

// WAP TO CREATE A CLASS HAVING PROPERTIES ID, NAME, EMAIL, GENDER, PHONE NUMBER, COURSE, FEE.
// CREATE A CONSTRUCTOR TO INITIALIZE THE INSTANCE OF OBJECT,
// CREATE SETTER AND GETTER,
// CREATE A METHOD NAMED TOSTRING WHICH WILL RETURN ALL PROPERTY VALUES,
// RETURN IF GENDER IS MALE THEN MR. IF FEMALE THEN MS.

class Student {
    private $ID;
    private $Name;
    private $Email;
    private $Gender;
    private $Phone;
    private $Course;
    private $CourseFee;

    // Constructor to initialize object
    public function __construct($ID, $Name, $Email, $Gender, $Phone, $Course, $CourseFee) {
        $this->ID = $ID;
        $this->Name = $Name;
        $this->Email = $Email;
        $this->Gender = $Gender;
        $this->Phone = $Phone;
        $this->Course = $Course;
        $this->CourseFee = $CourseFee;
    }

    public function getID() {
        return $this->ID;
    }

    public function getName() {
        return $this->Name;
    }

    public function getEmail() {
        return $this->Email;
    }

    public function getGender() {
        return $this->Gender;
    }

    public function getPhone() {
        return $this->Phone;
    }

    public function getCourse() {
        return $this->Course;
    }

    public function getCourseFee() {
        return $this->CourseFee;
    }

    public function setID($ID) {
        $this->ID = $ID;
    }

    public function setName($Name) {
        $this->Name = $Name;
    }

    public function setEmail($Email) {
        $this->Email = $Email;
    }

    public function setGender($Gender) {
        $this->Gender = $Gender;
    }

    public function setPhone($Phone) {
        $this->Phone = $Phone;
    }

    public function setCourse($Course) {
        $this->Course = $Course;
    }

    public function setCourseFee($CourseFee) {
        $this->CourseFee = $CourseFee;
    }

    public function __toString() {
        $prefix = ($this->Gender == "Male") ? "Mr." : "Ms.";
        return "ID: {$this->ID}\n" .
               "Name: {$prefix} {$this->Name}\n" .
               "Email: {$this->Email}\n" .
               "Gender: {$this->Gender}\n" .
               "Phone: {$this->Phone}\n" .
               "Course: {$this->Course}\n" .
               "Course Fee: {$this->CourseFee}\n";
    }
}

$student1 = new Student(101, "John Doe", "john@example.com", "Male", "9876543210", "PHP", 5000);
echo $student1; 

?>
