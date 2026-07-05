<?php
// Simple API for handling song requests

$songsFile = __DIR__ . '/songs.json';

// Ensure file exists
if (!file_exists($songsFile)) {
    file_put_contents($songsFile, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$songs = json_decode(file_get_contents($songsFile), true);
if (!is_array($songs)) {
    $songs = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $requestedBy = isset($_POST['requested_by']) ? trim($_POST['requested_by']) : '';
    $interpret = isset($_POST['interpret']) ? trim($_POST['interpret']) : '';

    if ($title === '') {
        // Minimal error handling – redirect back
        header('Location: index.php');
        exit;
    }

    // Normalize title for matching
    $normalizedTitle = mb_strtolower($title, 'UTF-8');
    $foundIndex = null;

    foreach ($songs as $index => $song) {
        if (isset($song['title']) && mb_strtolower($song['title'], 'UTF-8') === $normalizedTitle) {
            $foundIndex = $index;
            break;
        }
    }

    if ($foundIndex !== null) {
        // Existing song: increase count and add requested_by if provided
        if (!isset($songs[$foundIndex]['count'])) {
            $songs[$foundIndex]['count'] = 0;
        }
        $songs[$foundIndex]['count']++;

        if (!isset($songs[$foundIndex]['requested_by']) || !is_array($songs[$foundIndex]['requested_by'])) {
            $songs[$foundIndex]['requested_by'] = [];
        }

        if ($requestedBy !== '') {
            if (!in_array($requestedBy, $songs[$foundIndex]['requested_by'], true)) {
                $songs[$foundIndex]['requested_by'][] = $requestedBy;
            }
        }

        // If interpret is newly provided and empty before, set it
        if ($interpret !== '' && empty($songs[$foundIndex]['interpret'])) {
            $songs[$foundIndex]['interpret'] = $interpret;
        }
    } else {
        // New song entry
        $songs[] = [
            'title'        => $title,
            'count'        => 1,
            'interpret'    => $interpret !== '' ? $interpret : '',
            'requested_by' => $requestedBy !== '' ? [$requestedBy] : [],
            'status'       => 'pending',
            'confirmed'    => false,
        ];
    }

    // Save back to file
    file_put_contents($songsFile, json_encode($songs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    // Redirect back to index
    header('Location: index.php');
    exit;
}

// Fallback: redirect to index
header('Location: index.php');
exit;
