<?php
// models/StudentGateway.php

class StudentGateway
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function insert(Student $student): bool
    {
        $sql = "INSERT INTO students (name, email, age) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $name  = $student->getName();
        $email = $student->getEmail();
        $age   = $student->getAge();

        $stmt->bind_param("ssi", $name, $email, $age);

        return $stmt->execute();
    }

    public function all(): array
    {
        $rows = [];

        $result = $this->conn->query("SELECT * FROM students ORDER BY id DESC");

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }

        return $rows;
    }
}
