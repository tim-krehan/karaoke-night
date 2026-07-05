<?php
session_start();

if (!($_SESSION['is_admin'] ?? false)) {
    header('Location: login.php');
    exit;
}

$songsFile = __DIR__ . '/songs.json';

if (!file_exists($songsFile)) {
    file_put_contents($songsFile, json_encode([], JSON_PRETTY_PRINT));
}

$songs = json_decode(file_get_contents($songsFile), true);
$songs = is_array($songs) ? $songs : [];

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="songs_export.csv"');

$out = fopen('php://output', 'w');
fputcsv($out, ['title', 'interpret', 'count', 'status']);

foreach ($songs as $s) {
    fputcsv($out, [$s['title'], $s['interpret'], $s['count'], $s['status']]);
}

fclose($out);
exit;
