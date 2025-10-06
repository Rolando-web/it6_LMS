-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 05, 2025 at 05:54 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `it6_lms`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddBook` (IN `p_title` VARCHAR(255), IN `p_author` VARCHAR(255), IN `p_category` VARCHAR(100), IN `p_isbn` VARCHAR(50), IN `p_publish_date` DATE, IN `p_copies` INT, IN `p_image` VARCHAR(255))   BEGIN
    INSERT INTO books (title, author, category, isbn, publish_date, copies, image)
    VALUES (p_title, p_author, p_category, p_isbn, p_publish_date, p_copies, p_image);
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `CalculateFee` (`daysOverdue` INT) RETURNS DECIMAL(10,2) DETERMINISTIC BEGIN
  DECLARE fee DECIMAL(10,2);
  SET fee = daysOverdue * 50.00;
  RETURN fee;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `timestamp`, `details`, `created_at`) VALUES
(162, 16, 'borrowed book', '2025-09-30 18:20:56', 'Book ID 57 borrowed on 2025-09-30', '2025-09-30 10:20:56'),
(163, 16, 'borrowed book', '2025-09-30 18:22:33', 'Book ID 55 borrowed on 2025-09-30', '2025-09-30 10:22:33'),
(164, 16, 'returned book', '2025-09-30 18:22:49', 'Book ID 55 returned on 2025-09-30', '2025-09-30 10:22:49'),
(165, 16, 'borrowed book', '2025-10-05 15:14:40', 'Book ID 62 borrowed on 2025-10-05', '2025-10-05 07:14:40'),
(166, 26, 'borrowed book', '2025-10-05 15:34:16', 'Book ID 62 borrowed on 2025-10-05', '2025-10-05 07:34:16'),
(167, 16, 'returned book', '2025-10-05 15:36:37', 'Book ID 58 returned on 2025-10-05', '2025-10-05 07:36:37'),
(168, 16, 'borrowed book', '2025-10-05 16:24:16', 'Book ID 63 borrowed on 2025-10-05', '2025-10-05 08:24:16'),
(169, 16, 'returned book', '2025-10-05 23:18:08', 'Book ID 63 returned on 2025-10-05', '2025-10-05 15:18:08'),
(170, 16, 'borrowed book', '2025-10-05 23:19:18', 'Book ID 63 borrowed on 2025-10-05', '2025-10-05 15:19:18'),
(171, 16, 'returned book', '2025-10-05 23:19:34', 'Book ID 63 returned on 2025-10-05', '2025-10-05 15:19:34'),
(172, 16, 'borrowed book', '2025-10-05 23:19:43', 'Book ID 63 borrowed on 2025-10-05', '2025-10-05 15:19:43'),
(173, 16, 'returned book', '2025-10-05 23:21:53', 'Book ID 63 returned on 2025-10-05', '2025-10-05 15:21:53'),
(174, 16, 'borrowed book', '2025-10-05 23:22:52', 'Book ID 63 borrowed on 2025-10-05', '2025-10-05 15:22:52'),
(175, 16, 'returned book', '2025-10-05 23:23:08', 'Book ID 63 returned on 2025-10-05', '2025-10-05 15:23:08'),
(176, 16, 'borrowed book', '2025-10-05 23:23:21', 'Book ID 63 borrowed on 2025-10-05', '2025-10-05 15:23:21'),
(177, 16, 'returned book', '2025-10-05 23:36:09', 'Book ID 63 returned on 2025-10-05', '2025-10-05 15:36:09');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `isbn` varchar(13) NOT NULL,
  `publish_date` date NOT NULL,
  `copies` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `category`, `isbn`, `publish_date`, `copies`, `image`, `created_at`) VALUES
(52, 'New books', 'Luayon', 'Fiction', '999-99-9999-9', '2025-07-10', 24, 'uploads/1758447063_Screenshot 2025-09-20 050904.png', '2025-10-04 23:32:12'),
(53, 'Labaders', 'Hello', 'Fiction', '66-666-666-66', '2025-09-05', 23, 'uploads/1758447206_Screenshot 2025-09-19 030530.png', '2025-10-04 23:32:12'),
(54, 'Yawa book', 'yawa', 'Science', '11-222-333-55', '2025-09-10', 24, 'uploads/1758447232_Screenshot 2025-09-19 174256.png', '2025-10-04 23:32:12'),
(55, 'Romeo and Juliet', 'Jayr Luayon', 'Biology', '123-12334523-', '2025-09-04', 24, 'uploads/1758542380_Screenshot 2025-09-18 205858.png', '2025-10-04 23:32:12'),
(56, 'Ibong-Adarna', 'José de la Cruz', 'Arts', '777-777-77777', '1980-02-18', 18, 'uploads/1758556319_Screenshot 2025-09-22 034958.png', '2025-10-04 23:32:12'),
(57, 'Alamat ng laloo123', 'Boots S. Agbayani Pastor', 'Fiction', '888-888-8888', '2008-06-18', 47, 'uploads/1758556609_Screenshot 2025-09-22 035644.png', '2025-10-04 23:32:12'),
(58, 'Julitos Book', 'Julito Great', 'Business', '2020-20-2002', '2025-09-12', 18, 'uploads/1758721492_Screenshot 2025-09-22 035644.png', '2025-10-04 23:32:12'),
(59, 'Bugsy The Surigawnyon', 'Jayr Luayon', 'Technology', '1111-111-222', '2025-09-18', 9, 'uploads/1758724110_Screenshot 2025-09-24 222817.png', '2025-10-04 23:32:12'),
(61, 'The Philippine History', 'Jhssr Antukin', 'History', '55-5555-5555-', '2015-03-08', 25, 'uploads/1759237989_Screenshot 2025-09-28 230246.png', '2025-10-04 23:32:12'),
(62, 'Exodus', 'Leon Uris', 'History', '44-651-45564-', '1958-02-15', 18, 'uploads/1759489855_Screenshot 2025-10-03 190953.png', '2025-10-04 23:32:12'),
(63, 'Stell Ball Run', 'Hirohiko Araki', 'Fiction', '550-22-36-54-', '2004-01-01', 1, 'uploads/1759652437_Screenshot 2025-10-05 161837.png', '2025-10-05 16:20:37');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `borrow_date` date NOT NULL DEFAULT curdate(),
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `overdue_days` int(11) DEFAULT 0,
  `fee` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `user_id`, `book_id`, `borrow_date`, `due_date`, `return_date`, `overdue_days`, `fee`) VALUES
(89, 16, 59, '2025-09-27', '2025-09-30', '2025-09-28', 0, 0.00),
(90, 16, 59, '2025-09-27', '2025-09-30', '2025-09-28', 0, 0.00),
(91, 16, 58, '2025-09-27', '2025-09-30', '2025-09-28', 0, 0.00),
(92, 16, 57, '2025-09-27', '2025-09-30', '2025-09-28', 0, 0.00),
(93, 16, 56, '2025-09-27', '2025-09-30', '2025-09-28', 0, 0.00),
(94, 26, 54, '2025-09-27', '2025-09-30', '2025-09-28', 0, 0.00),
(95, 26, 53, '2025-09-27', '2025-09-30', '2025-09-28', 0, 0.00),
(96, 26, 52, '2025-09-27', '2025-09-30', '2025-09-28', 0, 0.00),
(97, 16, 57, '2025-09-27', '2025-09-30', '2025-09-28', 0, 0.00),
(98, 26, 54, '2025-09-28', '2025-10-01', '2025-09-28', 0, 0.00),
(99, 26, 59, '2025-09-28', '2025-10-12', '2025-09-28', 0, 0.00),
(100, 26, 58, '2025-09-28', '2025-10-01', '2025-09-28', 0, 0.00),
(101, 16, 58, '2025-09-28', '2025-10-01', '2025-10-05', 4, 200.00),
(102, 16, 57, '2025-09-28', '2025-10-01', NULL, 0, 0.00),
(104, 26, 53, '2025-09-29', '2025-10-02', '2025-09-29', 0, 0.00),
(106, 16, 57, '2025-09-30', '2025-10-03', NULL, 0, 0.00),
(107, 16, 55, '2025-09-30', '2025-10-03', '2025-09-30', 0, 0.00),
(108, 16, 62, '2025-10-05', '2025-10-08', NULL, 0, 0.00),
(109, 26, 62, '2025-10-05', '2025-10-08', NULL, 0, 0.00),
(110, 16, 63, '2025-10-05', '2025-10-08', '2025-10-05', 0, 0.00),
(111, 16, 63, '2025-10-05', '2025-10-08', '2025-10-05', 0, 0.00),
(112, 16, 63, '2025-10-05', '2025-10-08', '2025-10-05', 0, 0.00),
(113, 16, 63, '2025-10-05', '2025-10-08', '2025-10-05', 0, 0.00),
(114, 16, 63, '2025-10-05', '2025-10-08', '2025-10-05', 0, 0.00);

--
-- Triggers `transactions`
--
DELIMITER $$
CREATE TRIGGER `log_borrow_activity` AFTER INSERT ON `transactions` FOR EACH ROW BEGIN INSERT INTO activity_logs (user_id, action, details) VALUES ( NEW.user_id, 'borrowed book', CONCAT('Book ID ', NEW.book_id, ' borrowed on ', NEW.borrow_date) ); END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_return_activity` AFTER UPDATE ON `transactions` FOR EACH ROW BEGIN
  IF NEW.return_date IS NOT NULL AND OLD.return_date IS NULL THEN
    INSERT INTO activity_logs (user_id, action, details)
    VALUES (
      NEW.user_id,
      'returned book',
      CONCAT('Book ID ', NEW.book_id, ' returned on ', NEW.return_date)
    );
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `plain_password` varchar(255) DEFAULT NULL,
  `roles` enum('Users','Admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `contact`, `email`, `password`, `plain_password`, `roles`) VALUES
(15, 'Rolando', 'Luayon', '09702446885', 'rolandoluayon4@gmail.com', '$2y$10$3wNoVm69XaiBIC/77c0OqONSGajaja3b0k7l2uL1S6jIn4iwm3AQ2', 'Luayon123', 'Admin'),
(16, 'tiffany', 'ocon', '90521458765', 'Tiffany@gmail.com', '$2y$10$jmdN/v98.j4rQ5mKBzg1AOZ7ukdTggaYRCQMEhP7Jf0CBSjUEXN9G', 'Luayon123', 'Users'),
(24, 'Micheal', 'Velez', '0912345678', 'MichealVelez@gmail.com', '$2y$10$6fV8EAR2jSDElUeBCSWBCe1ve3bYn4ZFW5/3dxkKWa0OTB9TxcDMy', 'Luayon123', 'Admin'),
(25, 'Jotaro', 'Joestar', '0970112233', 'jotaro@gmail.com', '$2y$10$IEvfjzgYVaAu2B31Olmy0urOJi7iu7gjcq7usFgI/i17iSSe7MQAq', 'Luayon123', 'Users'),
(26, 'Jason', 'Mercado', '09702050603', 'JasonMercado@gmail.com', '$2y$10$84e1ug.uX9Yfw8PXDMWds./5JzqNwFLLmADCddVylosiEKAxNQG/a', 'Luayon123', 'Users');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `fk_transaction_user` (`user_id`),
  ADD KEY `fk_transaction_book` (`book_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `contact` (`contact`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_transaction_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaction_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
