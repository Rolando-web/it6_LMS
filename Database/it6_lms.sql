-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2025 at 11:45 AM
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
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `timestamp`, `details`) VALUES
(1, 16, 'borrowed book', '2025-09-26 15:59:59', 'Book ID 59 borrowed on 2025-09-26'),
(2, 16, 'borrowed book', '2025-09-26 15:59:59', 'Book ID 59 borrowed on 2025-09-26'),
(3, 25, 'borrowed book', '2025-09-26 16:07:10', 'Book ID 58 borrowed on 2025-09-26'),
(4, 25, 'borrowed book', '2025-09-26 16:07:10', 'Book ID 58 borrowed on 2025-09-26'),
(5, 26, 'borrowed book', '2025-09-26 16:07:41', 'Book ID 59 borrowed on 2025-09-26'),
(6, 26, 'borrowed book', '2025-09-26 16:07:41', 'Book ID 59 borrowed on 2025-09-26'),
(7, 26, 'borrowed book', '2025-09-26 16:08:03', 'Book ID 59 borrowed on 2025-09-26'),
(8, 26, 'borrowed book', '2025-09-26 16:08:03', 'Book ID 59 borrowed on 2025-09-26'),
(9, 26, 'borrowed book', '2025-09-26 16:08:47', 'Book ID 58 borrowed on 2025-09-26'),
(10, 26, 'borrowed book', '2025-09-26 16:08:47', 'Book ID 58 borrowed on 2025-09-26'),
(11, 26, 'borrowed book', '2025-09-26 16:09:11', 'Book ID 59 borrowed on 2025-09-26'),
(12, 26, 'borrowed book', '2025-09-26 16:09:11', 'Book ID 59 borrowed on 2025-09-26'),
(13, 26, 'borrowed book', '2025-09-26 16:11:31', 'Book ID 59 borrowed on 2025-09-26'),
(14, 26, 'borrowed book', '2025-09-26 16:11:31', 'Book ID 59 borrowed on 2025-09-26'),
(15, 26, 'borrowed book', '2025-09-26 16:13:29', 'Book ID 59 borrowed on 2025-09-26'),
(16, 26, 'borrowed book', '2025-09-26 16:14:08', 'Book ID 59 borrowed on 2025-09-26'),
(17, 26, 'borrowed book', '2025-09-26 16:18:08', 'Book ID 56 borrowed on 2025-09-26'),
(18, 26, 'borrowed book', '2025-09-26 16:18:38', 'Book ID 59 borrowed on 2025-09-26'),
(19, 26, 'borrowed book', '2025-09-26 16:18:51', 'Book ID 58 borrowed on 2025-09-26'),
(20, 26, 'borrowed book', '2025-09-26 16:28:57', 'Book ID 57 borrowed on 2025-09-26'),
(21, 26, 'borrowed book', '2025-09-26 19:17:40', 'Book ID 59 borrowed on 2025-09-26'),
(22, 16, 'borrowed book', '2025-09-26 23:02:33', 'Book ID 59 borrowed on 2025-09-26'),
(23, 16, 'borrowed book', '2025-09-27 18:34:36', 'Book ID 58 borrowed on 2025-09-27'),
(24, 26, 'borrowed book', '2025-09-27 19:12:05', 'Book ID 54 borrowed on 2025-09-27'),
(25, 26, 'borrowed book', '2025-09-27 19:12:05', 'Book ID 54 borrowed on 2025-09-27'),
(26, 26, 'borrowed book', '2025-09-27 19:12:39', 'Book ID 55 borrowed on 2025-09-27'),
(27, 26, 'borrowed book', '2025-09-27 19:13:00', 'Book ID 53 borrowed on 2025-09-27'),
(28, 26, 'borrowed book', '2025-09-27 19:13:36', 'Book ID 54 borrowed on 2025-09-27'),
(29, 26, 'borrowed book', '2025-09-27 19:14:00', 'Book ID 58 borrowed on 2025-09-27'),
(30, 26, 'borrowed book', '2025-09-27 22:40:45', 'Book ID 59 borrowed on 2025-09-27'),
(31, 26, 'borrowed book', '2025-09-27 22:40:49', 'Book ID 59 borrowed on 2025-09-27'),
(32, 26, 'borrowed book', '2025-09-27 22:43:04', 'Book ID 52 borrowed on 2025-09-27'),
(33, 26, 'borrowed book', '2025-09-27 22:48:06', 'Book ID 56 borrowed on 2025-09-27'),
(34, 26, 'borrowed book', '2025-09-27 22:49:19', 'Book ID 55 borrowed on 2025-09-27'),
(35, 26, 'borrowed book', '2025-09-27 22:53:37', 'Book ID 53 borrowed on 2025-09-27'),
(36, 26, 'borrowed book', '2025-09-27 22:56:01', 'Book ID 58 borrowed on 2025-09-27'),
(37, 26, 'borrowed book', '2025-09-27 22:56:52', 'Book ID 57 borrowed on 2025-09-27'),
(38, 26, 'borrowed book', '2025-09-27 22:57:08', 'Book ID 57 borrowed on 2025-09-27'),
(39, 26, 'returned book', '2025-09-27 23:06:00', 'Book ID 57 returned on 2025-09-27'),
(40, 26, 'returned book', '2025-09-27 23:11:50', 'Book ID 54 returned on 2025-09-27'),
(41, 26, 'returned book', '2025-09-27 23:11:54', 'Book ID 58 returned on 2025-09-27'),
(42, 26, 'returned book', '2025-09-27 23:13:07', 'Book ID 59 returned on 2025-09-27'),
(43, 26, 'returned book', '2025-09-27 23:22:41', 'Book ID 55 returned on 2025-09-27'),
(44, 16, 'borrowed book', '2025-09-27 23:23:27', 'Book ID 59 borrowed on 2025-09-27'),
(45, 16, 'returned book', '2025-09-27 23:23:37', 'Book ID 59 returned on 2025-09-27'),
(46, 16, 'borrowed book', '2025-09-27 23:24:10', 'Book ID 59 borrowed on 2025-09-27'),
(47, 16, 'returned book', '2025-09-27 23:24:19', 'Book ID 59 returned on 2025-09-27'),
(48, 16, 'borrowed book', '2025-09-27 23:24:50', 'Book ID 59 borrowed on 2025-09-27'),
(49, 16, 'returned book', '2025-09-27 23:24:53', 'Book ID 59 returned on 2025-09-27'),
(50, 16, 'borrowed book', '2025-09-27 23:25:29', 'Book ID 59 borrowed on 2025-09-27'),
(51, 16, 'returned book', '2025-09-27 23:25:33', 'Book ID 59 returned on 2025-09-27'),
(52, 16, 'borrowed book', '2025-09-27 23:26:46', 'Book ID 57 borrowed on 2025-09-27'),
(53, 16, 'returned book', '2025-09-27 23:26:50', 'Book ID 57 returned on 2025-09-27'),
(54, 16, 'borrowed book', '2025-09-27 23:28:13', 'Book ID 59 borrowed on 2025-09-27'),
(55, 16, 'returned book', '2025-09-27 23:28:21', 'Book ID 59 returned on 2025-09-27'),
(56, 16, 'borrowed book', '2025-09-27 23:29:28', 'Book ID 59 borrowed on 2025-09-27'),
(57, 16, 'returned book', '2025-09-27 23:29:34', 'Book ID 59 returned on 2025-09-27'),
(58, 16, 'borrowed book', '2025-09-27 23:30:49', 'Book ID 58 borrowed on 2025-09-27'),
(59, 16, 'returned book', '2025-09-27 23:30:52', 'Book ID 58 returned on 2025-09-27'),
(60, 16, 'borrowed book', '2025-09-27 23:31:21', 'Book ID 54 borrowed on 2025-09-27'),
(61, 16, 'returned book', '2025-09-27 23:31:31', 'Book ID 54 returned on 2025-09-27'),
(62, 16, 'borrowed book', '2025-09-27 23:32:56', 'Book ID 56 borrowed on 2025-09-27'),
(63, 16, 'borrowed book', '2025-09-27 23:34:26', 'Book ID 59 borrowed on 2025-09-27'),
(64, 16, 'borrowed book', '2025-09-27 23:34:34', 'Book ID 59 borrowed on 2025-09-27'),
(65, 16, 'borrowed book', '2025-09-27 23:34:38', 'Book ID 59 borrowed on 2025-09-27'),
(66, 16, 'borrowed book', '2025-09-27 23:34:43', 'Book ID 59 borrowed on 2025-09-27'),
(67, 16, 'borrowed book', '2025-09-27 23:34:45', 'Book ID 59 borrowed on 2025-09-27'),
(68, 16, 'borrowed book', '2025-09-27 23:34:47', 'Book ID 59 borrowed on 2025-09-27'),
(69, 16, 'borrowed book', '2025-09-27 23:34:49', 'Book ID 59 borrowed on 2025-09-27'),
(70, 16, 'borrowed book', '2025-09-27 23:34:50', 'Book ID 59 borrowed on 2025-09-27'),
(71, 16, 'borrowed book', '2025-09-27 23:34:54', 'Book ID 59 borrowed on 2025-09-27'),
(72, 16, 'returned book', '2025-09-27 23:35:07', 'Book ID 59 returned on 2025-09-27'),
(73, 16, 'returned book', '2025-09-27 23:35:13', 'Book ID 59 returned on 2025-09-27'),
(74, 16, 'returned book', '2025-09-27 23:36:02', 'Book ID 59 returned on 2025-09-27'),
(75, 16, 'returned book', '2025-09-27 23:36:04', 'Book ID 59 returned on 2025-09-27'),
(76, 16, 'returned book', '2025-09-27 23:36:06', 'Book ID 59 returned on 2025-09-27'),
(77, 16, 'returned book', '2025-09-27 23:36:08', 'Book ID 59 returned on 2025-09-27'),
(78, 16, 'returned book', '2025-09-27 23:36:09', 'Book ID 59 returned on 2025-09-27'),
(79, 16, 'returned book', '2025-09-27 23:36:10', 'Book ID 59 returned on 2025-09-27'),
(80, 16, 'returned book', '2025-09-27 23:36:11', 'Book ID 59 returned on 2025-09-27'),
(81, 16, 'returned book', '2025-09-27 23:36:12', 'Book ID 56 returned on 2025-09-27'),
(82, 16, 'borrowed book', '2025-09-27 23:37:33', 'Book ID 59 borrowed on 2025-09-27'),
(83, 16, 'returned book', '2025-09-27 23:37:35', 'Book ID 59 returned on 2025-09-27'),
(84, 16, 'borrowed book', '2025-09-27 23:39:15', 'Book ID 59 borrowed on 2025-09-27'),
(85, 16, 'returned book', '2025-09-27 23:39:18', 'Book ID 59 returned on 2025-09-27'),
(86, 16, 'borrowed book', '2025-09-27 23:41:03', 'Book ID 58 borrowed on 2025-09-27'),
(87, 16, 'returned book', '2025-09-27 23:41:06', 'Book ID 58 returned on 2025-09-27'),
(88, 16, 'borrowed book', '2025-09-27 23:41:16', 'Book ID 58 borrowed on 2025-09-27'),
(89, 16, 'returned book', '2025-09-27 23:41:40', 'Book ID 58 returned on 2025-09-27'),
(90, 16, 'borrowed book', '2025-09-27 23:42:43', 'Book ID 59 borrowed on 2025-09-27'),
(91, 16, 'returned book', '2025-09-27 23:42:46', 'Book ID 59 returned on 2025-09-27'),
(92, 16, 'borrowed book', '2025-09-27 23:45:06', 'Book ID 59 borrowed on 2025-09-27'),
(93, 16, 'returned book', '2025-09-27 23:45:09', 'Book ID 59 returned on 2025-09-27'),
(94, 16, 'borrowed book', '2025-09-27 23:45:15', 'Book ID 59 borrowed on 2025-09-27'),
(95, 16, 'returned book', '2025-09-27 23:45:19', 'Book ID 59 returned on 2025-09-27'),
(96, 16, 'borrowed book', '2025-09-27 23:47:20', 'Book ID 58 borrowed on 2025-09-27'),
(97, 16, 'returned book', '2025-09-27 23:47:23', 'Book ID 58 returned on 2025-09-27'),
(98, 16, 'borrowed book', '2025-09-27 23:48:02', 'Book ID 56 borrowed on 2025-09-27'),
(99, 16, 'borrowed book', '2025-09-27 23:48:05', 'Book ID 58 borrowed on 2025-09-27'),
(100, 16, 'borrowed book', '2025-09-27 23:48:06', 'Book ID 59 borrowed on 2025-09-27'),
(101, 16, 'borrowed book', '2025-09-27 23:48:09', 'Book ID 57 borrowed on 2025-09-27'),
(102, 16, 'borrowed book', '2025-09-27 23:48:11', 'Book ID 54 borrowed on 2025-09-27'),
(103, 16, 'returned book', '2025-09-27 23:48:14', 'Book ID 58 returned on 2025-09-27'),
(104, 16, 'returned book', '2025-09-27 23:58:14', 'Book ID 59 returned on 2025-09-27'),
(105, 16, 'returned book', '2025-09-27 23:58:51', 'Book ID 57 returned on 2025-09-27'),
(106, 16, 'returned book', '2025-09-28 00:01:08', 'Book ID 56 returned on 2025-09-28'),
(107, 16, 'returned book', '2025-09-28 00:01:11', 'Book ID 54 returned on 2025-09-28'),
(108, 16, 'borrowed book', '2025-09-28 00:04:01', 'Book ID 59 borrowed on 2025-09-27'),
(109, 16, 'borrowed book', '2025-09-28 00:04:20', 'Book ID 58 borrowed on 2025-09-27'),
(110, 16, 'borrowed book', '2025-09-28 00:04:25', 'Book ID 58 borrowed on 2025-09-27'),
(111, 16, 'returned book', '2025-09-28 00:06:46', 'Book ID 59 returned on 2025-09-28'),
(112, 16, 'returned book', '2025-09-28 00:07:28', 'Book ID 58 returned on 2025-09-28'),
(113, 16, 'returned book', '2025-09-28 00:09:07', 'Book ID 58 returned on 2025-09-28'),
(114, 16, 'borrowed book', '2025-09-28 00:09:13', 'Book ID 58 borrowed on 2025-09-27'),
(115, 16, 'returned book', '2025-09-28 00:09:21', 'Book ID 58 returned on 2025-09-28'),
(116, 16, 'borrowed book', '2025-09-28 00:12:50', 'Book ID 58 borrowed on 2025-09-27'),
(117, 16, 'borrowed book', '2025-09-28 00:13:44', 'Book ID 58 borrowed on 2025-09-27'),
(118, 16, 'returned book', '2025-09-28 00:17:13', 'Book ID 58 returned on 2025-09-28'),
(119, 16, 'returned book', '2025-09-28 00:17:18', 'Book ID 58 returned on 2025-09-28'),
(120, 16, 'borrowed book', '2025-09-28 00:17:31', 'Book ID 56 borrowed on 2025-09-27'),
(121, 16, 'returned book', '2025-09-28 00:17:42', 'Book ID 56 returned on 2025-09-28'),
(122, 16, 'borrowed book', '2025-09-28 00:26:27', 'Book ID 58 borrowed on 2025-09-27'),
(123, 16, 'returned book', '2025-09-28 00:26:37', 'Book ID 58 returned on 2025-09-28'),
(124, 16, 'borrowed book', '2025-09-28 00:28:05', 'Book ID 59 borrowed on 2025-09-27'),
(125, 16, 'borrowed book', '2025-09-28 00:28:07', 'Book ID 58 borrowed on 2025-09-27'),
(126, 16, 'borrowed book', '2025-09-28 00:28:08', 'Book ID 57 borrowed on 2025-09-27'),
(127, 16, 'returned book', '2025-09-28 00:28:31', 'Book ID 59 returned on 2025-09-28'),
(128, 16, 'returned book', '2025-09-28 00:28:33', 'Book ID 58 returned on 2025-09-28'),
(129, 16, 'returned book', '2025-09-28 00:28:35', 'Book ID 57 returned on 2025-09-28'),
(130, 16, 'borrowed book', '2025-09-28 00:44:17', 'Book ID 59 borrowed on 2025-09-27'),
(131, 16, 'returned book', '2025-09-28 00:44:26', 'Book ID 59 returned on 2025-09-28'),
(132, 16, 'borrowed book', '2025-09-28 00:44:38', 'Book ID 59 borrowed on 2025-09-27'),
(133, 16, 'borrowed book', '2025-09-28 00:44:40', 'Book ID 58 borrowed on 2025-09-27'),
(134, 16, 'borrowed book', '2025-09-28 00:44:41', 'Book ID 57 borrowed on 2025-09-27'),
(135, 16, 'borrowed book', '2025-09-28 00:44:43', 'Book ID 56 borrowed on 2025-09-27'),
(136, 26, 'borrowed book', '2025-09-28 00:45:06', 'Book ID 54 borrowed on 2025-09-27'),
(137, 26, 'borrowed book', '2025-09-28 00:45:08', 'Book ID 53 borrowed on 2025-09-27'),
(138, 26, 'borrowed book', '2025-09-28 00:45:10', 'Book ID 52 borrowed on 2025-09-27'),
(139, 16, 'returned book', '2025-09-28 00:45:28', 'Book ID 59 returned on 2025-09-28'),
(140, 16, 'returned book', '2025-09-28 00:45:32', 'Book ID 56 returned on 2025-09-28'),
(141, 16, 'returned book', '2025-09-28 00:45:35', 'Book ID 57 returned on 2025-09-28'),
(142, 16, 'returned book', '2025-09-28 00:45:37', 'Book ID 58 returned on 2025-09-28'),
(143, 16, 'borrowed book', '2025-09-28 00:46:01', 'Book ID 57 borrowed on 2025-09-27'),
(144, 16, 'returned book', '2025-09-28 00:46:05', 'Book ID 57 returned on 2025-09-28'),
(145, 26, 'returned book', '2025-09-28 00:46:23', 'Book ID 52 returned on 2025-09-28'),
(146, 26, 'returned book', '2025-09-28 00:46:25', 'Book ID 53 returned on 2025-09-28'),
(147, 26, 'returned book', '2025-09-28 00:46:27', 'Book ID 54 returned on 2025-09-28'),
(148, 26, 'borrowed book', '2025-09-28 19:02:42', 'Book ID 54 borrowed on 2025-09-28'),
(149, 26, 'borrowed book', '2025-09-28 19:02:52', 'Book ID 59 borrowed on 2025-09-28'),
(150, 26, 'returned book', '2025-09-28 22:36:43', 'Book ID 54 returned on 2025-09-28'),
(151, 26, 'returned book', '2025-09-28 22:36:54', 'Book ID 59 returned on 2025-09-28'),
(152, 26, 'borrowed book', '2025-09-28 22:37:31', 'Book ID 58 borrowed on 2025-09-28'),
(153, 26, 'returned book', '2025-09-28 22:37:59', 'Book ID 58 returned on 2025-09-28'),
(154, 16, 'borrowed book', '2025-09-28 22:45:47', 'Book ID 58 borrowed on 2025-09-28'),
(155, 16, 'borrowed book', '2025-09-28 22:45:49', 'Book ID 57 borrowed on 2025-09-28'),
(156, 26, 'borrowed book', '2025-09-29 22:35:09', 'Book ID 51 borrowed on 2025-09-29'),
(157, 26, 'returned book', '2025-09-29 22:35:24', 'Book ID 51 returned on 2025-09-29'),
(158, 26, 'borrowed book', '2025-09-29 22:36:32', 'Book ID 53 borrowed on 2025-09-29'),
(159, 26, 'returned book', '2025-09-29 22:36:38', 'Book ID 53 returned on 2025-09-29'),
(160, 26, 'borrowed book', '2025-09-29 22:36:53', 'Book ID 51 borrowed on 2025-09-29'),
(161, 26, 'returned book', '2025-09-29 22:37:04', 'Book ID 51 returned on 2025-09-29');

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
(51, 'The man', 'Jayr', 'Fiction', '123-1233123-1', '2025-09-05', 21, 'uploads/1758381558_Screenshot 2025-09-19 002440.png'),
(52, 'New books', 'Luayon', 'Fiction', '999-99-9999-9', '2025-07-10', 24, 'uploads/1758447063_Screenshot 2025-09-20 050904.png'),
(53, 'Labaders', 'Hello', 'Fiction', '66-666-666-66', '2025-09-05', 23, 'uploads/1758447206_Screenshot 2025-09-19 030530.png'),
(54, 'Yawa book', 'yawa', 'Fiction', '11-222-333-55', '2025-09-10', 24, 'uploads/1758447232_Screenshot 2025-09-19 174256.png'),
(55, 'Romeo and Juliet', 'Jayr Luayon', 'Fiction', '123-12334523-', '2025-09-04', 24, 'uploads/1758542380_Screenshot 2025-09-18 205858.png'),
(56, 'Ibong-Adarna', 'José de la Cruz', 'Fiction', '777-777-77777', '1980-02-18', 18, 'uploads/1758556319_Screenshot 2025-09-22 034958.png'),
(57, 'Alamat ng pinya', 'Boots S. Agbayani Pastor', 'Fiction', '888-888-8888', '2008-06-18', 48, 'uploads/1758556609_Screenshot 2025-09-22 035644.png'),
(58, 'Julitos Book', 'Julito Great', 'Fiction', '2020-20-2002', '2025-09-12', 17, 'uploads/1758721492_Screenshot 2025-09-22 035644.png'),
(59, 'Bugsy The Surigawnyon', 'Jayr Luayon', 'Fiction', '1111-111-222', '2025-09-18', 9, 'uploads/1758724110_Screenshot 2025-09-24 222817.png');

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
(101, 16, 58, '2025-09-28', '2025-10-01', NULL, 0, 0.00),
(102, 16, 57, '2025-09-28', '2025-10-01', NULL, 0, 0.00),
(103, 26, 51, '2025-09-29', '2025-10-13', '2025-09-29', 0, 0.00),
(104, 26, 53, '2025-09-29', '2025-10-02', '2025-09-29', 0, 0.00),
(105, 26, 51, '2025-09-29', '2025-10-02', '2025-09-29', 0, 0.00);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

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
