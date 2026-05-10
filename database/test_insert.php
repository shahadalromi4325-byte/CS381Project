<?php
include 'db_connection.php';

header('Content-Type: text/html; charset=utf-8');

// Use a prepared statement — NEVER concatenate user data into SQL
$sql = "INSERT INTO ebooks (title, author, category, format, size, icon) 
        VALUES (:title, :author, :category, :format, :size, :icon)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title'    => 'Advanced Web Development',
        ':author'   => 'Shahad Ahmed',
        ':category' => 'Science',
        ':format'   => 'PDF',
        ':size'     => '4.2 MB',
        ':icon'     => 'fa-file-code'
    ]);

    $newId = $pdo->lastInsertId();
    echo "✅ Success: New record inserted with ID = " . $newId;

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>