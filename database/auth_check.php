<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: ../pages/login.php");
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.html");
    exit;
}
?>