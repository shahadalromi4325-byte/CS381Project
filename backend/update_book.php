<?php
session_start();
include '../database/db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

$id          = (int)($_POST['id']          ?? 0);
$title       = trim($_POST['title']        ?? '');
$author      = trim($_POST['author']       ?? '');
$category    = trim($_POST['category']     ?? '');
$call_number = trim($_POST['call_number']  ?? '');
$isbn        = trim($_POST['isbn']         ?? '');
$quantity    = (int)($_POST['quantity']    ?? 1);
$available   = (int)($_POST['available']   ?? 0);

if (!$id || empty($title) || empty($author)) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

try {
    $stmt = $pdo->prepare(
        "UPDATE books SET title=:title, author=:author, category=:category,
         call_number=:call_number, isbn=:isbn, quantity=:quantity, available=:available
         WHERE id=:id"
    );
    $stmt->execute([
        ':title'       => $title,
        ':author'      => $author,
        ':category'    => $category,
        ':call_number' => $call_number,
        ':isbn'        => $isbn ?: null,
        ':quantity'    => $quantity,
        ':available'   => $available,
        ':id'          => $id,
    ]);
    echo json_encode(['success' => true, 'message' => 'Book updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to update book']);
}
?>