-- ============================================
-- 1. Users (password = "password123")
-- ============================================
INSERT INTO users (full_name, email, password, role) VALUES
('Admin User',         'admin@yic.edu.sa',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Shahad Alromi',      'shahad@yic.edu.sa',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('Sara Alawi',         'sara@yic.edu.sa',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('Ahmed Almutairi',    'ahmed@yic.edu.sa',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('Fatimah Alghamdi',   'fatimah@yic.edu.sa',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('Omar Alharbi',       'omar@yic.edu.sa',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('Nora Alshehri',      'nora@yic.edu.sa',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('Khalid Alzahrani',   'khalid@yic.edu.sa',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('Lina Alotaibi',      'lina@yic.edu.sa',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff'),
('Mohammed Alqahtani', 'mohammed@yic.edu.sa', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('Reem Alsaedi',       'reem@yic.edu.sa',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student');

-- ============================================
-- 2. Books
-- ============================================
INSERT INTO books (title, author, category, call_number, isbn, quantity, available) VALUES
('Introduction to Software Development',       'Dr. Ahmed',          'Science',     'QA76.76.S64',  '978-1118123400', 5, 5),
('Database Systems: Design & Implementation',  'Prof. Sarah',         'Science',     'QA76.9.D3',    '978-0136086209', 4, 4),
('Artificial Intelligence: A Modern Approach', 'Stuart Russell',      'Science',     'Q335.R87',     '978-0136042594', 3, 2),
('Web Design with HTML, CSS, JavaScript',      'Jon Duckett',         'Science',     'TK5105.888',   '978-1118008188', 8, 8),
('Clean Code',                                 'Robert C. Martin',    'Programming', 'QA76.73.J38',  '978-0132350884', 3, 3),
('The Pragmatic Programmer',                   'Andrew Hunt',         'Programming', 'QA76.6.H86',   '978-0201616224', 2, 1),
('Introduction to Algorithms',                 'Thomas H. Cormen',    'Science',     'QA76.6.I5858', '978-0262033848', 4, 4),
('Computer Networks',                          'Andrew Tanenbaum',    'Networking',  'TK5105.T36',   '978-0132126953', 3, 2),
('Operating System Concepts',                  'Abraham Silberschatz', 'Science',    'QA76.76.O63',  '978-1119800361', 5, 5),
('Calculus: Early Transcendentals',            'James Stewart',       'Mathematics', 'QA303.2.S74',  '978-1285741550', 6, 6),
('Linear Algebra and Its Applications',        'David C. Lay',        'Mathematics', 'QA184.2.L39',  '978-0321982384', 4, 3),
('Discrete Mathematics',                       'Kenneth Rosen',       'Mathematics', 'QA39.3.R67',   '978-0073383095', 3, 3);

-- ============================================
-- 3. E-Books
-- ============================================
INSERT INTO ebooks (title, author, category, format, size, icon) VALUES
('Advanced PHP for Web Apps',        'YIC Faculty',        'Computer Science', 'PDF',  '5.2 MB',  'fa-file-code'),
('Deep Learning Foundations',        'Andrew Ng',          'Computer Science', 'PDF',  '12.4 MB', 'fa-atom'),
('UI/UX Accessibility Guide',        'WCAG Team',          'Design',           'PDF', '2.1 MB',  'fa-universal-access'),
('Data Structures in JavaScript',    'Open Source',        'Computer Science', 'PDF',  '3.8 MB',  'fa-code-branch'),
('Python for Data Science',          'Jake VanderPlas',    'Data Science',     'PDF',  '8.3 MB',  'fa-file-code'),
('React.js Complete Guide',          'Maximilian S.',      'Web Development',  'PDF',  '6.7 MB',  'fa-file-code'),
('Cybersecurity Fundamentals',       'Charles P. Pfleeger','Security',         'PDF',  '9.1 MB',  'fa-shield-halved'),
('Machine Learning with Python',     'Sebastian Raschka',  'Data Science',     'PDF', '11.2 MB', 'fa-robot'),
('Linux Command Line Basics',        'William Shotts',     'Systems',          'PDF',  '4.5 MB',  'fa-terminal'),
('Cloud Computing: AWS Guide',       'Ben Piper',          'Cloud',            'PDF',  '7.8 MB',  'fa-cloud'),
('Responsive Web Design Patterns',   'Ethan Marcotte',     'Design',           'PDF', '3.2 MB',  'fa-mobile-screen'),
('Network Security Essentials',      'William Stallings',  'Networking',       'PDF',  '10.4 MB', 'fa-network-wired');

-- ============================================
-- 4. Borrowed Books
-- ============================================
INSERT INTO borrowed_books (user_id, book_id, borrow_date, due_date, return_date, status) VALUES
(2,  1,  '2025-11-01', '2025-11-15', '2025-11-14', 'returned'),
(2,  3,  '2025-11-20', '2025-12-04', NULL,          'overdue'),
(3,  2,  '2025-11-10', '2025-11-24', '2025-11-23', 'returned'),
(3,  5,  '2025-12-01', '2025-12-15', NULL,          'active'),
(4,  4,  '2025-11-15', '2025-11-29', NULL,          'overdue'),
(5,  6,  '2025-12-05', '2025-12-19', NULL,          'active'),
(6,  7,  '2025-11-25', '2025-12-09', '2025-12-08', 'returned'),
(7,  8,  '2025-12-10', '2025-12-24', NULL,          'active'),
(8,  9,  '2025-11-05', '2025-11-19', NULL,          'overdue'),
(10, 10, '2025-12-01', '2025-12-15', NULL,          'active'),
(11, 11, '2025-12-08', '2025-12-22', NULL,          'active');

-- ============================================
-- 5. Fines
-- ============================================
INSERT INTO fines (user_id, borrow_id, amount, reason, status) VALUES
(2,  2,  5.00,  'Book overdue by 16 days',   'unpaid'),
(4,  5,  8.50,  'Book overdue by 17 days',   'unpaid'),
(8,  9,  12.00, 'Book overdue by 24 days',   'unpaid'),
(2,  1,  0.00,  'Returned on time',           'paid'),
(3,  3,  0.00,  'Returned on time',           'paid'),
(6,  7,  0.00,  'Returned on time',           'paid'),
(5,  4,  3.00,  'Book returned with damage',  'unpaid'),
(7,  8,  1.50,  'Minor late fee',             'unpaid'),
(10, 10, 0.00,  'No fine',                    'paid'),
(11, 11, 2.00,  'Book cover torn',            'unpaid');
USE yic_library;

-- ──  Add cover_url column to books ────────
ALTER TABLE books ADD COLUMN cover_url VARCHAR(500) DEFAULT NULL;

-- ──  Add file_path column to ebooks ───────
ALTER TABLE ebooks ADD COLUMN file_path VARCHAR(500) DEFAULT NULL;

-- ── 3. Update books with cover images (Open Library API - free) ─
UPDATE books SET cover_url = 'https://covers.openlibrary.org/b/isbn/9780132350884-L.jpg' WHERE isbn = '978-0132350884';
UPDATE books SET cover_url = 'https://covers.openlibrary.org/b/isbn/9780201616224-L.jpg' WHERE isbn = '978-0201616224';
UPDATE books SET cover_url = 'https://covers.openlibrary.org/b/isbn/9780262033848-L.jpg' WHERE isbn = '978-0262033848';
UPDATE books SET cover_url = 'https://covers.openlibrary.org/b/isbn/9780132126953-L.jpg' WHERE isbn = '978-0132126953';
UPDATE books SET cover_url = 'https://books.google.com/books/content?vid=ISBN9781119800361&printsec=frontcover&img=1&zoom=1' WHERE isbn = '978-1119800361';
UPDATE books SET cover_url = 'https://covers.openlibrary.org/b/isbn/9781285741550-L.jpg' WHERE isbn = '978-1285741550';
UPDATE books SET cover_url = 'https://covers.openlibrary.org/b/isbn/9780321982384-L.jpg' WHERE isbn = '978-0321982384';
UPDATE books SET cover_url = 'https://covers.openlibrary.org/b/isbn/9780073383095-L.jpg' WHERE isbn = '978-0073383095';
UPDATE books SET cover_url = 'https://covers.openlibrary.org/b/isbn/9781118008188-L.jpg' WHERE isbn = '978-1118008188';
UPDATE books SET cover_url = 'https://ui-avatars.com/api/?name=Database+Systems&size=300&background=2D54A4&color=ffffff&bold=true&format=png' WHERE isbn = '978-0136086209';
UPDATE books SET cover_url = 'https://covers.openlibrary.org/b/isbn/9780136042594-L.jpg' WHERE isbn = '978-0136042594';
UPDATE books SET cover_url = 'https://ui-avatars.com/api/?name=Introduction+Software&size=300&background=1F3A6B&color=ffffff&bold=true&format=png' WHERE isbn = '978-1118123400';

-- ── 4. Update ebooks with PDF file paths ────
-- Place your PDF files in: assets/pdfs/
UPDATE ebooks SET file_path = 'assets/pdfs/advanced-php.pdf'         WHERE title = 'Advanced PHP for Web Apps';
UPDATE ebooks SET file_path = 'assets/pdfs/deep-learning.pdf'        WHERE title = 'Deep Learning Foundations';
UPDATE ebooks SET file_path = 'assets/pdfs/uiux-guide.pdf'           WHERE title = 'UI/UX Accessibility Guide';
UPDATE ebooks SET file_path = 'assets/pdfs/data-structures-js.pdf'   WHERE title = 'Data Structures in JavaScript';
UPDATE ebooks SET file_path = 'assets/pdfs/python-datascience.pdf'   WHERE title = 'Python for Data Science';
UPDATE ebooks SET file_path = 'assets/pdfs/reactjs-guide.pdf'        WHERE title = 'React.js Complete Guide';
UPDATE ebooks SET file_path = 'assets/pdfs/cybersecurity.pdf'        WHERE title = 'Cybersecurity Fundamentals';
UPDATE ebooks SET file_path = 'assets/pdfs/ml-python.pdf'            WHERE title = 'Machine Learning with Python';
UPDATE ebooks SET file_path = 'assets/pdfs/linux-cli.pdf'            WHERE title = 'Linux Command Line Basics';
UPDATE ebooks SET file_path = 'assets/pdfs/aws-cloud.pdf'            WHERE title = 'Cloud Computing: AWS Guide';
UPDATE ebooks SET file_path = 'assets/pdfs/responsive-design.pdf'    WHERE title = 'Responsive Web Design Patterns';
UPDATE ebooks SET file_path = 'assets/pdfs/network-security.pdf'     WHERE title = 'Network Security Essentials';