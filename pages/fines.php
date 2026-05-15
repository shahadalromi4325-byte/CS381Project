<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.html"); exit(); }
include '../database/db_connection.php';

$userId = (int)$_SESSION['user_id'];

// ── Fetch only current user's fines ─────────────────────────
$stmt = $pdo->prepare("
    SELECT f.fine_id, f.amount, f.reason, f.status, f.created_at
    FROM fines f
    WHERE f.user_id = :uid
    ORDER BY f.fine_id DESC
");
$stmt->execute([':uid' => $userId]);
$fines = $stmt->fetchAll();

$total = 0;
foreach ($fines as $f) {
    if ($f['status'] === 'unpaid') $total += $f['amount'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Fines – YIC Library</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="../assets/css/fines.css">
</head>
<body>
  <div class="logo"><img src="../assets/images/logo.png" class="logo-img"></div>

  <nav class="nav-links">
    <ul>
      <li><a class="links" href="../index.php"><i class="fas fa-home"></i> Home</a></li>
      <li><a class="links" href="books.php"><i class="fas fa-book"></i> Books</a></li>
      <li><a class="links" href="borrowed-books.php"><i class="fas fa-bookmark"></i> My Books</a></li>
      <li><a class="links active" href="fines.php"><i class="fas fa-file-invoice-dollar"></i> Fines</a></li>
    </ul>
  </nav>

  <div class="fines-container">
    <h1><i class="fas fa-file-invoice-dollar"></i> My Library Fines</h1>

    <div class="fines-summary">
      <div class="summary-card">
        <h3>Total Unpaid Balance</h3>
        <p class="amount">$<?= number_format($total, 2) ?></p>
      </div>
    </div>

    <?php if (empty($fines)): ?>
      <div style="text-align:center;padding:60px;color:#888;">
        <i class="fas fa-circle-check" style="font-size:48px;margin-bottom:16px;display:block;color:#3ecf8e;"></i>
        <p>No fines on your account.</p>
      </div>
    <?php else: ?>
    <div class="fines-list">
      <?php foreach ($fines as $fine): ?>
        <div class="fine-item">
          <div class="fine-info">
            <h3><i class="fas fa-exclamation-circle"></i> Fine #<?= $fine['fine_id'] ?></h3>
            <p><?= htmlspecialchars($fine['reason'] ?? 'Library fine') ?></p>
            <p class="fine-amount">$<?= number_format($fine['amount'], 2) ?></p>
            <p style="font-size:12px;color:#888;margin-top:4px;">
              <i class="fas fa-calendar-alt"></i> <?= date('M d, Y', strtotime($fine['created_at'])) ?>
            </p>
          </div>
          <div class="fine-actions">
            <span class="status-badge <?= strtolower($fine['status']) ?>"><?= $fine['status'] ?></span>
            <?php if ($fine['status'] === 'unpaid'): ?>
              <button class="pay-btn"><i class="fas fa-credit-card"></i> Pay Now</button>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</body>
</html>