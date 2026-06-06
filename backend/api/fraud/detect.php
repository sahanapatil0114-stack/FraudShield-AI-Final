<?php
require_once __DIR__ . '/../../config/cors.php';
require_once __DIR__ . '/../../config/fraud_model.php';

setCORSHeaders();
handlePreflight();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Method not allowed', 405);
}

$body = getRequestBody();
$result = analyze_transaction(
    (float)($body['amount'] ?? 0),
    trim($body['merchant'] ?? ''),
    trim($body['location'] ?? ''),
    (int)($body['hour'] ?? (int)date('G')),
    trim($body['category'] ?? 'general')
);

jsonResponse(['success' => true, 'result' => $result]);
