<?php
include '../api/db_connection.php'; 

// Fetch fines from the database
$sql = "SELECT id, book_title, amount, due_date, status FROM fines";
$result = $conn->query($sql);
$fines_data = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $fines_data[] = $row;
    }
}

// Calculate total balance
$total_balance = 0;
foreach ($fines_data as $fine) {
    if ($fine['status'] === 'Unpaid') {
        $total_balance += $fine['amount'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pay Fines</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">
  <link rel="stylesheet" href="/assets/css/styles.css">
  <link rel="stylesheet" href="/assets/css/fines.css">
</head>

<body>
  <img src="/assets/images/animation.gif" alt="library background" class="hero-gif">
  <div class="overlay"></div>

  <div class="logo">
    <img src="/assets/images/logo.png" alt="YIC Library Logo" class="logo-img">
  </div>

  <nav class="nav-links">
    <ul>
      <li><a class="links" href="/index.html"><i class="fas fa-home"></i> Home</a></li>
      <li><a class="links" href="/pages/books.php"><i class="fas fa-book"></i> Books</a></li>
      <li><a class="links" href="/pages/Ebooks.php"><i class="fas fa-book-reader"></i> E-Books</a></li>
      <li><a class="links" href="/pages/borrowed-books.php"><i class="fas fa-bookmark"></i> My Books</a></li>
    </ul>
  </nav>

  <div class="fines-container">
    <h1><i class="fas fa-file-invoice-dollar"></i> Library Fines</h1>
    
    <div class="fines-summary">
      <div class="summary-card">
        <h3>Total Balance</h3>
        <p class="amount">$<?php echo number_format($total_balance, 2); ?></p>
      </div>
    </div>

    <div class="fines-list">
      <?php if (empty($fines_data)): ?>
        <p class="no-fines">✅ No outstanding fines found. Keep up the good work!</p>
      <?php else: ?>
        <?php foreach ($fines_data as $fine): ?>
          <div class="fine-item">
            <div class="fine-info">
              <h3><?php echo htmlspecialchars($fine['book_title']); ?></h3>
              <p>Due Date: <?php echo $fine['due_date']; ?></p>
              <p class="fine-amount">$<?php echo number_format($fine['amount'], 2); ?></p>
            </div>
            <div class="fine-actions">
              <span class="status-badge <?php echo strtolower($fine['status']); ?>">
                <?php echo ucfirst($fine['status']); ?>
              </span>
              <?php if ($fine['status'] === 'Unpaid'): ?>
                <button class="pay-btn" onclick="openPaymentModal(<?php echo $fine['id']; ?>, <?php echo $fine['amount']; ?>)">
                  Pay Now
                </button>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <div id="paymentModal" class="modal">
      <div class="modal-content">
          <span class="close-modal">&times;</span>
          <h2><i class="fas fa-credit-card"></i> Payment Details</h2>
          <form id="paymentForm">
              <input type="hidden" id="fineIdToPay">
              <div class="input-group">
                  <label>Cardholder Name</label>
                  <input type="text" placeholder="John Doe" required>
              </div>
              <div class="input-group">
                  <label>Card Number</label>
                  <input type="text" placeholder="1234 5678 9101 1121" required>
              </div>
              <button type="submit" class="confirm-pay-btn">Confirm Payment</button>
          </form>
      </div>
  </div>

  <script>
    function openPaymentModal(id, amount) {
        document.getElementById('paymentModal').style.display = 'block';
        document.getElementById('fineIdToPay').value = id;
    }

    document.querySelector('.close-modal').onclick = () => {
        document.getElementById('paymentModal').style.display = 'none';
    };

    document.getElementById('paymentForm').onsubmit = (e) => {
        e.preventDefault();
        alert('Payment successful! (Note: In a real app, you would now call a PHP script to update the DB status to "Paid")');
        location.reload();
    };
  </script>
</body>
</html>