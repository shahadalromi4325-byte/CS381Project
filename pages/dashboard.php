<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

include '../database/db_connection.php';

$userId   = (int)$_SESSION['user_id'];
$userName = htmlspecialchars($_SESSION['full_name'] ?? '');
$email    = htmlspecialchars($_SESSION['email'] ?? '');

// ── Stats ──
$totalBorrowed = $pdo->prepare("SELECT COUNT(*) FROM borrowed_books WHERE user_id = :uid");
$totalBorrowed->execute([':uid' => $userId]);
$totalBorrowed = $totalBorrowed->fetchColumn();

$activeBorrowed = $pdo->prepare("SELECT COUNT(*) FROM borrowed_books WHERE user_id = :uid AND status = 'active'");
$activeBorrowed->execute([':uid' => $userId]);
$activeBorrowed = $activeBorrowed->fetchColumn();

$overdueBorrowed = $pdo->prepare("SELECT COUNT(*) FROM borrowed_books WHERE user_id = :uid AND status = 'overdue'");
$overdueBorrowed->execute([':uid' => $userId]);
$overdueBorrowed = $overdueBorrowed->fetchColumn();

$unpaidFines = $pdo->prepare("SELECT COALESCE(SUM(amount),0) FROM fines WHERE user_id = :uid AND status = 'unpaid'");
$unpaidFines->execute([':uid' => $userId]);
$unpaidFines = $unpaidFines->fetchColumn();

// ── Borrowed books ──
$stmt = $pdo->prepare("
    SELECT b.title, b.author, b.category,
           bb.borrow_date, bb.due_date, bb.return_date, bb.status
    FROM borrowed_books bb
    JOIN books b ON bb.book_id = b.id
    WHERE bb.user_id = :uid
    ORDER BY bb.borrow_id DESC
");
$stmt->execute([':uid' => $userId]);
$borrowedList = $stmt->fetchAll();

// ── Fines ──
$fStmt = $pdo->prepare("
    SELECT f.amount, f.reason, f.status, f.created_at
    FROM fines f
    WHERE f.user_id = :uid
    ORDER BY f.fine_id DESC
");
$fStmt->execute([':uid' => $userId]);
$finesList = $fStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Dashboard – YIC Library</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">
  <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
  <video class="bg-video" autoplay muted loop playsinline>
  <source src="../assets/videos/background.mp4" type="video/mp4">
</video>

<!-- Sidebar -->
<aside class="sidebar">
  <div class="sidebar-logo">
    <img src="../assets/images/logo.png" alt="Logo">
    <div>
      <span>YIC Library</span>
      <small>Student Portal</small>
    </div>
  </div>

  <nav style="margin-top:16px;">
    <a class="nav-item active" href="#" onclick="showSection('overview', this)">
      <i class="fas fa-chart-pie"></i> Overview
    </a>
    <a class="nav-item" href="#" onclick="showSection('borrowed', this)">
      <i class="fas fa-bookmark"></i> My Books
    </a>
    <a class="nav-item" href="#" onclick="showSection('fines', this)">
      <i class="fas fa-file-invoice-dollar"></i> My Fines
    </a>
    <a class="nav-item" href="#" onclick="showSection('profile', this)">
      <i class="fas fa-user"></i> Profile
    </a>
    <hr style="border-color:var(--border);margin:12px 24px;">
    <a class="nav-item" href="../pages/books.php">
      <i class="fas fa-search"></i> Browse Books
    </a>
    <a class="nav-item" href="../pages/Ebooks.php">
      <i class="fas fa-tablet-alt"></i> E-Books
    </a>
    <a class="nav-item" href="../index.php">
      <i class="fas fa-home"></i> Home
    </a>
    <a class="nav-item" href="../backend/logout.php">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="user-badge">
      <div class="user-avatar"><?= strtoupper(substr($_SESSION['full_name'], 0, 1)) ?></div>
      <div class="user-info">
        <span><?= $userName ?></span>
        <small><i class="fas fa-graduation-cap"></i> Student</small>
      </div>
    </div>
  </div>
</aside>

<!-- Main -->
<main class="main">

  <!-- ── OVERVIEW ── -->
  <div id="sec-overview" class="section active">
    <div class="page-header">
      <h1><i class="fas fa-hand-wave"></i> Welcome back, <?= $userName ?>!</h1>
      <p>Here's a summary of your library activity</p>
    </div>

    <?php if ($overdueBorrowed > 0): ?>
    <div class="alert alert-danger">
      <i class="fas fa-triangle-exclamation"></i>
      You have <strong><?= $overdueBorrowed ?> overdue book(s)</strong>. Please return them to avoid additional fines.
    </div>
    <?php endif; ?>

    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-book"></i></div>
        <div class="stat-info">
          <h3><?= $totalBorrowed ?></h3>
          <p>Total Borrowed</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-bookmark"></i></div>
        <div class="stat-info">
          <h3><?= $activeBorrowed ?></h3>
          <p>Currently Active</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-clock"></i></div>
        <div class="stat-info">
          <h3><?= $overdueBorrowed ?></h3>
          <p>Overdue</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-file-invoice-dollar"></i></div>
        <div class="stat-info">
          <h3>$<?= number_format($unpaidFines, 2) ?></h3>
          <p>Unpaid Fines</p>
        </div>
      </div>
    </div>

    <!-- Recent borrowed -->
    <div class="panel">
      <div class="panel-header">
        <h2><i class="fas fa-clock-rotate-left"></i> Recent Borrowings</h2>
        <a href="#" onclick="showSection('borrowed', document.querySelector('.nav-item:nth-child(2)'));return false;"
           style="font-size:13px;color:var(--accent);text-decoration:none;">
          View all <i class="fas fa-arrow-right"></i>
        </a>
      </div>
      <div class="table-wrap">
        <?php $recent = array_slice($borrowedList, 0, 5); ?>
        <?php if (!empty($recent)): ?>
        <table>
          <thead>
            <tr><th>Book</th><th>Author</th><th>Due Date</th><th>Status</th></tr>
          </thead>
          <tbody>
            <?php foreach ($recent as $b): ?>
            <tr>
              <td><?= htmlspecialchars($b['title']) ?></td>
              <td><?= htmlspecialchars($b['author']) ?></td>
              <td><?= $b['due_date'] ?></td>
              <td><span class="badge badge-<?= $b['status'] ?>"><?= $b['status'] ?></span></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php else: ?>
        <div class="empty">
          <i class="fas fa-book-open"></i>
          <p>No borrowing history yet.</p>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- ── MY BOOKS ── -->
  <div id="sec-borrowed" class="section">
    <div class="page-header">
      <h1><i class="fas fa-bookmark"></i> My Borrowed Books</h1>
      <p>All books you have borrowed</p>
    </div>

    <div class="panel">
      <div class="table-wrap">
        <?php if (!empty($borrowedList)): ?>
        <table>
          <thead>
            <tr>
              <th>Title</th><th>Author</th><th>Category</th>
              <th>Borrow Date</th><th>Due Date</th><th>Return Date</th><th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($borrowedList as $b): ?>
            <tr>
              <td><?= htmlspecialchars($b['title']) ?></td>
              <td><?= htmlspecialchars($b['author']) ?></td>
              <td><?= htmlspecialchars($b['category'] ?? '—') ?></td>
              <td><?= $b['borrow_date'] ?></td>
              <td><?= $b['due_date'] ?></td>
              <td><?= $b['return_date'] ?? '—' ?></td>
              <td><span class="badge badge-<?= $b['status'] ?>"><?= $b['status'] ?></span></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php else: ?>
        <div class="empty">
          <i class="fas fa-book-open"></i>
          <p>You haven't borrowed any books yet.</p>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- ── FINES ── -->
  <div id="sec-fines" class="section">
    <div class="page-header">
      <h1><i class="fas fa-file-invoice-dollar"></i> My Fines</h1>
      <p>Total unpaid: <strong style="color:var(--danger)">$<?= number_format($unpaidFines, 2) ?></strong></p>
    </div>

    <div class="panel">
      <div class="table-wrap">
        <?php if (!empty($finesList)): ?>
        <table>
          <thead>
            <tr><th>Reason</th><th>Amount</th><th>Date</th><th>Status</th></tr>
          </thead>
          <tbody>
            <?php foreach ($finesList as $f): ?>
            <tr>
              <td><?= htmlspecialchars($f['reason'] ?? '—') ?></td>
              <td>$<?= number_format($f['amount'], 2) ?></td>
              <td><?= date('M d, Y', strtotime($f['created_at'])) ?></td>
              <td><span class="badge badge-<?= $f['status'] ?>"><?= $f['status'] ?></span></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php else: ?>
        <div class="empty">
          <i class="fas fa-circle-check"></i>
          <p>No fines on your account.</p>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- ── PROFILE ── -->
  <div id="sec-profile" class="section">
    <div class="page-header">
      <h1><i class="fas fa-user"></i> My Profile</h1>
      <p>Your account information</p>
    </div>

    <div class="profile-card">
      <div class="profile-avatar"><?= strtoupper(substr($_SESSION['full_name'], 0, 1)) ?></div>
      <div class="profile-info">
        <h2><?= $userName ?></h2>
        <p><i class="fas fa-envelope"></i> <?= $email ?></p>
        <span class="role-badge"><i class="fas fa-graduation-cap"></i> Student</span>
      </div>
    </div>

    <div class="panel">
      <div class="panel-header"><h2><i class="fas fa-chart-bar"></i> Activity Summary</h2></div>
      <div style="padding:24px;display:grid;grid-template-columns:repeat(2,1fr);gap:16px;">
        <div style="background:var(--surface);padding:16px;border-radius:10px;border:1px solid var(--border);">
          <p style="font-size:12px;color:var(--muted);">Total Books Borrowed</p>
          <h3 style="font-size:28px;margin-top:6px;"><?= $totalBorrowed ?></h3>
        </div>
        <div style="background:var(--surface);padding:16px;border-radius:10px;border:1px solid var(--border);">
          <p style="font-size:12px;color:var(--muted);">Currently Active</p>
          <h3 style="font-size:28px;margin-top:6px;"><?= $activeBorrowed ?></h3>
        </div>
        <div style="background:var(--surface);padding:16px;border-radius:10px;border:1px solid var(--border);">
          <p style="font-size:12px;color:var(--muted);">Overdue Books</p>
          <h3 style="font-size:28px;margin-top:6px;color:<?= $overdueBorrowed > 0 ? 'var(--danger)' : 'inherit' ?>">
            <?= $overdueBorrowed ?>
          </h3>
        </div>
        <div style="background:var(--surface);padding:16px;border-radius:10px;border:1px solid var(--border);">
          <p style="font-size:12px;color:var(--muted);">Unpaid Fines</p>
          <h3 style="font-size:28px;margin-top:6px;color:<?= $unpaidFines > 0 ? 'var(--danger)' : 'inherit' ?>">
            $<?= number_format($unpaidFines, 2) ?>
          </h3>
        </div>
      </div>
    </div>
  </div>

</main>

<script>
  function showSection(name, el) {
    document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
    document.getElementById('sec-' + name).classList.add('active');
    if (el) el.classList.add('active');
  }
</script>
</body>
</html>