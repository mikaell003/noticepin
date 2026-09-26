<?php

session_start();

require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    DELETE FROM notices
    WHERE id = ?
    AND user_id = ?
");

$stmt->execute([
    $id,
    $_SESSION['user_id']
]);

header("Location: dashboard.php");
exit;