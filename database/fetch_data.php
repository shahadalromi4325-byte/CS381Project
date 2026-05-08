<?php
include 'db_connection.php';

$type = $_GET['type'] ?? 'books'; // Determine if we want physical books or ebooks
$table = ($type === 'ebooks') ? 'ebooks' : 'books';

$sql = "SELECT * FROM $table";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);
$conn->close();
?>