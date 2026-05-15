<?php
session_start();
include '../database/db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

$id = (int)($_POST['id'] ?? 0);

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'Invalid book ID']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM books WHERE id = :id");
    $stmt->execute([':id' => $id]);
    echo json_encode(['success' => true, 'message' => 'Book deleted successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to delete book']);
}
?>