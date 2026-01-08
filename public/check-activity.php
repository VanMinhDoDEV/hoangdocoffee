<?php
// SUPER FAST CHECK - NO FRAMEWORK LOADED
// Response time: < 10ms

// Disable caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$storagePath = __DIR__ . '/../storage/latest_activity.json';

// Get Client IDs (default 0)
$clientOrder = isset($_GET['last_order_id']) ? (int)$_GET['last_order_id'] : 0;
$clientComment = isset($_GET['last_comment_id']) ? (int)$_GET['last_comment_id'] : 0;
$clientReview = isset($_GET['last_review_id']) ? (int)$_GET['last_review_id'] : 0;

// Read Server Data
$serverData = ['order' => 0, 'comment' => 0, 'review' => 0];

if (file_exists($storagePath)) {
    $content = file_get_contents($storagePath);
    $json = json_decode($content, true);
    if ($json) {
        $serverData = array_merge($serverData, $json);
    }
}

// Compare
$hasNew = false;
$newIds = [];

if ($serverData['order'] > $clientOrder) {
    $hasNew = true;
    $newIds['order'] = $serverData['order'];
}
if ($serverData['comment'] > $clientComment) {
    $hasNew = true;
    $newIds['comment'] = $serverData['comment'];
}
if ($serverData['review'] > $clientReview) {
    $hasNew = true;
    $newIds['review'] = $serverData['review'];
}

if (!$hasNew) {
    http_response_code(204);
    exit;
}

// Return new data
header('Content-Type: application/json');
echo json_encode([
    'has_new' => true,
    'latest' => $serverData // Return all latest IDs
]);
exit;
