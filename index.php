<?php
// Ensure songs.json exists
$songsFile = __DIR__ . '/songs.json';
if (!file_exists($songsFile)) {
    file_put_contents($songsFile, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Load songs
$songs = json_decode(file_get_contents($songsFile), true);
if (!is_array($songs)) {
    $songs = [];
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
    <title>Karaoke Request Night</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="yankees-bg">
    <!-- Top scrolling banner -->
    <marquee behavior="scroll" direction="left" scrollamount="9"
            style="background:#ff0000; color:#ffffff; font-family:'Arial Black'; font-size:1.4rem; padding:10px; border:4px solid #ffffff;">
        ★ REQUEST YOUR FAVORITE SONGS ★ MAKE THE NIGHT LEGENDARY ★
    </marquee>

    <header class="header-banner">
        <div class="pixel-sparkle sparkle">✨</div>
        <h1 class="title-text blink">KARAOKE REQUEST NIGHT</h1>
        <div class="pixel-sparkle sparkle">✨</div>
    </header>

    <main class="main-container">
        <section class="request-section">
            <h2 class="section-title">Song-Request einreichen</h2>
            <form action="api.php" method="post" class="request-form">
                <div class="form-row">
                    <label for="title">Songtitel <span class="required">*</span></label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-row">
                    <label for="requested_by">Sängername (optional)</label>
                    <input type="text" id="requested_by" name="requested_by">
                </div>
                <div class="form-row">
                    <label for="interpret">Interpret (optional)</label>
                    <input type="text" id="interpret" name="interpret">
                </div>
                <div class="form-row">
                    <button type="submit" class="btn-submit">Request abschicken!</button>
                </div>
            </form>
            <p class="admin-link">
                <a href="admin.php">Zur Admin-Ansicht &raquo;</a>
            </p>
        </section>

        <!-- Mid-page sparkle marquee -->
        <marquee behavior="alternate" direction="right" scrollamount="7"
                style="background:#0033aa; color:#ffcc00; font-family:'Impact'; font-size:1.3rem; padding:10px; border:4px dashed #ffffff; margin:20px 0;">
            ✦ ✦ ✦ ADD YOUR SONG — ADD YOUR VOICE — ADD YOUR MAGIC ✦ ✦ ✦
        </marquee>


        <section class="table-section">
            <h2 class="section-title">Aktuelle Song-Requests</h2>
            <?php if (empty($songs)): ?>
                <p class="no-songs">Noch keine Requests – sei der erste!</p>
            <?php else: ?>
                <table class="songs-table">
                    <thead>
                        <tr>
                            <th>Titel</th>
                            <th>Interpret</th>
                            <th>Anzahl</th>
                            <th>Status</th>
                            <th>Confirmed</th>
                            <th>Requested By</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($songs as $song): ?>
                        <tr>
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
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </main>

    <!-- Bottom marquee -->
    <marquee behavior="scroll" direction="right" scrollamount="10"
            style="background:#001a33; color:#ffffff; font-family:'Arial Black'; font-size:1.2rem; padding:10px; border:4px solid #ff0000;">
        ★ THANK YOU FOR YOUR REQUEST ★ LET THE STARS SHINE TONIGHT ★
    </marquee>


    <footer class="footer-banner">
        <p>© 1996–2026 Karaoke Yankees Night – Powered by PHP & JSON</p>
    </footer>
</body>
</html>
