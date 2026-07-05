<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit;
}

$songsFile = __DIR__ . '/songs.json';

function loadSongs($file)
{
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
    return json_decode(file_get_contents($file), true);
}

function saveSongs($file, $songs)
{
    file_put_contents($file, json_encode($songs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$songs = loadSongs($songsFile);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['index'])) {
    $idx = (int)$_POST['index'];
    if (isset($songs[$idx])) {
        $songs[$idx]['title'] = trim($_POST['title']);
        $songs[$idx]['interpret'] = trim($_POST['interpret']);
        $songs[$idx]['count'] = (int)$_POST['count'];
        $status = trim($_POST['status']);
        $songs[$idx]['status'] = ($status === 'ok' || $status === 'requested') ? $status : 'requested';

        saveSongs($songsFile, $songs);
        $message = 'Songdaten aktualisiert ✨';
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel ✨</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="yankees-body">
<marquee class="yankees-marquee" behavior="scroll" direction="left">
    ✨ ADMIN CONTROL CENTER ✨ HANDLE YOUR KARAOKE LINEUP LIKE A 90s YANKEES LEGEND ✨
</marquee>

<div class="page-container">
    <header class="header">
        <h1 class="title">🔐 Admin Panel ✨</h1>
        <nav class="nav">
            <a href="index.php" class="nav-link">Zur Request-Seite 🎤</a>
            <a href="logout.php" class="nav-link">Logout 🚪</a>
        </nav>
    </header>

    <main>
        <section class="admin-section">
            <h2 class="section-title">Songverwaltung 🎶</h2>
            <?php if ($message): ?>
                <p class="info-message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <table class="song-table admin-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Songtitel 🎵</th>
                    <th>Interpret 🎤</th>
                    <th>Count 🔢</th>
                    <th>Status ✅</th>
                    <th>Aktion ✨</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($songs as $index => $song): ?>
                    <tr>
                        <form method="post" action="admin.php">
                            <td><?php echo $index; ?><input type="hidden" name="index" value="<?php echo $index; ?>"></td>
                            <td><input type="text" name="title" value="<?php echo htmlspecialchars($song['title']); ?>" class="admin-input"></td>
                            <td><input type="text" name="interpret" value="<?php echo htmlspecialchars($song['interpret']); ?>" class="admin-input"></td>
                            <td><input type="number" name="count" value="<?php echo (int)$song['count']; ?>" class="admin-input"></td>
                            <td>
                                <select name="status" class="admin-input">
                                    <option value="requested" <?php echo $song['status']==='requested'?'selected':''; ?>>requested</option>
                                    <option value="ok" <?php echo $song['status']==='ok'?'selected':''; ?>>ok</option>
                                </select>
                            </td>
                            <td><button type="submit" class="btn btn-admin-update">Speichern ✨</button></td>
                        </form>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer class="footer">
        <p>✨ Admin Power ✨ Manage Songs, Status, Interpret & Count ✨</p>
    </footer>
</div>
</body>
</html>
