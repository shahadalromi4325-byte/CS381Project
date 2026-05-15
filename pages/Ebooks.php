<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.html"); exit(); }
include '../database/db_connection.php';

try {
    $stmt = $pdo->query("SELECT * FROM ebooks ORDER BY id ASC");
    $db_ebooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { $db_ebooks = []; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-Books – YIC Library</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link rel="stylesheet" href="../assets/css/Ebooks.css">
</head>
<body>
  <!-- No video/gif background — body gradient is the background -->
  <div class="logo"><img src="../assets/images/logo.png" class="logo-img" alt="YIC Logo"></div>

  <nav class="nav-links">
    <ul>
      <li><a class="links" href="../index.php"><i class="fas fa-home"></i> Home</a></li>
      <li><a class="links active" href="books.php"><i class="fas fa-book"></i> Books</a></li>
      <li><a class="links" href="Ebooks.php"><i class="fas fa-tablet-alt"></i> E-Books</a></li>
      <li><a class="links" href="../index.php#services"><i class="fas fa-cogs"></i> Services</a></li>
      <li><a class="links" href="borrowed-books.php"><i class="fas fa-bookmark"></i> My Books</a></li>
    </ul>
  </nav>


  <div class="books-catalog-container">
    <h1><i class="fas fa-tablet-alt"></i> E-Books Digital Library</h1>

    <div class="search-container">
      <input type="text" class="search-input" id="searchInput"
             placeholder="Search by title or author..."
             oninput="filterEbooks()">
    </div>

    <div class="books-grid" id="ebooksGrid">
      <?php foreach ($db_ebooks as $ebook): ?>
        <div class="book-card"
             data-title="<?= strtolower(htmlspecialchars($ebook['title'])) ?>"
             data-author="<?= strtolower(htmlspecialchars($ebook['author'])) ?>">

          <div class="book-icon">
            <i class="fas <?= htmlspecialchars($ebook['icon'] ?? 'fa-file-pdf') ?>"></i>
          </div>

          <h3><?= htmlspecialchars($ebook['title']) ?></h3>

          <div class="book-meta">
            <span><i class="fas fa-user"></i> <?= htmlspecialchars($ebook['author']) ?></span>
            <span><i class="fas fa-tag"></i> <?= htmlspecialchars($ebook['category'] ?? '—') ?></span>
            <span>
              <i class="fas fa-file"></i> <?= htmlspecialchars($ebook['format']) ?>
              &nbsp;|&nbsp;
              <i class="fas fa-hdd"></i> <?= htmlspecialchars($ebook['size'] ?? '—') ?>
            </span>
          </div>

          <?php if (!empty($ebook['file_path'])): ?>
            <a class="download-btn"
               href="../<?= htmlspecialchars($ebook['file_path']) ?>"
               download="<?= htmlspecialchars($ebook['title']) ?>.<?= strtolower($ebook['format']) ?>">
              <i class="fas fa-download"></i> Download <?= htmlspecialchars($ebook['format']) ?>
            </a>
          <?php else: ?>
            <button class="download-btn disabled" disabled>
              <i class="fas fa-clock"></i> Coming Soon
            </button>
          <?php endif; ?>

        </div>
      <?php endforeach; ?>

      <?php if (empty($db_ebooks)): ?>
        <p style="color:rgba(255,255,255,0.5);text-align:center;padding:60px;grid-column:1/-1;">
          <i class="fas fa-box-open" style="font-size:2.5rem;display:block;margin-bottom:12px;"></i>
          No e-books available.
        </p>
      <?php endif; ?>
    </div>
  </div>

  <script>
    function filterEbooks() {
      const q = document.getElementById('searchInput').value.toLowerCase();
      document.querySelectorAll('.book-card').forEach(card => {
        const match = card.dataset.title.includes(q) || card.dataset.author.includes(q);
        card.style.display = match ? '' : 'none';
      });
    }
  </script>
</body>
</html>