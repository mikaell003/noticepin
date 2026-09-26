<?php

session_start();

require_once 'config/database.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {

        $check = $pdo->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $check->execute([$email]);

        if ($check->fetch()) {

            $error = "An account with that email already exists.";

        } else {

            $siteId = 'np_' . bin2hex(random_bytes(6));

            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $pdo->prepare("
                INSERT INTO users
                (name, email, password, site_id)
                VALUES (?, ?, ?, ?)
            ");

            $stmt->execute([
                $name,
                $email,
                $hashedPassword,
                $siteId
            ]);

            $_SESSION['user_id'] = $pdo->lastInsertId();

            header("Location: dashboard.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Create Account — NoticePin</title>

<link rel="stylesheet" href="assets/css/style.css">

</head>

<body class="auth-page">

<div class="auth-card">

    <div class="auth-logo">
        📌 NoticePin
    </div>

    <h1>Create your account</h1>

    <p class="auth-subtitle">
        Start adding sticky notices to your website.
    </p>

    <?php if ($error): ?>
        <div class="error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <label>Name</label>

        <input
            type="text"
            name="name"
            placeholder="Your name"
            required
        >

        <label>Email</label>

        <input
            type="email"
            name="email"
            placeholder="you@example.com"
            required
        >

        <label>Password</label>

        <input
            type="password"
            name="password"
            placeholder="Minimum 6 characters"
            required
        >

        <button class="primary-button full">
            Create Account
        </button>

    </form>

    <p class="auth-bottom">
        Already have an account?
        <a href="login.php">Login</a>
    </p>

</div>

</body>
</html>