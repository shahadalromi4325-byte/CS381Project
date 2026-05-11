<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.html"); exit(); }
include '../database/db_connection.php'; 

$sql = "SELECT b.title, b.author, bb.borrow_date, bb.due_date, bb.status 
        FROM books b 
        JOIN borrowed_books bb ON b.id = bb.book_id";
$stmt = $pdo->query($sql);
$borrowed_list = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Borrowed Books – YIC Library</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="../assets/css/borrowed-books.css">
</head>
<body>
  <img src="../assets/images/animation.gif" class="hero-gif">
  <div class="overlay"></div>
  <div class="logo"><img src="../assets/images/logo.png" class="logo-img"></div>

  <nav class="nav-links">
    <ul>
      <li><a class="links" href="../index.php"><i class="fas fa-home"></i> Home</a></li>
      <li><a class="links" href="books.php"><i class="fas fa-book"></i> Books</a></li>
      <li><a class="links active" href="borrowed-books.php"><i class="fas fa-bookmark"></i> My Books</a></li>
    </ul>
  </nav>

  <div class="borrowed-container">
    <h1><i class="fas fa-book-reader"></i> My Borrowed Books</h1>
    <div class="borrowed-list">
      <?php foreach ($borrowed_list as $book): ?>
        <div class="borrowed-item">
          <div class="item-info">
            <h3><?= htmlspecialchars($book['title']) ?></h3>
            <p>By <?= htmlspecialchars($book['author']) ?></p>
            <div class="dates">
              <span><strong>Due:</strong> <?= $book['due_date'] ?></span>
            </div>
          </div>
          <div class="status-badge <?= strtolower($book['status']) ?>"><?= $book['status'] ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>