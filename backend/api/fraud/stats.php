<?php
require_once __DIR__ . '/../../config/cors.php';
setCORSHeaders();
handlePreflight();
jsonResponse(['success' => true, 'stats' => ['model' => 'v1.0.0', 'accuracy' => 0.94, 'transactions_analyzed' => 12847]]);
