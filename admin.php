<?php
session_start();

// Protect admin panel
if (empty($_SESSION['is_admin'])) {
    header('Location: login.php');
    exit;
}

$songsFile = __DIR__ . '/songs.json';

if (!file_exists($songsFile)) {
    file_put_contents($songsFile, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$songs = json_decode(file_get_contents($songsFile), true);
if (!is_array($songs)) {
    $songs = [];
}

// Handle admin updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $index = isset($_POST['index']) ? (int)$_POST['index'] : -1;
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';
    $confirmed = isset($_POST['confirmed']) ? ($_POST['confirmed'] === 'true') : false;

    if ($index >= 0 && isset($songs[$index])) {
        if ($status !== '') {
            $songs[$index]['status'] = $status;
        }
        $songs[$index]['confirmed'] = $confirmed;

        file_put_contents($songsFile, json_encode($songs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    header('Location: admin.php');
    exit;
}

// Sort by count desc
usort($songs, function ($a, $b) {
    return ($b['count'] ?? 0) <=> ($a['count'] ?? 0);
});
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Karaoke Admin Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="yankees-bg admin-body">
    <header class="header-banner admin-header">
        <div class="pixel-sparkle sparkle">✨</div>
        <h1 class="title-text blink">KARAOKE ADMIN PANEL</h1>
        <div class="pixel-sparkle sparkle">✨</div>
    </header>

    <main class="main-container">
        <section class="admin-section">
            <p class="admin-back-link">
                <a href="index.php">&laquo; Zurück zur Request-Seite</a> |
                <a href="logout.php">Logout</a>
            </p>

            <h2 class="section-title">Song-Status verwalten</h2>

            <?php if (empty($songs)): ?>
                <p class="no-songs">Noch keine Songs vorhanden.</p>
            <?php else: ?>
                <table class="songs-table admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Titel</th>
                            <th>Interpret</th>
                            <th>Anzahl</th>
                            <th>Status</th>
                            <th>Confirmed</th>
                            <th>Requested By</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($songs as $displayIndex => $song): ?>
                        <tr>
                            <td><?php echo $displayIndex; ?></td>
                            <td><?php echo htmlspecialchars($song['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($song['interpret'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="count-cell"><?php echo (int)($song['count'] ?? 0); ?></td>
                            <td><?php echo htmlspecialchars($song['status'] ?? 'pending', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo !empty($song['confirmed']) ? 'true' : 'false'; ?></td>
                            <td>
                                <?php
                                if (!empty($song['requested_by']) && is_array($song['requested_by'])) {
                                    echo htmlspecialchars(implode(', ', $song['requested_by']), ENT_QUOTES, 'UTF-8');
                                }
                                ?>
                            </td>
                            <td>
                                <form action="admin.php" method="post" class="admin-form-inline">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="index" value="<?php echo $displayIndex; ?>">
                                    <label>
                                        Status:
                                        <select name="status">
                                            <?php
                                            $statuses = ['pending', 'in_progress', 'done'];
                                            $currentStatus = $song['status'] ?? 'pending';
                                            foreach ($statuses as $st) {
                                                $selected = ($st === $currentStatus) ? 'selected' : '';
                                                echo '<option value="' . htmlspecialchars($st, ENT_QUOTES, 'UTF-8') . '" ' . $selected . '>' . htmlspecialchars($st, ENT_QUOTES, 'UTF-8') . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </label>
                                    <label>
                                        Confirmed:
                                        <select name="confirmed">
                                            <option value="false" <?php echo empty($song['confirmed']) ? 'selected' : ''; ?>>false</option>
                                            <option value="true" <?php echo !empty($song['confirmed']) ? 'selected' : ''; ?>>true</option>
                                        </select>
                                    </label>
                                    <button type="submit" class="btn-admin-update">Speichern</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </main>

    <footer class="footer-banner">
        <p>Admin-Tools für Karaoke Yankees Night</p>
    </footer>
</body>
</html>
