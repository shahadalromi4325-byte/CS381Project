<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.html"); exit(); }
include '../database/db_connection.php'; 

$stmt = $pdo->query("SELECT * FROM fines");
$fines = $stmt->fetchAll();
$total = 0;
foreach($fines as $f) { if($f['status'] == 'Unpaid') $total += $f['amount']; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pay Fines – YIC Library</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="../assets/css/fines.css">
</head>
<body>
  <img src="../assets/images/animation.gif" class="hero-gif">
  <div class="overlay"></div>
  <div class="logo"><img src="../assets/images/logo.png" class="logo-img"></div>

  <div class="fines-container">
    <h1><i class="fas fa-file-invoice-dollar"></i> Library Fines</h1>
    <div class="fines-summary">
      <div class="summary-card">
        <h3>Total Balance</h3>
        <p class="amount">$<?= number_format($total, 2) ?></p>
      </div>
    </div>
    <div class="fines-list">
      <?php foreach ($fines as $fine): ?>
        <div class="fine-item">
          <div class="fine-info">
            <h3><?= htmlspecialchars($fine['book_title']) ?></h3>
            <p>Due: <?= $fine['due_date'] ?></p>
            <p class="fine-amount">$<?= number_format($fine['amount'], 2) ?></p>
          </div>
          <div class="fine-actions">
            <span class="status-badge <?= strtolower($fine['status']) ?>"><?= $fine['status'] ?></span>
            <?php if($fine['status'] == 'Unpaid'): ?>
              <button class="pay-btn">Pay Now</button>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>