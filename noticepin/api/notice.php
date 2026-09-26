<?php

require_once '../config/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$siteId = trim($_GET['site'] ?? '');

if ($siteId === '') {

    echo json_encode([
        'success' => false,
        'message' => 'Missing site ID.'
    ]);

    exit;
}

$stmt = $pdo->prepare("
    SELECT
        n.id,
        n.title,
        n.message,
        n.button_text,
        n.button_url,
        n.position,
        n.note_color,
        n.text_color
    FROM notices n
    INNER JOIN users u
        ON u.id = n.user_id
    WHERE u.site_id = ?
    AND n.is_active = 1
    ORDER BY n.created_at DESC
    LIMIT 1
");

$stmt->execute([$siteId]);

$notice = $stmt->fetch();

if (!$notice) {

    echo json_encode([
        'success' => true,
        'notice' => null
    ]);

    exit;
}

$update = $pdo->prepare("
    UPDATE notices
    SET views = views + 1
    WHERE id = ?
");

$update->execute([$notice['id']]);

echo json_encode([
    'success' => true,
    'notice' => $notice
]);