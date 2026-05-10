<?php
// 1. Connect to the database
include '../api/db_connection.php'; 

// 2. SQL Query: Join 'books' and 'borrowed_books' to get the titles
// We match the 'id' from books with 'book_id' from borrowed_books
$sql = "SELECT b.title, b.author, bb.borrow_date, bb.due_date, bb.status 
        FROM books b 
        JOIN borrowed_books bb ON b.id = bb.book_id";

$result = $conn->query($sql);
$borrowed_list = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $borrowed_list[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Borrowed Books – YIC Library</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">
  <link rel="stylesheet" href="/assets/css/styles.css">
  <link rel="stylesheet" href="/assets/css/borrowed-books.css">
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
      <li><a class="links" href="/pages/books.php"><i class="fas fa-book"></i> Books</a></li>
      <li><a class="links" href="/pages/Ebooks.php"><i class="fas fa-book-reader"></i> E-Books</a></li>
      <li><a class="links" href="/index.html#services"><i class="fas fa-cogs"></i> Services</a></li>
      <li><a class="links active" href="/pages/borrowed-books.php"><i class="fas fa-bookmark"></i> My Books</a></li>
    </ul>
  </nav>

  <div class="borrowed-container">
    <h1><i class="fas fa-book-reader"></i> My Borrowed Books</h1>
    
    <div class="borrowed-count">
      <span>Showing <?php echo count($borrowed_list); ?> record(s)</span>
    </div>

    <div class="borrowed-list">
      <?php if (empty($borrowed_list)): ?>
        <div class="no-borrowed">
          <p>📭 Your borrowed list is currently empty.</p>
          <p>Visit the <a href="/pages/books.php">Catalog</a> to find something to read!</p>
        </div>
      <?php else: ?>
        <?php foreach ($borrowed_list as $book): ?>
          <div class="borrowed-item">
            <div class="item-info">
              <h3><?php echo htmlspecialchars($book['title']); ?></h3>
              <p>By <?php echo htmlspecialchars($book['author']); ?></p>
              <div class="dates">
                <span><strong>Borrowed:</strong> <?php echo $book['borrow_date']; ?></span>
                <span><strong>Due:</strong> <?php echo $book['due_date']; ?></span>
              </div>
            </div>
            <div class="status-badge <?php echo strtolower($book['status']); ?>">
              <?php echo ucfirst($book['status']); ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>