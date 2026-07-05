<?php
session_start();

if ($_SESSION['is_admin'] ?? false) {
    header('Location: admin.php');
    exit;
}

$adminPassword = getenv('ADMIN_PASSWORD') ?: 'changeme';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['password'] ?? '') === $adminPassword) {
        $_SESSION['is_admin'] = true;
        header('Location: admin.php');
        exit;
    }
    $error = 'Falsches Passwort ✨';
}

$topBanner = getenv('TOP_BANNER_TEXT') ?: '✨ ADMIN LOGIN ✨ ONLY TRUE KARAOKE CAPTAINS MAY ENTER ✨';
$bottomBanner = getenv('BOTTOM_BANNER_TEXT') ?: '✨ Passwortgeschütztes Admin-Panel ✨ ENV: ADMIN_PASSWORD ✨';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin Login ✨</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body class="yankees-body">

<marquee class="yankees-marquee"><?php echo htmlspecialchars($topBanner); ?></marquee>

<div class="page-container">
    <header class="header">
        <h1 class="title">🔐 Admin Login ✨</h1>
        <nav class="nav">
            <a href="index.php" class="nav-link">Zur Request-Seite 🎤</a>
        </nav>
    </header>

    <main>
        <section class="login-section">
            <h2 class="section-title">Passwort eingeben 🔑</h2>

            <?php if ($error): ?>
                <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <form method="post" class="login-form">
                <label class="login-label">Admin-Passwort:</label>
                <input type="password" name="password" class="login-input">
                <button class="btn btn-login">Login ✨</button>
            </form>
        </section>
    </main>

    <footer class="footer">
        <p><?php echo htmlspecialchars($bottomBanner); ?></p>
    </footer>
</div>

</body>
</html>
