<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/login.html");
    exit;
}
include '../database/db_connection.php';

$books = $pdo->query("SELECT * FROM books ORDER BY id ASC")->fetchAll();
$users = $pdo->query("SELECT user_id, full_name, email, role, created_at FROM users ORDER BY user_id ASC")->fetchAll();

$totalBooks    = $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();
$totalUsers    = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$activeBorrows = $pdo->query("SELECT COUNT(*) FROM borrowed_books WHERE status='active'")->fetchColumn();
$unpaidFines   = $pdo->query("SELECT COALESCE(SUM(amount),0) FROM fines WHERE status='unpaid'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard – YIC Library</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">
  <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
</head>
<body>
<video class="bg-video" autoplay muted loop playsinline>
  <source src="../assets/videos/background.mp4" type="video/mp4">
</video>
<aside class="sidebar">
  <div class="sidebar-logo">
    <img src="../assets/images/logo.png" alt="Logo">
    <div><span>YIC Library</span><small>Admin Panel</small></div>
  </div>
  <nav style="margin-top:16px;">
    <a class="nav-item active" href="#" onclick="return showSection('overview',this)"><i class="fas fa-chart-pie"></i> Overview</a>
    <a class="nav-item" href="#" onclick="return showSection('books',this)"><i class="fas fa-book"></i> Manage Books</a>
    <a class="nav-item" href="#" onclick="return showSection('users',this)"><i class="fas fa-users"></i> Users</a>
    <a class="nav-item" href="#" onclick="return showSection('borrows',this)"><i class="fas fa-bookmark"></i> Borrowed Books</a>
    <hr style="border-color:var(--border);margin:12px 24px;">
    <a class="nav-item" href="../index.php"><i class="fas fa-home"></i> Back to Site</a>
    <a class="nav-item" href="../backend/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </nav>
  <div class="sidebar-footer">
    <div class="admin-badge">
      <div class="admin-avatar"><?= strtoupper(substr($_SESSION['full_name'],0,1)) ?></div>
      <div class="admin-info">
        <span><?= htmlspecialchars($_SESSION['full_name']) ?></span>
        <small><i class="fas fa-shield-halved"></i> Administrator</small>
      </div>
    </div>
  </div>
</aside>

<main class="main">

  <!-- OVERVIEW -->
  <div id="sec-overview" class="section active">
    <div class="page-header">
      <div><h1><i class="fas fa-chart-pie"></i> Dashboard Overview</h1><p>Welcome back, <?= htmlspecialchars($_SESSION['full_name']) ?></p></div>
    </div>
    <div class="stats-grid">
      <div class="stat-card"><div class="stat-icon blue"><i class="fas fa-book"></i></div><div class="stat-info"><h3><?= $totalBooks ?></h3><p>Total Books</p></div></div>
      <div class="stat-card"><div class="stat-icon purple"><i class="fas fa-users"></i></div><div class="stat-info"><h3><?= $totalUsers ?></h3><p>Registered Users</p></div></div>
      <div class="stat-card"><div class="stat-icon green"><i class="fas fa-bookmark"></i></div><div class="stat-info"><h3><?= $activeBorrows ?></h3><p>Active Borrows</p></div></div>
      <div class="stat-card"><div class="stat-icon red"><i class="fas fa-file-invoice-dollar"></i></div><div class="stat-info"><h3>$<?= number_format($unpaidFines,2) ?></h3><p>Unpaid Fines</p></div></div>
    </div>
    <div class="panel">
      <div class="panel-header"><h2><i class="fas fa-clock"></i> Recent Activity</h2></div>
      <div class="table-wrap">
        <table>
          <thead><tr><th>User</th><th>Book</th><th>Borrow Date</th><th>Due Date</th><th>Status</th></tr></thead>
          <tbody>
            <?php
            $recent = $pdo->query("SELECT u.full_name,b.title,bb.borrow_date,bb.due_date,bb.status FROM borrowed_books bb JOIN users u ON bb.user_id=u.user_id JOIN books b ON bb.book_id=b.id ORDER BY bb.borrow_id DESC LIMIT 8")->fetchAll();
            foreach($recent as $r):
              $cls=$r['status']==='active'?'badge-avail':($r['status']==='overdue'?'badge-none':'badge-student');
            ?>
            <tr>
              <td><?=htmlspecialchars($r['full_name'])?></td>
              <td><?=htmlspecialchars($r['title'])?></td>
              <td><?=$r['borrow_date']?></td>
              <td><?=$r['due_date']?></td>
              <td><span class="badge <?=$cls?>"><?=$r['status']?></span></td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- BOOKS CRUD -->
  <div id="sec-books" class="section">
    <div class="page-header">
      <div><h1><i class="fas fa-book"></i> Manage Books</h1><p>Add, edit, or delete books</p></div>
      <button class="btn btn-primary" onclick="openAddModal()"><i class="fas fa-plus"></i> Add Book</button>
    </div>
    <div class="panel">
      <div class="search-bar"><input type="text" id="bookSearch" placeholder="Search by title or author..." oninput="filterTable('bookSearch','booksTable')"></div>
      <div class="table-wrap">
        <table id="booksTable">
          <thead><tr><th>#</th><th>Title</th><th>Author</th><th>Category</th><th>Qty</th><th>Available</th><th>Actions</th></tr></thead>
          <tbody>
            <?php foreach($books as $book):
              $avail=(int)$book['available'];$qty=(int)$book['quantity'];
              $cls=$avail>1?'badge-avail':($avail===1?'badge-low':'badge-none');
            ?>
            <tr>
              <td><?=$book['id']?></td>
              <td><?=htmlspecialchars($book['title'])?></td>
              <td><?=htmlspecialchars($book['author'])?></td>
              <td><?=htmlspecialchars($book['category']??'—')?></td>
              <td><?=$qty?></td>
              <td><span class="badge <?=$cls?>"><?=$avail?>/<?=$qty?></span></td>
              <td style="display:flex;gap:8px;">
                <button class="btn btn-edit btn-sm" onclick='openEditModal(<?=json_encode($book)?>'><i class="fas fa-pen"></i> Edit</button>
                <button class="btn btn-danger btn-sm" onclick="deleteBook(<?=$book['id']?>,this)"><i class="fas fa-trash"></i> Delete</button>
              </td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- USERS -->
  <div id="sec-users" class="section">
    <div class="page-header"><div><h1><i class="fas fa-users"></i> Users</h1><p>All registered users</p></div></div>
    <div class="panel">
      <div class="search-bar"><input type="text" id="userSearch" placeholder="Search by name or email..." oninput="filterTable('userSearch','usersTable')"></div>
      <div class="table-wrap">
        <table id="usersTable">
          <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Joined</th></tr></thead>
          <tbody>
            <?php foreach($users as $u):?>
            <tr>
              <td><?=$u['user_id']?></td>
              <td><?=htmlspecialchars($u['full_name'])?></td>
              <td><?=htmlspecialchars($u['email'])?></td>
              <td><span class="badge badge-<?=$u['role']?>"><?=$u['role']?></span></td>
              <td><?=date('M d, Y',strtotime($u['created_at']))?></td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- BORROWS -->
  <div id="sec-borrows" class="section">
    <div class="page-header"><div><h1><i class="fas fa-bookmark"></i> Borrowed Books</h1><p>All borrowings</p></div></div>
    <div class="panel">
      <div class="table-wrap">
        <table>
          <thead><tr><th>#</th><th>User</th><th>Book</th><th>Borrow Date</th><th>Due Date</th><th>Status</th></tr></thead>
          <tbody>
            <?php
            $allBorrows=$pdo->query("SELECT bb.borrow_id,u.full_name,b.title,bb.borrow_date,bb.due_date,bb.status FROM borrowed_books bb JOIN users u ON bb.user_id=u.user_id JOIN books b ON bb.book_id=b.id ORDER BY bb.borrow_id DESC")->fetchAll();
            foreach($allBorrows as $br):
              $cls=$br['status']==='active'?'badge-avail':($br['status']==='overdue'?'badge-none':'badge-student');
            ?>
            <tr>
              <td><?=$br['borrow_id']?></td>
              <td><?=htmlspecialchars($br['full_name'])?></td>
              <td><?=htmlspecialchars($br['title'])?></td>
              <td><?=$br['borrow_date']?></td>
              <td><?=$br['due_date']?></td>
              <td><span class="badge <?=$cls?>"><?=$br['status']?></span></td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</main>

<!-- Modal -->
<div class="modal-overlay" id="bookModal">
  <div class="modal">
    <h2 id="modalTitle"><i class="fas fa-book"></i> Add Book</h2>
    <form id="bookForm">
      <input type="hidden" id="bookId" name="id">
      <div class="form-group"><label>Title *</label><input type="text" name="title" id="fTitle" required></div>
      <div class="form-group"><label>Author *</label><input type="text" name="author" id="fAuthor" required></div>
      <div class="form-row">
        <div class="form-group"><label>Category</label><input type="text" name="category" id="fCategory"></div>
        <div class="form-group"><label>Call Number</label><input type="text" name="call_number" id="fCallNumber"></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>ISBN</label><input type="text" name="isbn" id="fIsbn"></div>
        <div class="form-group"><label>Quantity</label><input type="number" name="quantity" id="fQuantity" min="1" value="1"></div>
      </div>
      <div class="form-group" id="availableGroup" style="display:none;">
        <label>Available</label><input type="number" name="available" id="fAvailable" min="0">
      </div>
      <div class="modal-actions">
        <button type="button" class="btn btn-cancel" onclick="closeModal()">Cancel</button>
        <button type="submit" class="btn btn-primary" id="modalSubmitBtn"><i class="fas fa-save"></i> Save Book</button>
      </div>
    </form>
  </div>
</div>

<div class="toast" id="toast"></div>

<script src="../assets/js/admin_dashboard.js"></script>
</body>
</html>