<?php
session_start();

// Load admin password from environment variable
$adminPassword = getenv('ADMIN_PASSWORD');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($adminPassword !== false && $password === $adminPassword) {
        $_SESSION['is_admin'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error = "Falsches Passwort!";
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="yankees-bg">

    <!-- Top scrolling banner -->
    <marquee behavior="scroll" direction="left" scrollamount="8"
             style="background:#ff0000; color:#ffffff; font-family:'Arial Black'; font-size:1.4rem; padding:10px; border:4px solid #ffffff;">
        ★ WELCOME TO THE KARAOKE ADMIN ZONE ★ PLEASE ENTER YOUR PASSWORD ★
    </marquee>

    <div class="main-container">
        <h1 class="section-title">Admin Login</h1>

        <?php if (!empty($error)): ?>
            <p style="color: #ff0000; font-weight: bold;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="login.php" method="post" class="request-form">
            <div class="form-row">
                <label for="password">Admin Passwort</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-submit">Login</button>
        </form>

        <p class="admin-back-link" style="margin-top:20px;">
            <a href="index.php">&laquo; Zurück zur Karaoke‑Request‑Seite</a>
        </p>
    </div>

    <!-- Bottom scrolling banner -->
    <marquee behavior="alternate" direction="right" scrollamount="10"
             style="background:#0033aa; color:#ffcc00; font-family:'Impact'; font-size:1.3rem; padding:10px; border:4px dashed #ffffff; margin-top:20px;">
        ★ CLICK ABOVE TO RETURN TO THE REQUEST PAGE ★
    </marquee>

</body>
</html>
