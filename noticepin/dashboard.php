<?php

session_start();

require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare(
    "SELECT * FROM users WHERE id = ?"
);

$stmt->execute([$userId]);

$user = $stmt->fetch();

$stmt = $pdo->prepare("
    SELECT *
    FROM notices
    WHERE user_id = ?
    ORDER BY created_at DESC
");

$stmt->execute([$userId]);

$notices = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard — NoticePin</title>

<link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<nav class="dashboard-nav">

    <div class="logo">
        📌 NoticePin
    </div>

    <div>
        <span class="welcome">
            <?php echo htmlspecialchars($user['name']); ?>
        </span>

        <a href="logout.php" class="logout">
            Logout
        </a>
    </div>

</nav>

<main class="dashboard">

    <div class="dashboard-header">

        <div>
            <h1>Your Notices</h1>

            <p>
                Manage the messages appearing on your websites.
            </p>
        </div>

        <a href="create.php" class="primary-button">
            + Create Notice
        </a>

    </div>

    <div class="site-box">

        <div>
            <strong>Your Site ID</strong>

            <p>
                <?php echo htmlspecialchars($user['site_id']); ?>
            </p>
        </div>

        <button
            class="copy-button"
            onclick="copyText('<?php echo htmlspecialchars($user['site_id']); ?>')"
        >
            Copy
        </button>

    </div>

    <?php if (!$notices): ?>

        <div class="empty-state">

            <div class="empty-icon">📌</div>

            <h2>No notices yet</h2>

            <p>
                Create your first sticky notice and add it to your website.
            </p>

            <a href="create.php" class="primary-button">
                Create Notice
            </a>

        </div>

    <?php else: ?>

        <div class="notice-grid">

            <?php foreach ($notices as $notice): ?>

                <div class="notice-card">

                    <div
                        class="notice-preview"
                        style="
                            background: <?php echo htmlspecialchars($notice['note_color']); ?>;
                            color: <?php echo htmlspecialchars($notice['text_color']); ?>;
                        "
                    >

                        <div class="preview-pin">📌</div>

                        <h3>
                            <?php echo htmlspecialchars($notice['title']); ?>
                        </h3>

                        <p>
                            <?php echo nl2br(htmlspecialchars($notice['message'])); ?>
                        </p>

                        <?php if ($notice['button_text']): ?>

                            <button>
                                <?php echo htmlspecialchars($notice['button_text']); ?>
                            </button>

                        <?php endif; ?>

                    </div>

                    <div class="notice-info">

                        <div class="notice-title-row">

                            <h3>
                                <?php echo htmlspecialchars($notice['title']); ?>
                            </h3>

                            <?php if ($notice['is_active']): ?>

                                <span class="status active">
                                    Active
                                </span>

                            <?php else: ?>

                                <span class="status inactive">
                                    Inactive
                                </span>

                            <?php endif; ?>

                        </div>

                        <div class="stats">

                            <span>
                                👁 <?php echo $notice['views']; ?> views
                            </span>

                            <span>
                                🖱 <?php echo $notice['clicks']; ?> clicks
                            </span>

                        </div>

                        <div class="card-actions">

                            <a href="edit.php?id=<?php echo $notice['id']; ?>">
                                Edit
                            </a>

                            <a
                                href="delete.php?id=<?php echo $notice['id']; ?>"
                                onclick="return confirm('Delete this notice?')"
                                class="danger"
                            >
                                Delete
                            </a>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <div class="install-box">

        <h2>Install NoticePin</h2>

        <p>
            Add this code before the closing
            <code>&lt;/body&gt;</code>
            tag on your website.
        </p>

        <div class="code-box">

<pre id="embedCode">&lt;script src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/widget/widget.js"
data-site="<?php echo htmlspecialchars($user['site_id']); ?>"&gt;&lt;/script&gt;</pre>

            <button
                class="copy-button"
                onclick="copyEmbed()"
            >
                Copy Code
            </button>

        </div>

    </div>

</main>

<script>

function copyText(text) {
    navigator.clipboard.writeText(text);
    alert("Copied!");
}

function copyEmbed() {

    const code = document
        .getElementById('embedCode')
        .innerText;

    navigator.clipboard.writeText(code);

    alert("Embed code copied!");
}

</script>

</body>
</html>