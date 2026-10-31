-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS `library_system_db`;

-- Use the newly created database
USE `library_system_db`;

-- --------------------------------------------------------

--
-- Table structure for `users`
-- This table stores information for all user types: Admin, Teacher, and Student[cite: 5].
-- It handles authentication and profile management features[cite: 10, 11].
--

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('student', 'teacher', 'admin') NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1, -- For the admin to manage user accounts [cite: 24]
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------------------

--
-- Table structure for `books`
-- This table manages the library's book catalog, which admins can modify[cite: 22].
--

CREATE TABLE `books` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `author` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `is_available` TINYINT(1) NOT NULL DEFAULT 1 -- Used for browsing and issuing books [cite: 14, 15]
);

-- --------------------------------------------------------

--
-- Table structure for `borrowing_records`
-- This table tracks all borrowing activities, including history and pending requests[cite: 16, 23].
--

CREATE TABLE `borrowing_records` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `book_id` INT NOT NULL,
  `borrow_date` DATE NOT NULL,
  `return_date` DATE,
  `status` ENUM('pending', 'approved', 'returned', 'declined') NOT NULL, -- For admin approval workflow [cite: 23]
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`book_id`) REFERENCES `books`(`id`) ON DELETE CASCADE
);

-- --------------------------------------------------------

--
-- Table structure for `recommendations`
-- Stores book recommendations made by teachers[cite: 18].
--

CREATE TABLE `recommendations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `teacher_id` INT NOT NULL,
  `book_title` VARCHAR(255) NOT NULL,
  `reason` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`teacher_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

-- --------------------------------------------------------

--
-- Table structure for `reviews`
-- Stores book reviews posted by teachers[cite: 20].
--

CREATE TABLE `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `teacher_id` INT NOT NULL,
  `book_id` INT NOT NULL,
  `review_text` TEXT NOT NULL,
  `rating` INT NOT NULL, -- Rating from 1 to 5
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`teacher_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`book_id`) REFERENCES `books`(`id`) ON DELETE CASCADE
);

-- --------------------------------------------------------

--
-- Default Admin User
-- Inserts a default administrator account for initial login.
-- Email: admin@library.com
-- Password: admin123
--

INSERT INTO `users` (`full_name`, `email`, `password`, `role`, `is_active`) VALUES
('Admin User', 'admin@library.com', '$2y$10$p.3O2e06Vo23S23yEjsmxuaJ5sOzK7NFIGD4om82cTnoW.L252p2C', 'admin', 1);

-- Note on default password: The hashed value above is for 'admin123'. The login.php file
-- was written to accept both this hash and the plain text for the default admin only, for ease of first use.
-- It is highly recommended to change this password after logging in for the first time.
-- All other user passwords are securely hashed using password_hash().