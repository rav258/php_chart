<?php

header('Content-Type: application/json');

require_once __DIR__ . "/../../Database.php";

try {
    $db = new Database($pdo);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
    exit;
}

$dateFrom = isset($_GET['dateFrom']) ? $_GET['dateFrom'] : null;
$dateTo = isset($_GET['dateTo']) ? $_GET['dateTo'] : null;

if (!$dateFrom || !$dateTo) {
    http_response_code(400);
    echo json_encode(["error" => "Missing dateFrom or dateTo parameter"]);
    exit;
}

try {
    $data = $db->getPieData($dateFrom, $dateTo);
    echo json_encode($data);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}


