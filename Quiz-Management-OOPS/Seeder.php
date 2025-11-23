<?php
// Seeder.php
require_once __DIR__ . '/src/config.php';
require_once __DIR__ . '/src/Database.php';

$db = Database::getInstance()->getConnection();

// insert admin user
$username = 'admin';
$password = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $db->prepare("INSERT IGNORE INTO loginuser (username, password) VALUES (?, ?)");
$stmt->bind_param('ss', $username, $password);
$stmt->execute();
echo "Admin created: admin / admin123\n";

// sample batches
$batches = ['Batch A','Batch B'];
$stmt = $db->prepare("INSERT IGNORE INTO batch (BatchName) VALUES (?)");
foreach ($batches as $b) { $stmt->bind_param('s', $b); $stmt->execute(); }

// sample techs
$techs = ['PHP','JavaScript'];
$stmt = $db->prepare("INSERT IGNORE INTO technology (TechName) VALUES (?)");
foreach ($techs as $t) { $stmt->bind_param('s', $t); $stmt->execute(); }

// sample questions (for Batch A, PHP)
$batchId = $db->query("SELECT BatchID FROM batch WHERE BatchName='Batch A'")->fetch_assoc()['BatchID'];
$techId = $db->query("SELECT TechID FROM technology WHERE TechName='PHP'")->fetch_assoc()['TechID'];

$stmt = $db->prepare("INSERT INTO question (Qn_desc, Opt_1, Opt_2, Opt_3, Opt_4, Answer, batchID, techID)
    VALUES (?,?,?,?,?,?,?,?)");
$questions = [
    ['What is PHP?', 'A language', 'A database', 'A web server', 'An OS', 1],
    ['Which is echo in PHP?', 'print', 'echo', 'write', 'show', 2]
];
foreach ($questions as $q) {
    $stmt->bind_param('sssssiii', $q[0], $q[1], $q[2], $q[3], $q[4], $q[5], $batchId, $techId);
    $stmt->execute();
}
echo "Sample data inserted.\n";
