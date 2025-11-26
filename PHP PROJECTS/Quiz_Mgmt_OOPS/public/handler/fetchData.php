<?php
header('Content-Type: application/json');

// Debug: Check current directory
error_log("Current directory: " . __DIR__);

// Use absolute paths to avoid issues
$rootDir = dirname(dirname(__DIR__)); // Goes up to project root

require_once $rootDir . '/core/database.php';
require_once $rootDir . '/models/gateways/BatchGateway.php';
require_once $rootDir . '/models/gateways/TechnologyGateway.php';

error_log("=== fetchData.php started ===");

try {
    if (!$conn) {
        throw new Exception("Database connection is null");
    }
    
    error_log("Database connection successful");

    $batchGateway = new BatchGateway($conn);
    $techGateway = new TechnologyGateway($conn);

    error_log("Gateways instantiated");

    // Test direct query first
    error_log("Testing direct query...");
    $test_result = $conn->query("SELECT COUNT(*) as count FROM batch");
    if ($test_result) {
        $test_row = $test_result->fetch_assoc();
        error_log("Direct batch count: " . $test_row['count']);
    }

    $batches = $batchGateway->getAll();
    $technologies = $techGateway->getAll();

    error_log("Batches count: " . count($batches));
    error_log("Technologies count: " . count($technologies));

    echo json_encode([
        'status' => 'success',
        'batches' => $batches,
        'technologies' => $technologies,
        'debug' => [
            'batch_count' => count($batches),
            'technology_count' => count($technologies)
        ]
    ]);

} catch (Exception $e) {
    error_log("fetchData.php Exception: " . $e->getMessage());
    
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'batches' => [],
        'technologies' => []
    ]);
}
?>