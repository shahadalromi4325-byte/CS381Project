-- Create the Database
CREATE DATABASE IF NOT EXISTS yic_library;
USE yic_library;

-- 1. Users Table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Physical Books Table (Matches books.js structure)
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    call_number VARCHAR(50),
    isbn VARCHAR(20),
    quantity INT DEFAULT 1,
    available INT DEFAULT 1
);

-- 3. E-Books Table (Matches Ebooks.js structure)
CREATE TABLE ebooks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    format VARCHAR(10) DEFAULT 'PDF',
    size VARCHAR(20),
    icon VARCHAR(50) DEFAULT 'fa-file-pdf'
);

-- 4. Borrowed Books Table
CREATE TABLE borrowed_books (
    borrow_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    book_id INT,
    borrow_date DATE,
    due_date DATE,
    return_date DATE NULL,
    status ENUM('active', 'returned', 'overdue') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (book_id) REFERENCES books(id)
);

-- 5. Fines Table (Matches fines.html needs)
CREATE TABLE fines (
    fine_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    amount DECIMAL(10, 2),
    reason TEXT,
    status ENUM('unpaid', 'paid') DEFAULT 'unpaid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

--6. insert data (books and ebooks)
-- Inserting into Physical Books
INSERT INTO books (title, author, category, call_number, isbn, quantity, available) VALUES
('Introduction to Software Development', 'Dr. Ahmed', 'science', 'QA76.76.S64', '978-11181234', 5, 5),
('Database Systems: Design & Implementation', 'Prof. Sarah', 'science', 'QA76.9.D3', '978-01360862', 4, 4),
('Artificial Intelligence: A Modern Approach', 'Stuart Russell', 'science', 'Q335.R87', '978-01360425', 3, 2),
('Web Design with HTML, CSS, JavaScript', 'Jon Duckett', 'science', 'TK5105.888', '978-11180081', 8, 8);

-- Inserting into E-Books
INSERT INTO ebooks (title, author, category, format, size, icon) VALUES
('Advanced PHP for Web Apps', 'YIC Faculty', 'Computer science', 'PDF', '5.2 MB', 'fa-file-code'),
('Deep Learning Foundations', 'Andrew Ng', 'Computer Science', 'PDF', '12.4 MB', 'fa-atom'),
('UI/UX Accessibility Guide', 'WCAG Team', 'Design','ePub', '2.1 MB', 'fa-universal-access'),
('Data Structures in JS', 'Open Source','Computer Science', 'PDF', '3.8 MB', 'fa-code-branch');