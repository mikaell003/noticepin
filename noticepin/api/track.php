<?php

require_once '../config/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$noticeId = intval($_POST['notice_id'] ?? 0);

if ($noticeId <= 0) {

    echo json_encode([
        'success' => false
    ]);

    exit;
}

$stmt = $pdo->prepare("
    UPDATE notices
    SET clicks = clicks + 1
    WHERE id = ?
");

$stmt->execute([$noticeId]);

echo json_encode([
    'success' => true
]);