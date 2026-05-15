<?php
session_start();

// If already logged in as admin, redirect
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header('Location: admin_dashboard.php');
    exit;
}

include '../database/db_connection.php';

$error   = '';
$success = '';

// Secret key — change this to something only you know
define('ADMIN_SECRET', 'YIC@Admin2025');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name   = trim($_POST['full_name']    ?? '');
    $email       = trim($_POST['email']        ?? '');
    $password    = trim($_POST['password']     ?? '');
    $secret      = trim($_POST['secret_key']   ?? '');

    // Validate secret key first
    if ($secret !== ADMIN_SECRET) {
        $error = 'Invalid secret key.';
    } elseif (empty($full_name) || empty($email) || empty($password)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        try {
            // Check if email exists
            $check = $pdo->prepare("SELECT user_id FROM users WHERE email = :email LIMIT 1");
            $check->execute([':email' => $email]);
            if ($check->fetch()) {
                $error = 'This email is already registered.';
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, role) VALUES (:name, :email, :password, 'admin')");
                $stmt->execute([':name' => $full_name, ':email' => $email, ':password' => $hashed]);

                // Auto login
                $_SESSION['user_id']   = $pdo->lastInsertId();
                $_SESSION['full_name'] = $full_name;
                $_SESSION['email']     = $email;
                $_SESSION['role']      = 'admin';

                header('Location: admin_dashboard.php');
                exit;
            }
        } catch (PDOException $e) {
            $error = 'Server error. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Register – YIC Library</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">
  <link rel="stylesheet" href="../assets/css/login.css">
  <style>
    .admin-notice {
      background: rgba(79,142,247,.15);
      border: 1px solid rgba(79,142,247,.4);
      color: #4f8ef7;
      padding: 10px 16px;
      border-radius: 8px;
      font-size: 13px;
      margin-bottom: 16px;
      text-align: center;
    }
    .error-msg {
      background: rgba(224,91,91,.15);
      border: 1px solid rgba(224,91,91,.4);
      color: #e05b5b;
      padding: 10px 16px;
      border-radius: 8px;
      font-size: 13px;
      margin-bottom: 16px;
      text-align: center;
    }
  </style>
</head>
<body>
  <header>
    <img src="../assets/images/logo.png" alt="YIC Library Logo">
  </header>
  <div class="overlay"></div>

  <div class="box">
    <div class="login">
      <div class="loginBx">

        <h3>
          <i class="fas fa-shield-halved"></i>
          Admin
          <i class="fas fa-user-gear"></i><br>
          <span>REGISTER</span>
        </h3>

        <?php if ($error): ?>
          <div class="error-msg"><i class="fas fa-circle-xmark"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="admin-notice">
          <i class="fas fa-lock"></i> Admin secret key required
        </div>

        <form method="POST">
          <input type="text"     name="full_name"  placeholder="Full Name"       required autocomplete="name">
          <input type="email"    name="email"       placeholder="Email Address"   required autocomplete="email">
          <input type="password" name="password"    placeholder="Password"        required autocomplete="new-password">
          <input type="password" name="secret_key"  placeholder="Admin Secret Key" required>
          <input type="submit" value="Create Admin Account">
          <div class="group">
            <a href="login.html">Back to Login</a>
          </div>
        </form>

      </div>
    </div>
  </div>
</body>
</html>