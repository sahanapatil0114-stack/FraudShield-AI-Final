<?php
// Heuristic fraud detection model (same logic as Python model.py)

function analyze_transaction(float $amount, string $merchant, string $location, int $hour, string $category = 'general'): array {
    $score = 0.0;
    $flags = [];

    if ($amount > 10000) { $score += 0.40; $flags[] = 'Extremely high transaction amount'; }
    elseif ($amount > 5000) { $score += 0.30; $flags[] = 'Very high transaction amount'; }
    elseif ($amount > 2000) { $score += 0.20; $flags[] = 'High transaction amount'; }
    elseif ($amount > 1000) { $score += 0.10; $flags[] = 'Above-average transaction amount'; }

    $badMerchant = ['unknown','unnamed','anonymous','mystery','phantom','suspicious','offshore','wire','crypto','xyz','test','vendor 0','merchant #','store #','trader'];
    foreach ($badMerchant as $kw) {
        if (stripos($merchant, $kw) !== false) { $score += 0.30; $flags[] = 'Suspicious or unknown merchant'; break; }
    }

    $badLoc = ['nigeria','russia','ukraine','china','iran','north korea','unknown','offshore','anonymous','vpn','tor'];
    foreach ($badLoc as $kw) {
        if (stripos($location, $kw) !== false) { $score += 0.30; $flags[] = 'High-risk geographic location'; break; }
    }

    if ($hour >= 1 && $hour <= 5) { $score += 0.20; $flags[] = 'Unusual transaction time (late night / early morning)'; }
    elseif ($hour === 0 || $hour === 23) { $score += 0.10; $flags[] = 'Late-night transaction'; }

    $badCat = ['crypto','transfer','wire','gambling','forex'];
    if (in_array(strtolower($category), $badCat, true)) {
        $score += 0.20;
        $flags[] = "High-risk transaction category: $category";
    }

    $score = max(0.01, min(0.99, $score + (mt_rand(-50, 50) / 1000)));

    if ($score >= 0.70) { $risk = 'high'; $status = 'fraud'; }
    elseif ($score >= 0.40) { $risk = 'medium'; $status = 'pending'; }
    else { $risk = 'low'; $status = 'safe'; }

    return [
        'fraud_probability' => round($score, 4),
        'risk_level'        => $risk,
        'risk_score'        => round($score * 100, 2),
        'status'            => $status,
        'flags'             => $flags,
        'model_version'     => 'v1.0.0',
        'confidence'        => round(1.0 - abs($score - 0.5) * 0.3, 4),
    ];
}
