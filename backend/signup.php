<?php
// backend/signup.php
session_start();
include '../database/db_connection.php';

// ── Only accept POST ────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/signup.html?error=Invalid+request');
    exit;
}

// ── Get and sanitize inputs ─────────────────────────────────
$full_name  = trim($_POST['full_name']  ?? '');
$email      = trim($_POST['email']      ?? '');
$student_id = trim($_POST['student_id'] ?? '');
$password   = trim($_POST['password']   ?? '');

// ── Validation ──────────────────────────────────────────────
if (empty($full_name) || empty($email) || empty($student_id) || empty($password)) {
    header('Location: ../pages/signup.html?error=All+fields+are+required');
    exit;
}

if (strlen($full_name) < 3) {
    header('Location: ../pages/signup.html?error=Name+must+be+at+least+3+characters');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../pages/signup.html?error=Invalid+email+address');
    exit;
}

if (!is_numeric($student_id) || $student_id <= 0) {
    header('Location: ../pages/signup.html?error=Student+ID+must+be+a+valid+number');
    exit;
}

if (strlen($password) < 6) {
    header('Location: ../pages/signup.html?error=Password+must+be+at+least+6+characters');
    exit;
}

try {
    // ── Check if email already exists ───────────────────────
    $check = $pdo->prepare("SELECT user_id FROM users WHERE email = :email LIMIT 1");
    $check->execute([':email' => $email]);

    if ($check->fetch()) {
        header('Location: ../pages/signup.html?error=This+email+is+already+registered');
        exit;
    }

    // ── Hash password (never store plain text) ──────────────
    // password_hash() as taught in Module 5
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // ── Insert new user — Prepared Statement (Lab 6) ────────
    $stmt = $pdo->prepare(
        "INSERT INTO users (full_name, email, password, role) 
         VALUES (:full_name, :email, :password, 'student')"
    );
    $stmt->execute([
        ':full_name' => $full_name,
        ':email'     => $email,
        ':password'  => $hashedPassword,
    ]);

    // ── Auto-login after signup ─────────────────────────────
    $newUserId = $pdo->lastInsertId();

    $_SESSION['user_id']   = $newUserId;
    $_SESSION['full_name'] = $full_name;
    $_SESSION['email']     = $email;
    $_SESSION['role']      = 'student';

    // Redirect to home page after successful signup
    header('Location: ../index.php');
    exit;

} catch (PDOException $e) {
    header('Location: ../pages/signup.html?error=Server+error.+Please+try+again');
    exit;
}
?>