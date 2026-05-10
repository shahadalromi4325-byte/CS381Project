<?php
include '../api/db_connection.php'; 

// Fetch all digital books
$sql = "SELECT * FROM ebooks";
$result = $conn->query($sql);
$db_ebooks = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $db_ebooks[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-Books – YIC Library</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">
  <link rel="stylesheet" href="/assets/css/styles.css">
  <link rel="stylesheet" href="/assets/css/Ebooks.css">
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
      <li><a class="links active" href="/pages/Ebooks.php"><i class="fas fa-book-reader"></i> E-Books</a></li>
      <li><a class="links" href="/pages/borrowed-books.html"><i class="fas fa-bookmark"></i> My Books</a></li>
    </ul>
  </nav>

  <div class="books-catalog-container">
    <h1>📚 E-Books Digital Library</h1>

    <div class="search-container">
      <input type="text" id="searchInput" class="search-input" placeholder="Search digital titles...">
      <select id="categoryFilter" class="filter-select">
        <option value="">All Categories</option>
        <option value="science">Science & Tech</option>
        <option value="arts">Arts & Culture</option>
        <option value="fiction">Fiction</option>
        <option value="history">History</option>
      </select>
    </div>

    <div id="ebooksGrid" class="books-grid"></div>
  </div>

  <script>
    // Inject PHP data into JavaScript
    const ebooksData = <?php echo json_encode($db_ebooks); ?>;
  </script>
  <script src="/assets/js/Ebooks.js"></script>
</body>
</html>