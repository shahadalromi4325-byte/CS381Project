<?php
session_start();
include '../database/db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

$title       = trim($_POST['title']       ?? '');
$author      = trim($_POST['author']      ?? '');
$category    = trim($_POST['category']    ?? '');
$call_number = trim($_POST['call_number'] ?? '');
$isbn        = trim($_POST['isbn']        ?? '');
$quantity    = (int)($_POST['quantity']   ?? 1);

if (empty($title) || empty($author)) {
    echo json_encode(['success' => false, 'message' => 'Title and author are required']);
    exit;
}

try {
    $stmt = $pdo->prepare(
        "INSERT INTO books (title, author, category, call_number, isbn, quantity, available)
         VALUES (:title, :author, :category, :call_number, :isbn, :quantity, :quantity)"
    );
    $stmt->execute([
        ':title'       => $title,
        ':author'      => $author,
        ':category'    => $category,
        ':call_number' => $call_number,
        ':isbn'        => $isbn ?: null,
        ':quantity'    => $quantity,
    ]);
    echo json_encode(['success' => true, 'message' => 'Book added successfully', 'id' => $pdo->lastInsertId()]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to add book']);
}
?>