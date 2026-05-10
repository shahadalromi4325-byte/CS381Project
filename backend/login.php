<?php
session_start();
include '../database/db_connection.php';

// ── Only accept POST ────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/login.html?error=Invalid+request');
    exit;
}

$email    = trim($_POST['email']    ?? '');
$password = trim($_POST['password'] ?? '');

// ── Basic validation ────────────────────────────────────────
if (empty($email) || empty($password)) {
    header('Location: ../pages/login.html?error=Email+and+password+are+required');
    exit;
}

try {
    // ── Fetch user by email — Prepared Statement (Lab 6) ────
    $stmt = $pdo->prepare(
        "SELECT user_id, full_name, email, password, role 
         FROM users 
         WHERE email = :email 
         LIMIT 1"
    );
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    // ── Verify password with password_verify() ──────────────
    if ($user && password_verify($password, $user['password'])) {

        // Save all needed data in session
        $_SESSION['user_id']   = $user['user_id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['email']     = $user['email'];
        $_SESSION['role']      = $user['role'];

        // ── Redirect based on role ──────────────────────────
        if ($user['role'] === 'admin') {
            header('Location: ../pages/admin_dashboard.php');
        } else {
            header('Location: ../index.php');
        }
        exit;

    } else {
        header('Location: ../pages/login.html?error=Invalid+email+or+password');
        exit;
    }

} catch (PDOException $e) {
    header('Location: ../pages/login.html?error=Server+error.+Please+try+again');
    exit;
}
?>