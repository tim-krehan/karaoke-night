<?php
// api.php - JSON API for list & fuzzy search

header('Content-Type: application/json; charset=utf-8');

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

function fuzzyMatch($needle, $haystack)
{
    $needle = mb_strtolower(trim($needle));
    $haystack = mb_strtolower(trim($haystack));

    if ($needle === '') {
        return true;
    }

    if (strpos($haystack, $needle) !== false) {
        return true;
    }

    $distance = levenshtein($needle, $haystack);
    $threshold = max(1, (int)floor(strlen($needle) / 3));

    return $distance <= $threshold;
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$songs = loadSongs($songsFile);

switch ($action) {
    case 'list':
        echo json_encode($songs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;

    case 'search':
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        if ($q === '') {
            echo json_encode($songs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;
        }
        $filtered = [];
        foreach ($songs as $song) {
            if (fuzzyMatch($q, $song['title']) || fuzzyMatch($q, $song['interpret'])) {
                $filtered[] = $song;
            }
        }
        echo json_encode($filtered, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;
}
