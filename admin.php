<?php
session_start();

if (!($_SESSION['is_admin'] ?? false)) {
    header('Location: login.php');
    exit;
}

$songsFile = __DIR__ . '/songs.json';

function loadSongs($file)
{
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([], JSON_PRETTY_PRINT));
    }
    $data = json_decode(file_get_contents($file), true);
    return is_array($data) ? $data : [];
}

function saveSongs($file, $songs)
{
    file_put_contents($file, json_encode($songs, JSON_PRETTY_PRINT));
}

$songs = loadSongs($songsFile);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $i = (int)$_POST['index'];
    if (isset($songs[$i])) {
        $songs[$i]['title'] = trim($_POST['title']);
        $songs[$i]['interpret'] = trim($_POST['interpret']);
        $songs[$i]['count'] = (int)$_POST['count'];
        $songs[$i]['status'] = ($_POST['status'] === 'ok') ? 'ok' : 'requested';

        saveSongs($songsFile, $songs);
        $message = 'Songdaten aktualisiert ✨';
    }
}

$topBanner = getenv('TOP_BANNER_TEXT') ?: '✨ ADMIN CONTROL CENTER ✨ HANDLE YOUR KARAOKE LINEUP LIKE A 90s YANKEES LEGEND ✨';
$bottomBanner = getenv('BOTTOM_BANNER_TEXT') ?: '✨ Admin Power ✨ Manage Songs, Status, Interpret & Count ✨';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel ✨</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="style.css">
</head>
<body class="yankees-body">

<marquee class="yankees-marquee"><?php echo htmlspecialchars($topBanner); ?></marquee>

<div class="page-container">
    <header class="header">
        <h1 class="title">🔐 Admin Panel ✨</h1>
        <nav class="nav">
            <a href="index.php" class="nav-link">Zur Request-Seite 🎤</a>
            <a href="logout.php" class="nav-link">Logout 🚪</a>
            <a href="export.php" class="nav-link">CSV Export ✨</a>
        </nav>
    </header>

    <main>
        <section class="admin-section">
            <h2 class="section-title">Songverwaltung 🎶</h2>

            <?php if ($message): ?>
                <p class="info-message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <div class="table-wrapper">
                <table class="admin-table">
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
                        <?php foreach ($songs as $i => $song): ?>
                            <tr>
                                <form method="post">
                                    <td data-label="#"><?php echo $i; ?>
                                        <input type="hidden" name="index" value="<?php echo $i; ?>">
                                    </td>

                                    <td data-label="Songtitel 🎵">
                                        <input class="admin-input" type="text" name="title" value="<?php echo htmlspecialchars($song['title']); ?>">
                                    </td>

                                    <td data-label="Interpret 🎤">
                                        <input class="admin-input" type="text" name="interpret" value="<?php echo htmlspecialchars($song['interpret']); ?>">
                                    </td>

                                    <td data-label="Count 🔢">
                                        <input class="admin-input" type="number" name="count" value="<?php echo (int)$song['count']; ?>">
                                    </td>

                                    <td data-label="Status ✅">
                                        <select class="admin-input" name="status">
                                            <option value="requested" <?php echo $song['status']==='requested'?'selected':''; ?>>requested</option>
                                            <option value="ok" <?php echo $song['status']==='ok'?'selected':''; ?>>ok</option>
                                        </select>
                                    </td>

                                    <td data-label="Aktion ✨">
                                        <button class="btn btn-admin-update">Speichern ✨</button>
                                    </td>
                                </form>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </section>
    </main>

    <footer class="footer">
        <p><?php echo htmlspecialchars($bottomBanner); ?></p>
    </footer>
</div>

</body>
</html>
