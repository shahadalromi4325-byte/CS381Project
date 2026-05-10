<?php
// أول سطر دايماً — تحقق من الصلاحية
include '../backend/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - YIC Library</title>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($_SESSION['full_name']) ?> 👋</h1>
    <p>Role: <strong>Admin</strong></p>

    <a href="../backend/logout.php">Logout</a>
</body>
</html>