-- ============================================
-- YIC Library Database - CREATE TABLES ONLY
-- Run this FIRST on a fresh database
-- ============================================

CREATE DATABASE IF NOT EXISTS yic_library 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

USE yic_library;

-- ============================================
-- 1. Users Table
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    user_id    INT AUTO_INCREMENT PRIMARY KEY,
    full_name  VARCHAR(100) NOT NULL,
    email      VARCHAR(100) UNIQUE NOT NULL,
    password   VARCHAR(255) NOT NULL,
    role       ENUM('admin', 'student', 'staff') DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- 2. Physical Books Table
-- ============================================
CREATE TABLE IF NOT EXISTS books (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(255) NOT NULL,
    author      VARCHAR(100) NOT NULL,
    category    VARCHAR(50),
    call_number VARCHAR(50),
    isbn        VARCHAR(20) UNIQUE,
    quantity    INT DEFAULT 1,
    available   INT DEFAULT 1,
    INDEX idx_category (category),
    INDEX idx_author   (author)
);

-- ============================================
-- 3. E-Books Table
-- ============================================
CREATE TABLE IF NOT EXISTS ebooks (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    title    VARCHAR(255) NOT NULL,
    author   VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    format   ENUM('PDF', 'ePub', 'MOBI') DEFAULT 'PDF',
    size     VARCHAR(20),
    icon     VARCHAR(50) DEFAULT 'fa-file-pdf',
    INDEX idx_category (category)
);

-- ============================================
-- 4. Borrowed Books Table
-- ============================================
CREATE TABLE IF NOT EXISTS borrowed_books (
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
CREATE TABLE IF NOT EXISTS fines (
    fine_id    INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    borrow_id  INT NOT NULL,
    amount     DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    reason     TEXT,
    status     ENUM('unpaid', 'paid') DEFAULT 'unpaid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)   REFERENCES users(user_id)            ON DELETE CASCADE,
    FOREIGN KEY (borrow_id) REFERENCES borrowed_books(borrow_id) ON DELETE CASCADE,
    INDEX idx_status (status)
);