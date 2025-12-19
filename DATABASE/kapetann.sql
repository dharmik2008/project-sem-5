-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 22, 2025 at 07:14 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kapetann`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`) VALUES
(1, 'admin', 'admin', 'admin@coffee.com'),
(2, 'dharmik', '123123123', 'dharmikpatel20062008@gmail.com'),
(3, 'netra', '10201020', 'netra123@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `product_image` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `product_name`, `product_price`, `quantity`, `product_image`, `created_at`) VALUES
(45, NULL, 'Coffee-Frappuccino', 852.00, 1, 'images/Starbucks®.jpeg', '2025-08-21 19:54:56'),
(46, NULL, 'du', 541.00, 1, 'images/cart-item-8.png', '2025-08-21 19:55:23'),
(47, NULL, 'Mazagran', 950.00, 1, 'images/Mazagran.jpg', '2025-08-21 19:59:41'),
(48, NULL, 'Mazagran', 950.00, 1, 'images/Mazagran.jpg', '2025-08-21 20:53:02'),
(49, NULL, 'Mazagran', 950.00, 1, 'images/Mazagran.jpg', '2025-08-21 20:59:29'),
(50, NULL, 'Coffee-Frappuccino', 852.00, 1, 'images/Starbucks®.jpeg', '2025-08-21 22:44:40'),
(51, NULL, 'du', 541.00, 1, 'images/cart-item-8.png', '2025-08-22 10:38:26'),
(52, NULL, 'Cold Brew', 1200.00, 1, 'images/Vanilla-Sweet-Cream-Cold-Brew.jpg', '2025-08-22 10:38:58');

-- --------------------------------------------------------

--
-- Table structure for table `coffees`
--

CREATE TABLE `coffees` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coffees`
--

INSERT INTO `coffees` (`id`, `name`, `price`, `image`) VALUES
(1, 'Coffee-Frappuccino', 852.00, 'Starbucks®.jpeg'),
(19, 'Mazagran', 950.00, 'Mazagran.jpg'),
(21, 'du', 541.00, 'cart-item-8.png'),
(22, 'chand', 954.00, 'cart-item-7.png'),
(23, 'hello', 853.00, 'cart-item-10.png'),
(24, 'Demo', 654.00, 'cart-item-9.png'),
(25, 'Americano', 853.00, 'Americano.jpg'),
(26, 'Cappuccino', 1234.00, 'Cappucino.png'),
(27, 'Espresso', 650.00, 'coffeemaker.jpg'),
(28, 'Latte', 950.00, 'hot-chocolate.jpg'),
(29, 'Mocha', 1100.00, 'Mocha.jpg'),
(30, 'Cortado', 800.00, 'Cortado.jpg'),
(31, 'Macchiato', 750.00, 'Macchiato.jpg'),
(32, 'Cold Brew', 1200.00, 'Vanilla-Sweet-Cream-Cold-Brew.jpg'),
(33, 'Frappuccino', 1350.00, 'Coffee-Frappuccino®.jpeg'),
(34, 'Chai Tea Latte', 900.00, 'chai tea latte.jpg'),
(35, 'Iced Matcha Latte', 1150.00, 'Iced-Matcha-Latte.jpg'),
(36, 'Pumpkin Spice Latte', 1300.00, 'pumpkin spice latte.jpeg'),
(37, 'Honey Almondmilk Flat White', 1250.00, 'iced-honey-almondmilk-flat-white.jpg'),
(38, 'Frosted Brew Bliss', 1400.00, 'Frosted Brew Bliss.jpg'),
(39, 'Frozen Hazelnut Harmony', 1450.00, 'Frozen Hazelnut Harmony.jpg'),
(40, 'Mazagran', 950.00, 'Mazagran.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(20) DEFAULT 'unread',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `name`, `email`, `message`, `status`, `created_at`) VALUES
(1, 5, 'dharmik', 'dharmikpatel20062008@gmail.com', 'your coffee is so nice!!', 'read', '2025-08-21 22:10:28'),
(2, 5, 'dharmik', 'dharmikpatel20062008@gmail.com', 'nice coffee and website!', 'read', '2025-08-21 22:13:44'),
(3, 5, 'dharmik', 'dharmikpatel20062008@gmail.com', 'r4tg4fr3t', 'unread', '2025-08-21 22:35:22');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `title` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal_amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `invoice_number` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `price`, `title`, `quantity`, `subtotal_amount`, `date`, `invoice_number`, `user_id`, `address`, `phone`) VALUES
(1, 40.00, 'COLOMBIAN SUPREMO CUP (12 OZ)', 1, 40.00, '2023-04-21', 'INV-760084', 0, '', NULL),
(2, 45.00, 'AMERICANO - HOT ESPRESSO (12 OZ)', 1, 45.00, '2023-04-21', 'INV-760084', 0, '', NULL),
(3, 40.00, 'COLOMBIAN SUPREMO CUP (12 OZ)', 1, 40.00, '2023-04-21', 'INV-174394', 2, '', NULL),
(4, 50.00, 'NITRO COLD BREW W/ STRAW (12 OZ)', 1, 50.00, '2023-04-21', 'INV-741371', 2, '', NULL),
(5, 45.00, 'AMERICANO - HOT ESPRESSO (12 OZ)', 1, 45.00, '2023-04-21', 'INV-982020', 2, '', NULL),
(6, 40.00, 'COLOMBIAN SUPREMO CUP (12 OZ)', 1, 40.00, '2023-04-21', 'INV-144116', 2, '', NULL),
(7, 853.00, 'dharmik', 1, 853.00, '0000-00-00', 'INV-6895842d6e8d3', 0, '', NULL),
(8, 950.00, 'Mazagran', 1, 950.00, '0000-00-00', 'INV-6895c3514f2d2', 0, '', NULL),
(9, 950.00, 'Mazagran', 2, 1900.00, '0000-00-00', 'INV-6895c974e70b0', 0, '', NULL),
(10, 154.00, 'sd', 1, 154.00, '0000-00-00', 'INV-6895c974e8986', 0, '', NULL),
(11, 950.00, 'Mazagran', 1, 950.00, '0000-00-00', 'INV-6898b99dd5b5d', 0, '', NULL),
(12, 154.00, 'sd', 4, 616.00, '0000-00-00', 'INV-6898b99dd6a27', 0, '', NULL),
(13, 853.00, 'dharmik', 1, 853.00, '0000-00-00', 'INV-6898b9ca72f4f', 0, '', NULL),
(14, 853.00, 'hello', 1, 853.00, '0000-00-00', 'INV-6898b9eae6644', 0, '', NULL),
(15, 950.00, 'Mazagran', 1, 950.00, '0000-00-00', 'INV-6899e904097e7', 0, '', NULL),
(16, 950.00, 'Mazagran', 1, 950.00, '0000-00-00', 'INV-6899eb767e42c', 0, '', NULL),
(17, 853.00, 'hello', 3, 2559.00, '0000-00-00', 'INV-6899eb767f191', 0, '', NULL),
(18, 853.00, 'dharmik', 1, 853.00, '0000-00-00', 'INV-6899f58cdfedb', 0, '', NULL),
(19, 950.00, 'Mazagran', 5, 4750.00, '0000-00-00', 'INV-6899f594293ea', 0, '', NULL),
(20, 541.00, 'du', 1, 541.00, '0000-00-00', 'INV-6899f5a71c556', 0, '', NULL),
(21, 950.00, 'Mazagran', 1, 950.00, '0000-00-00', 'INV-6899f5a71d355', 0, '', NULL),
(22, 853.00, 'hello', 1, 853.00, '0000-00-00', 'INV-689b01dfee051', 0, '', NULL),
(23, 950.00, 'Mazagran', 1, 950.00, '0000-00-00', 'INV-689b01dff0202', 0, '', NULL),
(24, 853.00, 'dharmik', 9, 7677.00, '0000-00-00', 'INV-689b07cb92f7c', 0, 'fjkjhgfdghmjhng', '8849484307'),
(25, 950.00, 'Mazagran', 1, 950.00, '0000-00-00', 'INV-689b07cb95194', 0, 'fjkjhgfdghmjhng', '8849484307'),
(26, 853.00, 'dharmik', 1, 853.00, '0000-00-00', 'INV-689b0a41e9163', 0, 'fjkjhgfdghmjhng', '8849484307'),
(27, 950.00, 'Mazagran', 1, 950.00, '0000-00-00', 'INV-689b0a41e9e97', 0, 'fjkjhgfdghmjhng', '8849484307'),
(28, 541.00, 'du', 1, 541.00, '0000-00-00', 'INV-689b0a5487ffb', 0, 'fjkjhgfdghmjhng', '8849484307'),
(29, 541.00, 'du', 2, 1082.00, '0000-00-00', 'INV-689c364d8b7b8', 0, 'asdfghjkl', '8849484307'),
(30, 853.00, 'dharmik', 4, 3412.00, '0000-00-00', 'INV-689c37ce6e0ef', 0, 'asdfghjkl', '8849484307'),
(31, 853.00, 'dharmik', 1, 853.00, '0000-00-00', 'INV-689c383e90785', 0, 'asdfghjkl', '8849484307'),
(32, 853.00, 'hello', 2, 1706.00, '0000-00-00', 'INV-68a43d3edffcc', 0, 'darshan univarsity', '8849484307'),
(33, 853.00, 'dharmik', 1, 853.00, '0000-00-00', 'INV-68a449394b2b6', 0, 'darshan uni.', '8849484307'),
(34, 950.00, 'Mazagran', 1, 950.00, '0000-00-00', 'INV-68a569525f9b8', 0, 'asdfghjk', '8849484307'),
(35, 950.00, 'Mazagran', 1, 950.00, '0000-00-00', 'INV-68a72a3490c37', 0, 'darshan uni', '8849484307'),
(36, 950.00, 'Mazagran', 1, 950.00, '0000-00-00', 'INV-68a72a8963e9d', 0, 'darshan uni', '8849484307'),
(37, 541.00, 'du', 1, 541.00, '0000-00-00', 'INV-68a72ad659fa3', 0, 'darshan uni', '8849484307'),
(38, 853.00, 'hello', 1, 853.00, '0000-00-00', 'INV-68a72b26cd489', 0, 'darshan uni', '8849484307'),
(39, 852.00, 'Coffee-Frappuccino', 2, 1704.00, '0000-00-00', 'INV-68a72c4410b11', 0, 'darshan uni', '8849484307'),
(40, 852.00, 'Coffee-Frappuccino', 1, 852.00, '0000-00-00', 'INV-68a7540b06b81', 0, 'du', '8849484307'),
(41, 1200.00, 'Cold Brew', 1, 1200.00, '0000-00-00', 'INV-68a7fb8994108', 0, 'du', '8849484307');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `person` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `table_id` int(11) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Confirmed',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `name`, `email`, `date`, `time`, `person`, `user_id`, `table_id`, `status`, `created_at`) VALUES
(20, 'chand', 'dharmikpatel20062008@gmail.com', '2025-08-11', '22:42:00', 2, NULL, 2, 'Confirmed', '2025-08-21 18:42:44'),
(21, 'Nirav', 'niravnimavat01@gmail.com', '2025-08-20', '05:00:00', 5, NULL, 2, 'Pending', '2025-08-22 10:40:57');

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` int(11) NOT NULL,
  `table_number` varchar(20) NOT NULL,
  `capacity` int(11) NOT NULL,
  `location` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`id`, `table_number`, `capacity`, `location`, `status`) VALUES
(1, '1', 5, 'Corner', 'Available'),
(2, '2', 5, 'center', 'Available'),
(3, '3', 2, 'near reception', 'Available'),
(4, '4', 6, 'window side', 'Available'),
(5, '5', 5, 'Middle', 'Available'),
(6, '6', 6, 'Corner', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `create_datetime` datetime NOT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `create_datetime`, `reset_token`, `token_expiry`) VALUES
(2, 'admin', 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3', '2023-04-18 11:00:40', NULL, NULL),
(5, 'dharmik', 'dharmikpatel20062008@gmail.com', 'f5bb0c8de146c67b44babbf4e6584cc0', '2025-08-07 18:47:11', '98cd437883401c0d6b4585154fe28d468c9e70a56c7fe3cf1a6bc6739f4a6d6c', '2025-08-20 16:23:41'),
(7, 'netra', 'netra123@gmail.com', '373866650583956ca0186f84621f42bd', '2025-08-21 19:13:52', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coffees`
--
ALTER TABLE `coffees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `table_id` (`table_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `coffees`
--
ALTER TABLE `coffees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
