<?php
header('Content-Type: application/json; charset=utf-8');

$songsFile = __DIR__ . '/songs.json';

function loadSongs($file)
{
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([], JSON_PRETTY_PRINT));
    }
    $data = json_decode(file_get_contents($file), true);
    return is_array($data) ? $data : [];
}

function fuzzyMatch($needle, $haystack)
{
    $needle = mb_strtolower(trim($needle));
    $haystack = mb_strtolower(trim($haystack));

    if ($needle === '') return true;
    if (strpos($haystack, $needle) !== false) return true;

    return levenshtein($needle, $haystack) <= max(1, floor(strlen($needle) / 3));
}

$action = $_GET['action'] ?? 'list';
$songs = loadSongs($songsFile);

if ($action === 'search') {
    $q = trim($_GET['q'] ?? '');
    $filtered = [];

    foreach ($songs as $song) {
        if (fuzzyMatch($q, $song['title']) || fuzzyMatch($q, $song['interpret'])) {
            $filtered[] = $song;
        }
    }

    echo json_encode($filtered, JSON_PRETTY_PRINT);
    exit;
}

echo json_encode($songs, JSON_PRETTY_PRINT);
