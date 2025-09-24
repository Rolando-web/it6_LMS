-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2025 at 04:44 PM
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
CREATE DEFINER=`root`@`localhost` FUNCTION `TotalCopiesByCategory` (`cat` VARCHAR(100)) RETURNS INT(11) DETERMINISTIC BEGIN
    DECLARE total INT;
    SELECT SUM(copies) INTO total FROM books WHERE category = cat;
    RETURN IFNULL(total, 0);
END$$

DELIMITER ;

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
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `category`, `isbn`, `publish_date`, `copies`, `image`) VALUES
(51, 'The man', 'Jayr', 'Philosophy & Psychology', '123-1233123-1', '2025-09-05', 21, 'uploads/1758381558_Screenshot 2025-09-19 002440.png'),
(52, 'New books', 'Luayon', 'Fiction', '999-99-9999-9', '2025-07-10', 25, 'uploads/1758447063_Screenshot 2025-09-20 050904.png'),
(53, 'Labaders', 'Hello', 'Business & Economics', '66-666-666-66', '2025-09-05', 25, 'uploads/1758447206_Screenshot 2025-09-19 030530.png'),
(54, 'Yawa book', 'yawa', 'History & Biography', '11-222-333-55', '2025-09-10', 26, 'uploads/1758447232_Screenshot 2025-09-19 174256.png'),
(55, 'Romeo and Juliet', 'Jayr Luayon', 'Fiction', '123-12334523-', '2025-09-04', 25, 'uploads/1758542380_Screenshot 2025-09-18 205858.png'),
(56, 'Ibong-Adarna', 'José de la Cruz', 'Fiction', '777-777-77777', '1980-02-18', 20, 'uploads/1758556319_Screenshot 2025-09-22 034958.png'),
(57, 'Alamat ng pinya', 'Boots S. Agbayani Pastor', 'Fiction', '888-888-8888', '2008-06-18', 50, 'uploads/1758556609_Screenshot 2025-09-22 035644.png'),
(58, 'Julitos Book', 'Julito Great', 'Fiction', '2020-20-2002', '2025-09-12', 25, 'uploads/1758721492_Screenshot 2025-09-22 035644.png'),
(59, 'Bugsy The Surigawnyon', 'Jayr Luayon', 'Fiction', '1111-111-222', '2025-09-18', 25, 'uploads/1758724110_Screenshot 2025-09-24 222817.png');

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
(1, 15, 53, '0000-00-00', '2025-09-23', '2025-09-23', 1, 50.00);

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
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

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
