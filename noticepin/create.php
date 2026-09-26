<?php

session_start();

require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');
    $message = trim($_POST['message'] ?? '');

    $buttonText = trim($_POST['button_text'] ?? '');
    $buttonUrl = trim($_POST['button_url'] ?? '');

    $position = $_POST['position'] ?? 'bottom-right';

    $noteColor = $_POST['note_color'] ?? '#fff4a3';
    $textColor = $_POST['text_color'] ?? '#222222';

    $isActive = isset($_POST['is_active']) ? 1 : 0;

    $allowedPositions = [
        'bottom-right',
        'bottom-left',
        'top-right',
        'top-left'
    ];

    if (!in_array($position, $allowedPositions, true)) {
        $position = 'bottom-right';
    }

    if ($title === '' || $message === '') {

        $error = "Title and message are required.";

    } else {

        $stmt = $pdo->prepare("
            INSERT INTO notices
            (
                user_id,
                title,
                message,
                button_text,
                button_url,
                position,
                note_color,
                text_color,
                is_active
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $_SESSION['user_id'],
            $title,
            $message,
            $buttonText ?: null,
            $buttonUrl ?: null,
            $position,
            $noteColor,
            $textColor,
            $isActive
        ]);

        header("Location: dashboard.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Create Notice — NoticePin</title>

<link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<nav class="dashboard-nav">

    <div class="logo">
        📌 NoticePin
    </div>

    <a href="dashboard.php">
        ← Dashboard
    </a>

</nav>

<main class="form-page">

    <div class="form-header">

        <h1>Create a Notice</h1>

        <p>
            Create the message visitors will see on your website.
        </p>

    </div>

    <?php if ($error): ?>

        <div class="error">
            <?php echo htmlspecialchars($error); ?>
        </div>

    <?php endif; ?>

    <form method="POST" class="notice-form">

        <label>Title</label>

        <input
            type="text"
            name="title"
            placeholder="🔥 Black Friday Sale"
            maxlength="150"
            required
        >

        <label>Message</label>

        <textarea
            name="message"
            rows="5"
            placeholder="Get 50% off selected products this week."
            required
        ></textarea>

        <div class="two-columns">

            <div>

                <label>Button text</label>

                <input
                    type="text"
                    name="button_text"
                    placeholder="Shop Now →"
                >

            </div>

            <div>

                <label>Button URL</label>

                <input
                    type="url"
                    name="button_url"
                    placeholder="https://example.com"
                >

            </div>

        </div>

        <label>Position</label>

        <select name="position">

            <option value="bottom-right">
                Bottom Right
            </option>

            <option value="bottom-left">
                Bottom Left
            </option>

            <option value="top-right">
                Top Right
            </option>

            <option value="top-left">
                Top Left
            </option>

        </select>

        <div class="two-columns">

            <div>

                <label>Note color</label>

                <input
                    type="color"
                    name="note_color"
                    value="#fff4a3"
                >

            </div>

            <div>

                <label>Text color</label>

                <input
                    type="color"
                    name="text_color"
                    value="#222222"
                >

            </div>

        </div>

        <label class="checkbox">

            <input
                type="checkbox"
                name="is_active"
                checked
            >

            Show this notice immediately

        </label>

        <button class="primary-button">
            Create Notice
        </button>

    </form>

</main>

</body>
</html>