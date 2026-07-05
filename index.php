<?php
// index.php - Public request page

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

function fuzzyMatch($needle, $haystack)
{
    // Case-insensitive fuzzy search
    $needle = mb_strtolower(trim($needle));
    $haystack = mb_strtolower(trim($haystack));

    if ($needle === '') {
        return true;
    }

    // direct substring match
    if (strpos($haystack, $needle) !== false) {
        return true;
    }

    // levenshtein fuzzy match
    $distance = levenshtein($needle, $haystack);
    $threshold = max(1, (int)floor(strlen($needle) / 3));

    return $distance <= $threshold;
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
            $createdNew = true;
        }
        saveSongs($songsFile, $songs);
        $search = $newTitle;
    }
}

// Filter songs
$filteredSongs = [];
if ($search !== '') {
    foreach ($songs as $song) {
        if (fuzzyMatch($search, $song['title']) || fuzzyMatch($search, $song['interpret'])) {
            $filteredSongs[] = $song;
        }
    }
} else {
    $filteredSongs = $songs;
}

$noResults = ($search !== '' && count($filteredSongs) === 0);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Karaoke Requests ✨</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="yankees-body">
<marquee class="yankees-marquee" behavior="scroll" direction="left">
    ✨ WELCOME TO THE ULTIMATE 90s YANKEES KARAOKE REQUEST PAGE ✨ REQUEST YOUR SONGS NOW ✨
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
            <form method="get" action="index.php" class="search-form">
                <label for="search" class="search-label">Songtitel oder Interpret:</label>
                <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" class="search-input">
                <button type="submit" class="btn btn-search">Suchen ✨</button>
            </form>
            <?php if ($createdNew): ?>
                <p class="info-message">✨ Dein Song wurde hinzugefügt und angefragt! ✨</p>
            <?php endif; ?>
        </section>

        <section class="table-section">
            <h2 class="section-title">Aktuelle Karaoke-Liste 📜</h2>
            <table class="song-table">
                <thead>
                <tr>
                    <th>Songtitel 🎵</th>
                    <th>Interpret 🎤</th>
                    <th>Status ✅</th>
                </tr>
                </thead>
                <tbody>
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
                            Keine Songs gefunden für: <strong><?php echo htmlspecialchars($search); ?></strong><br>
                            <?php if ($noResults): ?>
                                <form method="post" action="index.php" class="request-form">
                                    <input type="hidden" name="request_title" value="<?php echo htmlspecialchars($search); ?>">
                                    <button type="submit" class="btn btn-request">
                                        ✨ Diesen Song anfragen! ✨
                                    </button>
                                </form>
                            <?php else: ?>
                                Bitte gib einen Suchbegriff ein. ✨
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer class="footer">
        <p>✨ 90s Yankees Karaoke Vibes ✨ Only Emojis, No Images ✨</p>
    </footer>
</div>
</body>
</html>
