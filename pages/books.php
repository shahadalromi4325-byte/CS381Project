<?php
// Include the database connection
// Adjust path if your connection file is in a different folder
include '../api/db_connection.php'; 

// Fetch all physical books
$sql = "SELECT * FROM books";
$result = $conn->query($sql);
$db_books = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $db_books[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Books Catalog – YIC Library</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">
  <link rel="stylesheet" href="/assets/css/styles.css">
  <link rel="stylesheet" href="/assets/css/books.css">
</head>

<body>
  <img src="/assets/images/animation.gif" alt="library background" class="hero-gif">
  <div class="overlay"></div>

  <div class="logo">
    <img src="/assets/images/logo.png" alt="YIC Library Logo" class="logo-img">
  </div>

  <div class="profile-circle">
    <a class="link" href="/pages/login.html"><i class="fas fa-user"></i></a>
  </div>

  <nav class="nav-links">
    <ul>
      <li><a class="links" href="/index.html"><i class="fas fa-home"></i> Home</a></li>
      <li><a class="links active" href="/pages/books.php"><i class="fas fa-book"></i> Books</a></li>
      <li><a class="links" href="/pages/Ebooks.php"><i class="fas fa-book-reader"></i> E-Books</a></li>
      <li><a class="links" href="/index.html#services"><i class="fas fa-cogs"></i> Services</a></li>
      <li><a class="links" href="/pages/borrowed-books.html"><i class="fas fa-bookmark"></i> My Books</a></li>
    </ul>
  </nav>

  <div class="books-catalog-container">
    <h1><i class="fas fa-book"></i> Books Catalog</h1>

    <div class="search-container">
      <input type="text" id="searchInput" placeholder="Search books..." class="search-input" onkeyup="filterBooks()">
      <select id="categoryFilter" class="filter-select" onchange="filterBooks()">
        <option value="">All Categories</option>
        <option value="science">Science</option>
        <option value="fiction">Fiction</option>
        <option value="reference">Reference</option>
        <option value="biography">Biography</option>
        <option value="history">History</option>
      </select>
    </div>

    <div class="results-info"><span id="resultCount">Loading books...</span></div>
    <div class="books-grid" id="booksContainer"></div>
    <div id="noResults" class="no-results" style="display:none;"><p>No books found.</p></div>
  </div>

  <script>
    // Inject PHP data into JavaScript
    const booksData = <?php echo json_encode($db_books); ?>;
  </script>
  <script src="/assets/js/books.js"></script>
</body>
</html>