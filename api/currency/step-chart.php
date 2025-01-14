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

$inputData = json_decode(file_get_contents("php://input"), true);

$dateFrom = isset($inputData['dateFrom']) ? $inputData['dateFrom'] : null;
$dateTo = isset($inputData['dateTo']) ? $inputData['dateTo'] : null;
$category = isset($inputData['category']) ? $inputData['category'] : null;

if (!$dateFrom || !$dateTo || !$category) {
    http_response_code(400);
    echo json_encode(["error" => "Missing dateFrom or dateTo parameter"]);
    exit;
}

try {
    $data = $db->getStepChartData($dateFrom, $dateTo, $category);
    echo json_encode($data);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}