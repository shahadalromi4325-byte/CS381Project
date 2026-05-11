<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.html"); exit(); }
include '../database/db_connection.php'; 

try {
    $stmt = $pdo->query("SELECT * FROM books");
    $db_books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { $db_books = []; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Books Catalog – YIC Library</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="../assets/css/books.css">
</head>
<body>
  <img src="../assets/images/Video.webm" alt="library background" class="hero-gif">
  <div class="overlay"></div>
  <div class="logo">
    <img src="../assets/images/logo.png" alt="YIC Library Logo" class="logo-img">
  </div>

  <nav class="nav-links">
    <ul>
      <li><a class="links" href="../index.php"><i class="fas fa-home"></i> Home</a></li>
      <li><a class="links active" href="books.php"><i class="fas fa-book"></i> Books</a></li>
      <li><a class="links" href="Ebooks.php"><i class="fas fa-book-reader"></i> E-Books</a></li>
      <li><a class="links" href="../index.php#services"><i class="fas fa-cogs"></i> Services</a></li>
      <li><a class="links" href="borrowed-books.php"><i class="fas fa-bookmark"></i> My Books</a></li>
    </ul>
  </nav>

  <div class="books-catalog-container">
    <h1><i class="fas fa-book"></i> Books Catalog</h1>
    <div class="books-grid">
      <?php foreach ($db_books as $book): ?>
        <div class="book-card">
          <div class="book-icon"><i class="fas fa-book"></i></div>
          <h3><?= htmlspecialchars($book['title']) ?></h3>
          <p>By <?= htmlspecialchars($book['author']) ?></p>
          <p class="status">Available: <?= $book['available'] ?>/<?= $book['quantity'] ?></p>
          <button class="borrow-btn">Borrow Now</button>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>