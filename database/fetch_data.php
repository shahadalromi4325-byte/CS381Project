<?php
include 'db_connection.php';

header('Content-Type: application/json');

// --- Input Validation ---
// Only allow specific values to prevent SQL injection
$allowed_types = ['books', 'ebooks'];
$type = $_GET['type'] ?? 'books';

if (!in_array($type, $allowed_types)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid type. Must be "books" or "ebooks".']);
    exit;
}

$table = ($type === 'ebooks') ? 'ebooks' : 'books';

try {
    $stmt = $pdo->prepare("SELECT * FROM `$table` ORDER BY id ASC");
    $stmt->execute();

    $data = $stmt->fetchAll(); // PDO::FETCH_ASSOC is set globally

    if (empty($data)) {
        echo json_encode(['message' => 'No records found.', 'data' => []]);
    } else {
        echo json_encode(['data' => $data, 'count' => count($data)]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to retrieve data.']);
}
?>