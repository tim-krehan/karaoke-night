<?php
// api.php - Simple JSON API for potential extensions (search, list)

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

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$songs = loadSongs($songsFile);

switch ($action) {
    case 'list':
        echo json_encode($songs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;
}
