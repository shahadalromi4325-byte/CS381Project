-- ============================================
-- YIC Library Database - Improved Version
-- ============================================

CREATE DATABASE IF NOT EXISTS yic_library 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

USE yic_library;

-- ============================================
-- 1. Users Table
-- ============================================
CREATE TABLE users (
    user_id    INT AUTO_INCREMENT PRIMARY KEY,
    full_name  VARCHAR(100) NOT NULL,
    email      VARCHAR(100) UNIQUE NOT NULL,
    password   VARCHAR(255) NOT NULL,
    role       ENUM('admin', 'student', 'staff') DEFAULT 'student',  -- ← أضيفي هذا
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- ============================================
-- 2. Physical Books Table
-- ============================================
CREATE TABLE books (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(255) NOT NULL,
    author      VARCHAR(100) NOT NULL,
    category    VARCHAR(50),
    call_number VARCHAR(50),
    isbn        VARCHAR(20) UNIQUE,
    quantity    INT DEFAULT 1,
    available   INT DEFAULT 1,
    INDEX idx_category (category),           -- Speed up category filtering
    INDEX idx_author   (author)              -- Speed up author search
);

-- ============================================
-- 3. E-Books Table
-- ============================================
CREATE TABLE ebooks (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    title    VARCHAR(255) NOT NULL,
    author   VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    format   ENUM('PDF', 'ePub', 'MOBI') DEFAULT 'PDF',  -- Restrict to valid formats
    size     VARCHAR(20),
    icon     VARCHAR(50) DEFAULT 'fa-file-pdf',
    INDEX idx_category (category)
);

-- ============================================
-- 4. Borrowed Books Table
-- ============================================
CREATE TABLE borrowed_books (
    borrow_id   INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    book_id     INT NOT NULL,
    borrow_date DATE NOT NULL DEFAULT (CURRENT_DATE),
    due_date    DATE NOT NULL,
    return_date DATE NULL,
    status      ENUM('active', 'returned', 'overdue') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id)      ON DELETE CASCADE,
    INDEX idx_status  (status),
    INDEX idx_user_id (user_id)
);

-- ============================================
-- 5. Fines Table
-- ============================================
CREATE TABLE fines (
    fine_id    INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    amount     DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    reason     TEXT,
    status     ENUM('unpaid', 'paid') DEFAULT 'unpaid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_status (status)
);

-- ============================================
-- 6. Sample Data
-- ============================================

INSERT INTO books (title, author, category, call_number, isbn, quantity, available) VALUES
('Introduction to Software Development',      'Dr. Ahmed',      'Science', 'QA76.76.S64',  '978-11181234', 5, 5),
('Database Systems: Design & Implementation', 'Prof. Sarah',    'Science', 'QA76.9.D3',    '978-01360862', 4, 4),
('Artificial Intelligence: A Modern Approach','Stuart Russell', 'Science', 'Q335.R87',     '978-01360425', 3, 2),
('Web Design with HTML, CSS, JavaScript',     'Jon Duckett',   'Science', 'TK5105.888',   '978-11180081', 8, 8);

INSERT INTO ebooks (title, author, category, format, size, icon) VALUES
('Advanced PHP for Web Apps',   'YIC Faculty', 'Computer Science', 'PDF',  '5.2 MB',  'fa-file-code'),
('Deep Learning Foundations',   'Andrew Ng',   'Computer Science', 'PDF',  '12.4 MB', 'fa-atom'),
('UI/UX Accessibility Guide',   'WCAG Team',   'Design',           'ePub', '2.1 MB',  'fa-universal-access'),
('Data Structures in JS',       'Open Source', 'Computer Science', 'PDF',  '3.8 MB',  'fa-code-branch');