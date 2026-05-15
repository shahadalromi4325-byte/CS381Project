<?php
session_start();
include '../database/db_connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

$userId = (int)$_SESSION['user_id'];
$bookId = (int)($_POST['book_id'] ?? 0);

if (!$bookId) {
    echo json_encode(['success' => false, 'message' => 'Invalid book']);
    exit;
}

try {
    // Check availability
    $check = $pdo->prepare("SELECT available FROM books WHERE id = :id LIMIT 1");
    $check->execute([':id' => $bookId]);
    $book = $check->fetch();

    if (!$book || (int)$book['available'] <= 0) {
        echo json_encode(['success' => false, 'message' => 'Book is not available']);
        exit;
    }

    // Check if user already borrowed this book and not returned
    $dup = $pdo->prepare("SELECT borrow_id FROM borrowed_books WHERE user_id=:uid AND book_id=:bid AND status IN ('active','overdue') LIMIT 1");
    $dup->execute([':uid' => $userId, ':bid' => $bookId]);
    if ($dup->fetch()) {
        echo json_encode(['success' => false, 'message' => 'You already have this book borrowed']);
        exit;
    }

    // Insert borrow record (due date = 14 days from now)
    $stmt = $pdo->prepare("
        INSERT INTO borrowed_books (user_id, book_id, borrow_date, due_date, status)
        VALUES (:uid, :bid, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 14 DAY), 'active')
    ");
    $stmt->execute([':uid' => $userId, ':bid' => $bookId]);

    // Decrement available count
    $upd = $pdo->prepare("UPDATE books SET available = available - 1 WHERE id = :id");
    $upd->execute([':id' => $bookId]);

    echo json_encode(['success' => true, 'message' => 'Book borrowed successfully! Due in 14 days.']);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again.']);
}
?>