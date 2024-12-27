<?php

header('Content-Type: application/json');

require_once __DIR__ . "/../../Database.php";

// Set up PDO
try {
    $db = new Database($pdo);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
    exit;
}

try {
    $dateRange = $db->getMinMaxDates();
    echo json_encode($dateRange);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
