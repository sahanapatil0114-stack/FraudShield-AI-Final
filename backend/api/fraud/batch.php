<?php
require_once __DIR__ . '/../../config/cors.php';
require_once __DIR__ . '/../../config/fraud_model.php';

setCORSHeaders();
handlePreflight();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Method not allowed', 405);
}

$body = getRequestBody();
$txns = array_slice($body['transactions'] ?? [], 0, 100);
$results = [];
$fraudCount = 0;

foreach ($txns as $t) {
    $result = analyze_transaction(
        (float)($t['amount'] ?? 0),
        trim($t['merchant'] ?? ''),
        trim($t['location'] ?? ''),
        (int)($t['hour'] ?? (int)date('G')),
        trim($t['category'] ?? 'general')
    );
    if ($result['status'] === 'fraud') $fraudCount++;
    $results[] = array_merge($t, ['result' => $result]);
}

jsonResponse(['success' => true, 'results' => $results, 'fraud_count' => $fraudCount, 'total' => count($results)]);
