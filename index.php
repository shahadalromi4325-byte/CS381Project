<?php
// ========== SESSION CHECK ==========
session_start();

$isLoggedIn = isset($_SESSION['user_id']);
$userName   = $isLoggedIn ? htmlspecialchars($_SESSION['full_name'] ?? '') : '';
$userRole   = $isLoggedIn ? ($_SESSION['role'] ?? '') : '';

// ========== FETCH POPULAR BOOKS FROM DATABASE ==========
include 'database/db_connection.php';

$popularBooks = [];
try {
    $stmt = $pdo->prepare("SELECT id, title, author, available, quantity FROM books ORDER BY available DESC LIMIT 5");
    $stmt->execute();
    $popularBooks = $stmt->fetchAll();
} catch (PDOException $e) {
    $popularBooks = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>YIC University Library</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
  <div class="overlay"></div>

  <!-- Background Video -->
  <img src="assets/images/animation.webm" alt="Background Animation" class="hero-gif">

  <!-- Logo -->
  <div class="logo">
    <img class="logo-img" src="assets/images/logo.png" alt="YIC Library Logo">
  </div>

  <div class="profile-circle">
    <?php if ($isLoggedIn): ?>
      <?php if ($userRole === 'admin'): ?>
        <a href="pages/admin_dashboard.php" class="link" title="Admin Dashboard">
          <i class="fas fa-user-shield"></i>
        </a>
      <?php else: ?>
        <a href="pages/dashboard.php" class="link" title="<?= $userName ?>">
          <i class="fas fa-user-circle"></i>
        </a>
      <?php endif; ?>
    <?php else: ?>
      <a href="pages/login.html" class="link" title="Login">
        <i class="fas fa-user"></i>
      </a>
    <?php endif; ?>
  </div>

  <!-- Navigation -->
  <nav class="nav-links">
    <ul>
      <li><a class="links" href="#home"><i class="fas fa-home"></i> Home</a></li>
      <li><a class="links" href="#announcements"><i class="fas fa-bullhorn"></i> Announcements</a></li>
      <li><a class="links" href="#popular"><i class="fas fa-book"></i> Books</a></li>
      <li><a class="links" href="#services"><i class="fas fa-cogs"></i> Services</a></li>
      <li><a class="links" href="#news"><i class="fas fa-newspaper"></i> News</a></li>

      <?php if ($isLoggedIn): ?>
        <li>
          <a class="links" href="backend/logout.php" title="Logout">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>

  <!-- ===== HERO ===== -->
  <section id="home" class="hero-section visible">

    <div class="contant">
      <?php if ($isLoggedIn): ?>
        <h1>Welcome back,<br><?= $userName ?> <i class="fas fa-hands"></i></h1>
      <?php else: ?>
        <h1>Welcome to<br>YIC University Library</h1>
      <?php endif; ?>

      <p class="subtitle">
        Access thousands of academic resources, research papers,<br>
        and digital books from anywhere
      </p>

      <div class="button-group">
        <button class="btn-primary" onclick="location.href='pages/books.php'">Explore Books</button>

        <?php if ($isLoggedIn): ?>
          <button class="btn-secondary" onclick="location.href='<?= $userRole === 'admin' ? 'pages/admin_dashboard.php' : 'pages/dashboard.php' ?>'">
            My Dashboard
          </button>
        <?php else: ?>
          <button class="btn-secondary" onclick="location.href='pages/login.html'">Login</button>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- ===== ANNOUNCEMENTS ===== -->
  <section id="announcements" class="announcements">
    <div class="section-title"><i class="fas fa-bullhorn"></i> Latest Announcements</div>
    <div class="announcements-grid">
      <div class="announcement-card">
        <i class="fas fa-calendar-alt"></i>
        <h3>Library Extended Hours</h3>
        <p>During final exams, the library will be open 24/7 starting from December 15th.</p>
        <span class="announcement-date"><i class="far fa-calendar-alt"></i> December 10, 2025</span>
      </div>
      <div class="announcement-card">
        <i class="fas fa-book-open"></i>
        <h3>New Books Arrival</h3>
        <p>500+ new academic books added to our collection. Visit the new arrivals section!</p>
        <span class="announcement-date"><i class="far fa-calendar-alt"></i> December 5, 2025</span>
      </div>
      <div class="announcement-card">
        <i class="fas fa-laptop-code"></i>
        <h3>Digital Resources Workshop</h3>
        <p>Learn how to access e-books and research databases. Register now!</p>
        <span class="announcement-date"><i class="far fa-calendar-alt"></i> December 15, 2025</span>
      </div>
      <div class="announcement-card">
        <i class="fas fa-gift"></i>
        <h3>Holiday Reading Challenge</h3>
        <p>Win prizes by reading and reviewing books during the winter break.</p>
        <span class="announcement-date"><i class="far fa-calendar-alt"></i> December 20, 2025</span>
      </div>
    </div>
  </section>

  <!-- ===== POPULAR BOOKS ===== -->
  <section id="popular" class="popular-books">
    <div class="section-title"><i class="fas fa-fire"></i> Most Popular Books</div>
    <div class="books-grid">

      <?php if (!empty($popularBooks)): ?>
        <?php foreach ($popularBooks as $book): ?>
          <div class="book-card" onclick="location.href='pages/books.php'">
            <div class="book-icon"><i class="fas fa-book"></i></div>
            <h4><?= htmlspecialchars($book['title'] ?? '') ?></h4>
            <p><?= htmlspecialchars($book['author'] ?? '') ?></p>

            <?php $avail = (int)($book['available'] ?? 0); $qty = (int)($book['quantity'] ?? 0); ?>
            <?php if ($avail > 1): ?>
              <span class="availability">
                <i class="fas fa-check-circle"></i>
                <?= $avail ?> of <?= $qty ?> available
              </span>
            <?php elseif ($avail === 1): ?>
              <span class="availability low">
                <i class="fas fa-exclamation-circle"></i> 1 copy left
              </span>
            <?php else: ?>
              <span class="availability unavailable">
                <i class="fas fa-times-circle"></i> Not Available
              </span>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>

      <?php else: ?>
        <p class="no-books">No books available at the moment.</p>
      <?php endif; ?>

    </div>
  </section>

  <!-- ===== SERVICES ===== -->
  <section id="services" class="services-section">
    <div class="section-title"><i class="fas fa-cogs"></i> Library Services</div>
    <div class="services-grid">
      <div class="service-card" onclick="location.href='pages/books.php'">
        <i class="fas fa-search"></i>
        <h3>Search Books</h3>
        <p>Find any book in our catalog</p>
      </div>
      <div class="service-card" onclick="location.href='pages/Ebooks.php'">
        <i class="fas fa-tablet-alt"></i>
        <h3>E-Books</h3>
        <p>Access digital resources anytime</p>
      </div>

      <?php if ($isLoggedIn): ?>
        <div class="service-card" onclick="location.href='pages/fines.php'">
          <i class="fas fa-dollar-sign"></i>
          <h3>Pay Fines</h3>
          <p>Check and pay overdue fines</p>
        </div>
        <div class="service-card" onclick="location.href='pages/borrowed-books.php'">
          <i class="fas fa-book-reader"></i>
          <h3>My Borrowed Books</h3>
          <p>Track your borrowed books</p>
        </div>
      <?php else: ?>
        <div class="service-card locked" onclick="location.href='pages/login.html'" title="Login required">
          <i class="fas fa-lock"></i>
          <h3>Pay Fines</h3>
          <p>Login to check your fines</p>
        </div>
        <div class="service-card locked" onclick="location.href='pages/login.html'" title="Login required">
          <i class="fas fa-lock"></i>
          <h3>My Borrowed Books</h3>
          <p>Login to track your books</p>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ===== NEWS ===== -->
  <section id="news" class="news-section">
    <div class="section-title"><i class="fas fa-newspaper"></i> Library News</div>
    <div class="news-list">
      <div class="news-item">
        <div class="news-icon"><i class="fas fa-trophy"></i></div>
        <div class="news-content">
          <h4>Library Wins Best Service Award</h4>
          <p>YIC Library recognized for excellence in student services at the annual education awards.</p>
          <span class="news-date"><i class="far fa-calendar-alt"></i> December 8, 2025</span>
        </div>
      </div>
      <div class="news-item">
        <div class="news-icon"><i class="fas fa-wifi"></i></div>
        <div class="news-content">
          <h4>New High-Speed WiFi Installed</h4>
          <p>All library zones now have upgraded 5G WiFi connectivity for better online access.</p>
          <span class="news-date"><i class="far fa-calendar-alt"></i> December 1, 2025</span>
        </div>
      </div>
      <div class="news-item">
        <div class="news-icon"><i class="fas fa-users"></i></div>
        <div class="news-content">
          <h4>Study Group Registration Open</h4>
          <p>Register your study group for the winter semester. Limited slots available.</p>
          <span class="news-date"><i class="far fa-calendar-alt"></i> November 28, 2025</span>
        </div>
      </div>
      <div class="news-item">
        <div class="news-icon"><i class="fas fa-microphone-alt"></i></div>
        <div class="news-content">
          <h4>Author Talk: Dr. Mohammed Al-Ghamdi</h4>
          <p>Join us for a discussion on "Digital Transformation in Education"</p>
          <span class="news-date"><i class="far fa-calendar-alt"></i> November 25, 2025</span>
        </div>
      </div>
    </div>

    <!-- Contact / Feedback Form -->
    <div class="feedback-form-wrap">
      <div class="section-title" style="margin-top:60px;"><i class="fas fa-envelope"></i> Send Us a Message</div>
      <form id="feedbackForm" novalidate>
        <div class="form-group">
          <label for="userName">Full Name</label>
          <input type="text" id="userName" placeholder="Your full name"
                 value="<?= $isLoggedIn ? $userName : '' ?>" autocomplete="name">
          <span class="error-msg" id="nameError"></span>
        </div>
        <div class="form-group">
          <label for="userEmail">Email Address</label>
          <input type="email" id="userEmail" placeholder="your@email.com"
                 value="<?= $isLoggedIn ? htmlspecialchars($_SESSION['email'] ?? '') : '' ?>" autocomplete="email">
          <span class="error-msg" id="emailError"></span>
        </div>
        <div class="form-group">
          <label for="userMessage">Message</label>
          <textarea id="userMessage" rows="4" placeholder="Write your message here..."></textarea>
          <span class="error-msg" id="messageError"></span>
        </div>
        <button type="submit" class="btn-primary">Send Message <i class="fas fa-paper-plane"></i></button>
        <div class="form-success" id="formSuccess">
          <i class="fas fa-check-circle"></i> Thank you! Your message has been sent.
        </div>
      </form>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <p><i class="far fa-copyright"></i> 2025 YIC University Library | Yanbu Industrial College</p>
    <p><i class="far fa-clock"></i> Opening Hours: Sun–Thu 8:00 AM – 10:00 PM</p>
  </footer>

  <!-- Scroll to Top -->
  <button class="scroll-top" id="scrollTop" aria-label="Scroll to top">
    <i class="fas fa-arrow-up"></i>
  </button>

  <script src="assets/js/main.js"></script>
</body>
</html>