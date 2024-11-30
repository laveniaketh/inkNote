<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../database.php');

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

$entry_id = $data['entry_id'];
$user_id = $data['userId'];
$content = $data['content'];
$date = $data['dateInput'];

$encodedContent = base64_encode($content);

// Convert the date to SQL date format
$date = date('Y-m-d', strtotime($date));

// Prepare the SQL query
$stmt = $conn->prepare("UPDATE entries SET entry_date = ?, content = ?, sentiment_id = ? WHERE entry_id = ?");
$stmt->bind_param("sssi", $date, $encodedContent, $sentiment, $entry_id);

if (isset($data['sentiment'])) {
    $sentiment = $data['sentiment'];
}

if ($stmt->execute()) {
    // Conditionally update the images in the medias table

    if (count($data['images']) == 0) {
        // Delete all images for this entry
        $stmt = $conn->prepare("DELETE FROM medias WHERE entry_id = ?");
        if ($stmt === false) {
            die('prepare() failed: ' . $conn->error);
        }
        $stmt->bind_param("i", $entry_id);
        if ($stmt->execute() === false) {
            die('execute() failed: ' . $stmt->error);
        }
    } elseif (count($data['images']) > 0) {
        $images = $data['images'];

        // Delete all images for this entry
        $stmt = $conn->prepare("DELETE FROM medias WHERE entry_id = ?");
        if ($stmt === false) {
            die('prepare() failed: ' . $conn->error);
        }
        $stmt->bind_param("i", $entry_id);
        if ($stmt->execute() === false) {
            die('execute() failed: ' . $stmt->error);
        }

        // Insert new images
        foreach ($images as $image) {
            $stmt = $conn->prepare("INSERT INTO medias (media_content, entry_id) VALUES (?, ?)");
            if ($stmt === false) {
                die('prepare() failed: ' . $conn->error);
            }
            $stmt->bind_param("si", $image, $entry_id);
            if ($stmt->execute() === false) {
                die('execute() failed: ' . $stmt->error);
            }
        }
    }

    if (count($data['tags']) == 0) {
        // Delete all tags for this entry
        $stmt = $conn->prepare("DELETE FROM tags WHERE entry_id = ?");
        if ($stmt === false) {
            die('prepare() failed: ' . $conn->error);
        }
        $stmt->bind_param("i", $entry_id);
        if ($stmt->execute() === false) {
            die('execute() failed: ' . $stmt->error);
        }
    } elseif (count($data['tags']) > 0) {
        $tags = $data['tags'];

        // Delete all tags for this entry
        $stmt = $conn->prepare("DELETE FROM tags WHERE entry_id = ?");
        if ($stmt === false) {
            die('prepare() failed: ' . $conn->error);
        }
        $stmt->bind_param("i", $entry_id);
        if ($stmt->execute() === false) {
            die('execute() failed: ' . $stmt->error);
        }

        // Insert new tags
        foreach ($tags as $tag) {
            $stmt = $conn->prepare("INSERT INTO tags (tag, entry_id) VALUES (?, ?)");
            if ($stmt === false) {
                die('prepare() failed: ' . $conn->error);
            }
            $stmt->bind_param("si", $tag, $entry_id);
            if ($stmt->execute() === false) {
                die('execute() failed: ' . $stmt->error);
            }
        }
    }


    echo json_encode(['success' => true, 'redirect' => '../home.php']);
    exit;
} else {
    echo json_encode(['error' => $conn->error]);
    exit;
}
