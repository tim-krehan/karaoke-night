<?php
// index.php - Public request page with AJAX fuzzy search + mobile optimized tables

$songsFile = __DIR__ . '/songs.json';

function loadSongs($file)
{
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
    $json = file_get_contents($file);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

function saveSongs($file, $songs)
{
    file_put_contents($file, json_encode($songs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$songs = loadSongs($songsFile);
$createdNew = false;

// Handle new song request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_title'])) {
    $newTitle = trim($_POST['request_title']);
    if ($newTitle !== '') {
        $exists = false;
        foreach ($songs as &$song) {
            if (mb_strtolower($song['title']) === mb_strtolower($newTitle)) {
                $song['count']++;
                $exists = true;
                break;
            }
        }
        unset($song);

        if (!$exists) {
            $songs[] = [
                'title' => $newTitle,
                'interpret' => '',
                'count' => 1,
                'status' => 'requested'
            ];
        }

        saveSongs($songsFile, $songs);
        $createdNew = true;
    }
}

$topBanner = getenv('TOP_BANNER_TEXT') ?: '✨ WELCOME TO THE ULTIMATE 90s YANKEES KARAOKE REQUEST PAGE ✨ REQUEST YOUR SONGS NOW ✨';
$bottomBanner = getenv('BOTTOM_BANNER_TEXT') ?: '✨ 90s Yankees Karaoke Vibes ✨ Only Emojis, No Images ✨';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Karaoke Requests ✨</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="style.css">
</head>
<body class="yankees-body">

<marquee class="yankees-marquee"><?php echo htmlspecialchars($topBanner); ?></marquee>

<div class="page-container">
    <header class="header">
        <h1 class="title">🎤 Karaoke Request Zone ✨</h1>
        <nav class="nav">
            <a href="login.php" class="nav-link">Admin Login 🔐</a>
        </nav>
    </header>

    <main>
        <section class="search-section">
            <h2 class="section-title">Finde deinen Song 🎶</h2>

            <form class="search-form" onsubmit="return false;">
                <label class="search-label">Songtitel oder Interpret:</label>
                <input type="text" id="search" class="search-input" autocomplete="off">
            </form>

            <?php if ($createdNew): ?>
                <p class="info-message">✨ Song erfolgreich hinzugefügt! Die gesamte Liste wird angezeigt. ✨</p>
            <?php endif; ?>
        </section>

        <section class="table-section">
            <h2 class="section-title">Aktuelle Karaoke-Liste 📜</h2>

            <div class="table-wrapper">
                <table class="song-table">
                    <thead>
                        <tr>
                            <th>Songtitel 🎵</th>
                            <th>Interpret 🎤</th>
                            <th>Status ✅</th>
                        </tr>
                    </thead>
                    <tbody id="song-table-body">
                        <?php foreach ($songs as $song): ?>
                            <tr>
                                <td data-label="Songtitel 🎵"><?php echo htmlspecialchars($song['title']); ?></td>
                                <td data-label="Interpret 🎤"><?php echo htmlspecialchars($song['interpret']); ?></td>
                                <td data-label="Status ✅"><?php echo htmlspecialchars($song['status']); ?></td>
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

<script src="script.js"></script>
</body>
</html>
