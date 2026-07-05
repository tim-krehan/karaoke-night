<?php
// index.php - Public request page with AJAX fuzzy search

$songsFile = __DIR__ . '/songs.json';

function loadSongs($file)
{
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
    $json = file_get_contents($file);
    $data = json_decode($json, true);
    if (!is_array($data)) {
        $data = [];
    }
    return $data;
}

function saveSongs($file, $songs)
{
    file_put_contents($file, json_encode($songs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$songs = loadSongs($songsFile);
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$createdNew = false;

// Handle "request new song"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_title'])) {
    $newTitle = trim($_POST['request_title']);
    if ($newTitle !== '') {
        $exists = false;
        foreach ($songs as &$song) {
            if (mb_strtolower($song['title']) === mb_strtolower($newTitle)) {
                $song['count'] = (int)$song['count'] + 1;
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
                'status' => 'requested',
            ];
        }

        saveSongs($songsFile, $songs);

        // Reset search → show full table
        $search = '';
        $createdNew = true;
    }
}

// initial table data (full list)
$filteredSongs = $songs;

$topBanner = getenv('TOP_BANNER_TEXT') ?: '✨ WELCOME TO THE ULTIMATE 90s YANKEES KARAOKE REQUEST PAGE ✨ REQUEST YOUR SONGS NOW ✨';
$bottomBanner = getenv('BOTTOM_BANNER_TEXT') ?: '✨ 90s Yankees Karaoke Vibes ✨ Only Emojis, No Images ✨';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Karaoke Requests ✨</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body class="yankees-body">
<marquee class="yankees-marquee" behavior="scroll" direction="left">
    <?php echo htmlspecialchars($topBanner); ?>
</marquee>

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
                <label for="search" class="search-label">Songtitel oder Interpret:</label>
                <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" class="search-input" autocomplete="off">
            </form>

            <?php if ($createdNew): ?>
                <p class="info-message">
                    ✨ Song erfolgreich hinzugefügt! Die gesamte Liste wird angezeigt. ✨
                </p>
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
                    <?php if (count($filteredSongs) > 0): ?>
                        <?php foreach ($filteredSongs as $song): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($song['title']); ?></td>
                                <td><?php echo htmlspecialchars($song['interpret']); ?></td>
                                <td><?php echo htmlspecialchars($song['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="no-results">
                                Keine Songs vorhanden. ✨
                            </td>
                        </tr>
                    <?php endif; ?>
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
