<?php
include 'db_connection.php'; // Ensure path is correct

// 1. Check if the table exists and columns match
$sql = "INSERT INTO ebooks (title, author, category, format, size, icon) 
        VALUES ('Advanced Web Development', 'Shahad Ahmed', 'science', 'PDF', '4.2 MB', 'fa-file-code')";

if ($conn->query($sql) === TRUE) {
    echo "✅ Success: New record created successfully";
} else {
    // 2. This will print the EXACT error from the database
    echo "❌ Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>