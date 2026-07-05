<?php
// export.php – CSV Export für Admin

session_start();

// Nur Admin darf exportieren
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit;
}

$songsFile = __DIR__ . '/songs.json';

// Songs laden
if (!file_exists($songsFile)) {
    file_put_contents($songsFile, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$songs = json_decode(file_get_contents($songsFile), true);
if (!is_array($songs)) {
    $songs = [];
}

// CSV Header
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="songs_export.csv"');

// Output stream
$output = fopen('php://output', 'w');

// CSV Spaltenüberschriften
fputcsv($output, ['title', 'interpret', 'count', 'status']);

// Daten eintragen
foreach ($songs as $song) {
    fputcsv($output, [
        $song['title'],
        $song['interpret'],
        $song['count'],
        $song['status']
    ]);
}

fclose($output);
exit;
