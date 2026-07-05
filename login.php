<?php
session_start();

// Already logged in → redirect
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    header('Location: admin.php');
    exit;
}

$adminPassword = getenv('ADMIN_PASSWORD') ?: 'changeme';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    if ($password === $adminPassword) {
        $_SESSION['is_admin'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Falsches Passwort ✨';
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin Login ✨</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="yankees-body">
<marquee class="yankees-marquee" behavior="scroll" direction="left">
    ✨ ADMIN LOGIN ✨ ONLY TRUE KARAOKE CAPTAINS MAY ENTER ✨
</marquee>

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
            <form method="post" action="login.php" class="login-form">
                <label for="password" class="login-label">Admin-Passwort:</label>
                <input type="password" id="password" name="password" class="login-input">
                <button type="submit" class="btn btn-login">Login ✨</button>
            </form>
        </section>
    </main>

    <footer class="footer">
        <p>✨ Passwortgeschütztes Admin-Panel ✨ ENV: ADMIN_PASSWORD ✨</p>
    </footer>
</div>
</body>
</html>
