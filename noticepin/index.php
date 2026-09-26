<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>NoticePin — Sticky Notices for Websites</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<nav class="navbar">
    <div class="logo">
         NoticePin
    </div>

    <div class="nav-links">
        <a href="login.php">Login</a>
        <a href="register.php" class="nav-button">Get Started</a>
    </div>
</nav>

<section class="hero">

    <div class="hero-content">

        <div class="badge">
             Tiny notices. Big visibility.
        </div>

        <h1>
            Pin important messages
            <span>to your website.</span>
        </h1>

        <p>
            Add beautiful sticky-note announcements to any website
            with one tiny line of code.
        </p>

        <div class="hero-buttons">
            <a href="register.php" class="primary-button">
                Create Your First Notice
            </a>

            <a href="#demo" class="secondary-button">
                See Demo
            </a>
        </div>

    </div>

    <div class="hero-demo" id="demo">

        <div class="browser">
            <div class="browser-bar">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <div class="fake-site">

                <h2>Your Website</h2>

                <p>
                    Imagine this is your business website.
                </p>

                <div class="fake-lines"></div>
                <div class="fake-lines short"></div>

                <div class="demo-note">

                    <div class="note-pin">📌</div>

                    <h3>🔥 Black Friday Sale</h3>

                    <p>
                        Get 50% off selected products this week.
                    </p>

                    <button>
                        Shop Now →
                    </button>

                </div>

            </div>
        </div>

    </div>

</section>

<section class="features">

    <div class="feature">
        <div class="feature-icon">⚡</div>
        <h3>One line of code</h3>
        <p>
            Add NoticePin to almost any website in seconds.
        </p>
    </div>

    <div class="feature">
        <div class="feature-icon">🎨</div>
        <h3>Customizable</h3>
        <p>
            Change the color, position, message and button.
        </p>
    </div>

    <div class="feature">
        <div class="feature-icon">📊</div>
        <h3>Simple analytics</h3>
        <p>
            See how many visitors view and click your notice.
        </p>
    </div>

</section>

<footer>
    © <?php echo date('Y'); ?> NoticePin.
</footer>

<script src="http://localhost/widget/widget.js"
data-site="np_e1fc149753c2"></script>
</body>
</html>