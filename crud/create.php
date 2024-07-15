<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../database.php');

$data = json_decode(file_get_contents('php://input'), true);

$user_id = $data['userId'];
$content = $data['content'];
$date = $data['dateInput']; 

$encodedContent = base64_encode($content);

// Convert the date to SQL date format
$date = date('Y-m-d', strtotime($date));

// Prepare the SQL query
$sql = "INSERT INTO entries (user_id, entry_date, content";

// Conditionally include sentiment in the SQL query
if (isset($data['sentiment'])) {
    $sentiment = $data['sentiment'];
    $sql .= ", sentiment_id";
}

$sql .= ") VALUES ('$user_id', '$date','$encodedContent'";

if (isset($sentiment)) {
    $sql .= ", '$sentiment'";
}

$sql .= ")";

$result = mysqli_query($conn, $sql);

if ($result) {
    $entry_id = mysqli_insert_id($conn);

    // Conditionally save the images to the medias table
    if (isset($data['images'])) {
        $images = $data['images'];
        foreach ($images as $image) {
            $sql = "INSERT INTO medias (entry_id, media_content) VALUES ('$entry_id', '$image')";
            mysqli_query($conn, $sql);
        }
    }

    // Conditionally save the tag names to the tags table
    if (isset($data['tags'])) {
        $tags = $data['tags'];
        foreach ($tags as $tag) {
            $sql = "INSERT INTO tags (entry_id, tag) VALUES ('$entry_id', '$tag')";
            mysqli_query($conn, $sql);
        }
    }

    echo json_encode(['success' => true, 'redirect' => '../home.php']);
    exit;
} else {
    echo json_encode(['error' => mysqli_error($conn)]);
    exit;
}
?>