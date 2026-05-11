<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.html"); exit(); }
include '../database/db_connection.php'; 

try {
    $stmt = $pdo->query("SELECT * FROM ebooks");
    $db_ebooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { $db_ebooks = []; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>E-Books – YIC Library</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="../assets/css/Ebooks.css">
</head>
<body>
  <img src="../assets/images/animation.gif" class="hero-gif">
  <div class="overlay"></div>
  <div class="logo"><img src="../assets/images/logo.png" class="logo-img"></div>

  <nav class="nav-links">
    <ul>
      <li><a class="links" href="../index.php"><i class="fas fa-home"></i> Home</a></li>
      <li><a class="links" href="books.php"><i class="fas fa-book"></i> Books</a></li>
      <li><a class="links active" href="Ebooks.php"><i class="fas fa-book-reader"></i> E-Books</a></li>
      <li><a class="links" href="borrowed-books.php"><i class="fas fa-bookmark"></i> My Books</a></li>
    </ul>
  </nav>

  <div class="books-catalog-container">
    <h1>📚 E-Books Digital Library</h1>
    <div class="books-grid">
      <?php foreach ($db_ebooks as $ebook): ?>
        <div class="book-card">
          <div class="book-icon"><i class="fas fa-file-pdf"></i></div>
          <h3><?= htmlspecialchars($ebook['title']) ?></h3>
          <div class="book-meta">
              <span><i class="fas fa-user"></i> <?= htmlspecialchars($ebook['author']) ?></span>
              <span><i class="fas fa-hdd"></i> <?= $ebook['size'] ?></span>
          </div>
          <button class="download-btn">Download <?= $ebook['format'] ?></button>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>