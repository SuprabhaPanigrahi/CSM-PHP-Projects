<?php
class Course {
    public $courseId;
    public $courseName;
    public $duration;
    public $fee;

    public function __construct($id, $name, $duration, $fee) {
        $this->courseId = $id;
        $this->courseName = $name;
        $this->duration = $duration;
        $this->fee = $fee;
    }

    public function __toString() {
        return "ID: $this->courseId, Name: $this->courseName, Duration: $this->duration hrs, Fee: ₹$this->fee";
    }
}

class CourseManager {
    private $courses = [];

    public function addCourse($course) {
        foreach ($this->courses as $c) {
            if ($c->courseId == $course->courseId) {
                echo "Course ID already exists<br>";
                return;
            }
        }
        $this->courses[] = $course;
        echo "Course Added<br>";
    }

    public function viewCourses() {
        if (empty($this->courses)) {
            echo "No courses available<br>";
            return;
        }
        foreach ($this->courses as $course) {
            echo $course . "<br>";
        }
    }

    public function searchCourseById($id) {
        foreach ($this->courses as $c) {
            if ($c->courseId == $id) {
                echo $c;
                return;
            }
        }
        echo "Course not found<br>";
    }

    public function updateCourse($id, $name=null, $duration=null, $fee=null) {
        foreach ($this->courses as $c) {
            if ($c->courseId == $id) {
                if ($name) $c->courseName = $name;
                if ($duration) $c->duration = $duration;
                if ($fee) $c->fee = $fee;
                echo "Course Updated<br>";
                return;
            }
        }
        echo "Course not found<br>";
    }

    public function deleteCourse($id) {
        foreach ($this->courses as $key => $c) {
            if ($c->courseId == $id) {
                unset($this->courses[$key]);
                echo "Course Deleted<br>";
                return;
            }
        }
        echo "Course not found<br>";
    }

    public function getTotalCourses() {
        return count($this->courses);
    }
}
?>
