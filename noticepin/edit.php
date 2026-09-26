<?php

session_start();

require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT *
    FROM notices
    WHERE id = ?
    AND user_id = ?
");

$stmt->execute([
    $id,
    $_SESSION['user_id']
]);

$notice = $stmt->fetch();

if (!$notice) {
    die("Notice not found.");
}

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
            UPDATE notices
            SET
                title = ?,
                message = ?,
                button_text = ?,
                button_url = ?,
                position = ?,
                note_color = ?,
                text_color = ?,
                is_active = ?
            WHERE id = ?
            AND user_id = ?
        ");

        $stmt->execute([
            $title,
            $message,
            $buttonText ?: null,
            $buttonUrl ?: null,
            $position,
            $noteColor,
            $textColor,
            $isActive,
            $id,
            $_SESSION['user_id']
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

<title>Edit Notice — NoticePin</title>

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

        <h1>Edit Notice</h1>

        <p>
            Update your website announcement.
        </p>

    </div>

    <?php if (!empty($error)): ?>

        <div class="error">
            <?php echo htmlspecialchars($error); ?>
        </div>

    <?php endif; ?>

    <form method="POST" class="notice-form">

        <label>Title</label>

        <input
            type="text"
            name="title"
            value="<?php echo htmlspecialchars($notice['title']); ?>"
            required
        >

        <label>Message</label>

        <textarea
            name="message"
            rows="5"
            required
        ><?php echo htmlspecialchars($notice['message']); ?></textarea>

        <div class="two-columns">

            <div>

                <label>Button text</label>

                <input
                    type="text"
                    name="button_text"
                    value="<?php echo htmlspecialchars($notice['button_text'] ?? ''); ?>"
                >

            </div>

            <div>

                <label>Button URL</label>

                <input
                    type="url"
                    name="button_url"
                    value="<?php echo htmlspecialchars($notice['button_url'] ?? ''); ?>"
                >

            </div>

        </div>

        <label>Position</label>

        <select name="position">

            <?php

            $positions = [
                'bottom-right' => 'Bottom Right',
                'bottom-left' => 'Bottom Left',
                'top-right' => 'Top Right',
                'top-left' => 'Top Left'
            ];

            foreach ($positions as $value => $label):

            ?>

                <option
                    value="<?php echo $value; ?>"
                    <?php echo $notice['position'] === $value ? 'selected' : ''; ?>
                >
                    <?php echo $label; ?>
                </option>

            <?php endforeach; ?>

        </select>

        <div class="two-columns">

            <div>

                <label>Note color</label>

                <input
                    type="color"
                    name="note_color"
                    value="<?php echo htmlspecialchars($notice['note_color']); ?>"
                >

            </div>

            <div>

                <label>Text color</label>

                <input
                    type="color"
                    name="text_color"
                    value="<?php echo htmlspecialchars($notice['text_color']); ?>"
                >

            </div>

        </div>

        <label class="checkbox">

            <input
                type="checkbox"
                name="is_active"
                <?php echo $notice['is_active'] ? 'checked' : ''; ?>
            >

            Show this notice

        </label>

        <button class="primary-button">
            Save Changes
        </button>

    </form>

</main>

</body>
</html>