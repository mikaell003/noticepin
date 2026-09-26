<?php

session_start();

require_once 'config/database.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare(
        "SELECT * FROM users WHERE email = ?"
    );

    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];

        header("Location: dashboard.php");
        exit;

    } else {

        $error = "Incorrect email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login — NoticePin</title>

<link rel="stylesheet" href="assets/css/style.css">

</head>

<body class="auth-page">

<div class="auth-card">

    <div class="auth-logo">
        📌 NoticePin
    </div>

    <h1>Welcome back</h1>

    <p class="auth-subtitle">
        Manage your website notices.
    </p>

    <?php if ($error): ?>
        <div class="error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <label>Email</label>

        <input
            type="email"
            name="email"
            required
        >

        <label>Password</label>

        <input
            type="password"
            name="password"
            required
        >

        <button class="primary-button full">
            Login
        </button>

    </form>

    <p class="auth-bottom">
        Don't have an account?
        <a href="register.php">Create one</a>
    </p>

</div>

</body>
</html>