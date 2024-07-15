<?php

header('Content-Type: application/json');

try {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    include('../database.php');

    $data = json_decode(file_get_contents('php://input'), true);

    $entry_id = $data['entryId'];

    // Prepare the SQL query to delete from medias
    $sql = "DELETE FROM medias WHERE entry_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $entry_id);
    $result = $stmt->execute();

    if (!$result) {
        throw new Exception(mysqli_error($conn));
    }

    // Prepare the SQL query to delete from tags
    $sql = "DELETE FROM tags WHERE entry_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $entry_id);
    $result = $stmt->execute();

    if (!$result) {
        throw new Exception(mysqli_error($conn));
    }

    // Prepare the SQL query to delete from entries
    $sql = "DELETE FROM entries WHERE entry_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $entry_id);
    $result = $stmt->execute();

    if (!$result) {
        throw new Exception(mysqli_error($conn));
    }

    echo json_encode(['success' => true, 'redirect' => 'home.php']);
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}