<?php
class Database {
    private static $host = "localhost";
    private static $user = "root";
    private static $pass = "csmpl@123";
    private static $db   = "library_system";

    public static function connect() {
        $conn = new mysqli(self::$host, self::$user, self::$pass, self::$db);

        if ($conn->connect_error) {
            die("DB Connection Failed: " . $conn->connect_error);
        }
        return $conn;
    }
}

$conn = Database::connect();
