<?php
// Migrate.php
require_once __DIR__ . '/src/config.php';

// --- create DB if not exists using mysqli (connect without database first) ---
$host = DB_HOST;
$user = DB_USER;
$pass = DB_PASS;
$dbName = DB_NAME;

$mysqli = new mysqli($host, $user, $pass);
// check connection
if ($mysqli->connect_error) {
    die("MySQL connection error: " . $mysqli->connect_error . PHP_EOL);
}

// 1) Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
if ($mysqli->query($sql) === TRUE) {
    echo "Database `{$dbName}` ensured.\n";
} else {
    die("Error creating database: " . $mysqli->error . PHP_EOL);
}
$mysqli->close();
// -------------------------------------------------------------------------

require_once __DIR__ . '/src/Database.php';

$db = Database::getInstance()->getConnection();

$sqls = [];

// loginuser
$sqls[] = "CREATE TABLE IF NOT EXISTS loginuser (
    loginID INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// batch
$sqls[] = "CREATE TABLE IF NOT EXISTS batch (
    BatchID INT AUTO_INCREMENT PRIMARY KEY,
    BatchName VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// technology
$sqls[] = "CREATE TABLE IF NOT EXISTS technology (
    TechID INT AUTO_INCREMENT PRIMARY KEY,
    TechName VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// guest_user
$sqls[] = "CREATE TABLE IF NOT EXISTS guest_user (
    guestID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    phone VARCHAR(50),
    gender VARCHAR(20),
    batchID INT,
    techID INT,
    created_at DATETIME,
    FOREIGN KEY (batchID) REFERENCES batch(BatchID) ON DELETE SET NULL,
    FOREIGN KEY (techID) REFERENCES technology(TechID) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// question
$sqls[] = "CREATE TABLE IF NOT EXISTS question (
    QnID INT AUTO_INCREMENT PRIMARY KEY,
    Qn_desc TEXT,
    Opt_1 VARCHAR(255),
    Opt_2 VARCHAR(255),
    Opt_3 VARCHAR(255),
    Opt_4 VARCHAR(255),
    Answer VARCHAR(10),
    batchID INT,
    techID INT,
    FOREIGN KEY (batchID) REFERENCES batch(BatchID) ON DELETE SET NULL,
    FOREIGN KEY (techID) REFERENCES technology(TechID) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// attempt
$sqls[] = "CREATE TABLE IF NOT EXISTS attempt (
    attemptID INT AUTO_INCREMENT PRIMARY KEY,
    guestID INT,
    score INT,
    total INT,
    created_at DATETIME,
    FOREIGN KEY (guestID) REFERENCES guest_user(guestID) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// attempt_answer
$sqls[] = "CREATE TABLE IF NOT EXISTS attempt_answer (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attemptID INT,
    questionID INT,
    selected INT,
    FOREIGN KEY (attemptID) REFERENCES attempt(attemptID) ON DELETE CASCADE,
    FOREIGN KEY (questionID) REFERENCES question(QnID) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

foreach ($sqls as $sql) {
    if ($db->query($sql) === TRUE) {
        echo "OK\n";
    } else {
        echo "Error: " . $db->error . "\n";
    }
}
echo "Done.\n";
