<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.html"); exit(); }
include '../database/db_connection.php';

try {
    $stmt = $pdo->query("SELECT * FROM books");
    $db_books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { $db_books = []; }

// Collect unique categories for the filter dropdown
$categories = array_unique(array_filter(array_column($db_books, 'category')));
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

  <!-- Background Video -->
  <video autoplay loop muted playsinline class="hero-gif">
    <source src="../assets/images/Video.webm" type="video/webm">
  </video>
  <div class="overlay"></div>

  <!-- Logo -->
  <div class="logo">
    <img src="../assets/images/logo.png" alt="YIC Library Logo" class="logo-img">
  </div>

  <!-- Navigation -->
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
    <h1><i class="fas fa-book"></i> Books Catalog</h1>

    <!-- Search & Filter -->
    <div class="search-container">
      <input type="text"
             class="search-input"
             id="searchInput"
             placeholder="Search by title or author..."
             onkeyup="filterBooks()">

      <select class="filter-select" id="categoryFilter" onchange="filterBooks()">
        <option value="">All Categories</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= strtolower(htmlspecialchars($cat)) ?>">
            <?= htmlspecialchars($cat) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- Results Count -->
    <p class="results-info" id="resultsInfo">
      Showing <?= count($db_books) ?> book(s)
    </p>

    <!-- Books Grid -->
    <div class="books-grid" id="booksGrid">
      <?php foreach ($db_books as $book): ?>
        <?php
          $avail    = (int)($book['available'] ?? 0);
          $qty      = (int)($book['quantity']  ?? 0);
          $coverUrl = !empty($book['cover_url']) ? htmlspecialchars($book['cover_url']) : '';
          $category = strtolower(htmlspecialchars($book['category'] ?? ''));
        ?>

        <div class="book-card"
             data-title="<?= strtolower(htmlspecialchars($book['title'])) ?>"
             data-author="<?= strtolower(htmlspecialchars($book['author'])) ?>"
             data-category="<?= $category ?>">

          <!-- Cover Image -->
          <div class="book-cover">
            <?php if ($coverUrl): ?>
              <img src="<?= $coverUrl ?>"
                   alt="<?= htmlspecialchars($book['title']) ?>"
                   loading="lazy"
                   onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
              <div class="book-icon-fallback" style="display:none;">
                <i class="fas fa-book"></i>
              </div>
            <?php else: ?>
              <div class="book-icon-fallback">
                <i class="fas fa-book"></i>
              </div>
            <?php endif; ?>
          </div>

          <!-- Book Info -->
          <div class="book-info">
            <h3><?= htmlspecialchars($book['title']) ?></h3>
            <p class="book-author"><i class="fas fa-user-pen"></i> <?= htmlspecialchars($book['author']) ?></p>

            <?php if (!empty($book['category'])): ?>
              <p class="book-category"><i class="fas fa-tag"></i> <?= htmlspecialchars($book['category']) ?></p>
            <?php endif; ?>

            <?php if ($avail > 1): ?>
              <p class="status available"><i class="fas fa-check-circle"></i> <?= $avail ?>/<?= $qty ?> Available</p>
            <?php elseif ($avail === 1): ?>
              <p class="status low"><i class="fas fa-exclamation-circle"></i> 1 Copy Left</p>
            <?php else: ?>
              <p class="status unavailable"><i class="fas fa-times-circle"></i> Not Available</p>
            <?php endif; ?>

            <button class="borrow-btn <?= $avail <= 0 ? 'disabled' : '' ?>"
                    <?= $avail <= 0 ? 'disabled' : "onclick=\"borrowBook({$book['id']})\"" ?>>
              <?php if ($avail <= 0): ?>
                <i class="fas fa-ban"></i> Unavailable
              <?php else: ?>
                <i class="fas fa-hand-holding-heart"></i> Borrow Now
              <?php endif; ?>
            </button>
          </div>

        </div>
      <?php endforeach; ?>

      <?php if (empty($db_books)): ?>
        <div class="no-books">
          <i class="fas fa-box-open"></i>
          <p>No books available at the moment.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <script>
    function filterBooks() {
      const query    = document.getElementById('searchInput').value.toLowerCase().trim();
      const category = document.getElementById('categoryFilter').value.toLowerCase();
      const cards    = document.querySelectorAll('.book-card');
      let visible    = 0;

      cards.forEach(card => {
        const titleMatch    = card.dataset.title.includes(query);
        const authorMatch   = card.dataset.author.includes(query);
        const categoryMatch = category === '' || card.dataset.category === category;

        if ((titleMatch || authorMatch) && categoryMatch) {
          card.style.display = '';
          visible++;
        } else {
          card.style.display = 'none';
        }
      });

      document.getElementById('resultsInfo').textContent =
        'Showing ' + visible + ' book(s)';
    }

    function borrowBook(bookId) {
      if (!confirm('Do you want to borrow this book?')) return;
      fetch('../backend/borrow_book.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'book_id=' + bookId
      })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
        if (data.success) location.reload();
      })
      .catch(() => alert('Something went wrong. Please try again.'));
    }
  </script>

</body>
</html>