-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2023 at 03:52 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eatkubtang`
--

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `bill_id` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `bill_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `bill_amount` decimal(10,2) NOT NULL,
  `bill_status` enum('Paid','Unpaid') NOT NULL,
  `payment_receipt_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `bill`
--

INSERT INTO `bill` (`bill_id`, `orderID`, `bill_date`, `bill_amount`, `bill_status`, `payment_receipt_path`) VALUES
(11, 14, '2023-10-16 21:27:01', '1019.00', 'Paid', '../../images/invoice/receipt_14.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `customer_id`, `product_id`, `quantity`) VALUES
(32, 7, 13, 1),
(33, 7, 23, 1),
(34, 7, 15, 1),
(35, 10, 10, 2),
(36, 10, 13, 1);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(10) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `description`) VALUES
(63, 'Chair & Desk', 'เก้าอี้และโต๊ะคอมพิวเตอร์'),
(64, 'Mouse', 'Mouse ทั้งแบบธรรมดา ไร้สาย หรือเกมมิงเกียร์'),
(65, 'Keyboard', 'Gaming Keybord FOR GAMER'),
(66, 'Keycaps & Custom', 'ปุ่ม Keycaps สำหรับ Keybord ขนาดต่างๆ'),
(67, 'Headset & In Ear', 'หูฟัง Gaming '),
(68, 'Speaker', 'ลำโพงตั้งโต๊ะเพื่อคนรักเครื่องเสียง'),
(69, 'Micophone', 'ไมค์ชัด เสียงแจ๋ว'),
(70, 'Accessory', 'เครื่องเคียงสำรหับคอมพิวเตอร์');

-- --------------------------------------------------------

--
-- Table structure for table `coupon`
--

CREATE TABLE `coupon` (
  `coupon_id` int(11) NOT NULL,
  `coupon_code` varchar(5) DEFAULT NULL,
  `discount_type` enum('percentage','amount') DEFAULT NULL,
  `discount_amount` int(11) DEFAULT NULL,
  `min_purchase` int(11) DEFAULT NULL,
  `coupon_quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `coupon`
--

INSERT INTO `coupon` (`coupon_id`, `coupon_code`, `discount_type`, `discount_amount`, `min_purchase`, `coupon_quantity`) VALUES
(7, '9ARM', 'percentage', 10, 0, 6),
(10, 'RERUN', 'percentage', 1000, 5000, 4),
(11, 'AR250', 'amount', 250, 1000, 99);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(10) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(10) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `img_URL` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `firstname`, `lastname`, `email`, `username`, `password`, `phone_number`, `address`, `img_URL`, `created_at`) VALUES
(1, 'วรกันต์', 'พฤฒิพฤกษ์', 'prun6.rr@gmail.com', 'Run jaaa', '$2y$10$zeh2SlPi795j0Z8G18WhNOsb6TpQdQMs4dBoYqlLUn.lmueyoNf/G', '0987654321', '43/2', '../../images/avatar/a07.jpg', '2023-10-04 19:24:38'),
(2, 'นิธิวัต', 'ตันจะโร', 'Pao@gmail.com', NULL, '$2y$10$EJgHLSyBw9Kynfj8ir37aeXfk7jnIXQRpIsj/fDnOTblqInGwADTS', NULL, NULL, NULL, '2023-10-08 03:36:37'),
(6, 'วรกันต์', 'พฤฒิพฤกษ์', 'Name@gmail.com', 'ReRun121', '$2y$10$ToOpVp2yiRd9VkSOMfWdp.B8DPQA1rJueFN6mbaoYfBhmgrV.YddK', '0968853815', '69/1', NULL, '2023-10-08 13:18:34'),
(7, 'อิรฟาน', 'เปาะซู', 'Pang@gmail.com', 'ไอ้ปัง', '$2y$10$yh6LUBVPGZeFvZjVSlUqCOj10HifCJAjf5zai9X21UkYssI3hNUK2', '0998765543', '32/7', '../../images/avatar/a05.jpg', '2023-10-08 18:07:03'),
(8, 'วรกันต์', 'พฤฒิพฤกษ์', 'q@gmail.com', 'q', '$2y$10$ymbCdDyn6RU0/n/z/nNuku2m0VOqHqbZ3N1Gs2iJ1yqsA9bsz/L8G', '0968853815', '69/1', NULL, '2023-10-09 10:27:58'),
(10, 'Vorakan', 'Pruethipruek', 'vorakan@gmail.com', 'ReRun007', '$2y$10$anZmuyzv/lqT1RNAbCjR3.PDKSRXmVXGCNXPDV3EQAh9NqOn5gH.C', '0968853815', '69/1', '../../images/customer/8f1.jpg', '2023-10-09 11:04:28'),
(11, 'ลุตฟี', 'โต๊ะแว๊ะ', 'lut@gmail.com', 'KuyLutt', '$2y$10$CCBkAwiouiE3ZHg9HFnL8OdXcSRMoD3SFqZGJMPw1bd63ibmv96Oe', '0987654321', '00/23', '../../images/avatar/a08.jpg', '2023-10-11 06:48:26'),
(12, 'อนุสรา', 'เขร็มล่า', 'nus@gmail.com', 'NongNus', '$2y$10$OxC41g2ZDIVABIQNV5j1d./FJB4YRiZkjZgXo4TT3b9TaYxfItvIO', '077894564', '14/7', '../../images/avatar/a04.jpg', '2023-10-11 08:57:12'),
(13, 'QQ', 'SS', 'p@gmail.com', 'QWER', '$2y$10$AEygcjWLdA0Rcxa3Ej3oyueVJgXgFAFwbXpnlUfJiZOAC33Oq/Ek6', '0987654321', 'er2/1', '../../images/avatar/a03.jpg', '2023-10-13 03:49:33'),
(14, 'วรกันต์', 'พฤฒิพฤกษ์', 'PP@gmail.com', 'qaz', '$2y$10$Dq.h6OIr.0K.BN3ShfFol.lK.iZ3mNg/F/LVCdqq4McikXXIV.W3e', '0968853815', '69/1', '../../images/customer7f.jpg', '2023-10-13 03:56:12');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(10) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(10) DEFAULT NULL,
  `img_URL` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `firstname`, `lastname`, `email`, `username`, `password`, `phone_number`, `img_URL`, `created_at`) VALUES
(1, 'วรกันต์', 'พฤฒิพฤกษ์', 'run@gmail.com', 'H_Run', '12345', '0968853815', '../../images/avatar/a01.jpg', '2023-10-11 17:47:54'),
(2, 'Jane', 'Smith', 'jane@example.com', 'janesmith', '12345', '2147483647', '../../images/avatar/a02.jpg', '2023-10-11 17:47:54'),
(3, 'Alice', 'Johnson', 'alice@example.com', 'alicej', '12345', '2147483647', '../../images/avatar/a03.jpg', '2023-10-11 17:47:54'),
(4, 'Bob', 'Brown', 'bob@example.com', 'bobb', '12345', '2147483647', '../../images/avatar/a04.jpg', '2023-10-11 17:47:54'),
(5, 'Eve', 'Wilson', 'eve@example.com', 'evew', '12345', '1112223333', '../../images/avatar/a05.jpg', '2023-10-11 17:47:54');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `orderID` int(11) NOT NULL,
  `orderDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `orderPrice` decimal(10,2) NOT NULL,
  `orderStatus` varchar(255) NOT NULL,
  `customer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`orderID`, `orderDate`, `orderPrice`, `orderStatus`, `customer_id`) VALUES
(14, '2023-10-16 21:26:23', '1019.00', 'Verifying Payment', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `od_id` int(11) NOT NULL,
  `orderID` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `od_quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `one_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`od_id`, `orderID`, `product_id`, `od_quantity`, `total_price`, `one_price`) VALUES
(23, 14, 9, 1, '990.00', '990.00'),
(24, 14, 13, 1, '279.00', '279.00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(10) NOT NULL,
  `category_id` int(10) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `img_url` varchar(255) DEFAULT NULL,
  `price` double NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `product_name`, `img_url`, `price`, `quantity`, `rating`) VALUES
(9, 63, 'เก้าอี้เกมมิ่ง โยกได้ ยะหู้ว', '../../images/product/a.png', 990, 24, '0.00'),
(10, 63, 'Gaming Desk', '../../images/product/a1.png', 3990, 24, '0.00'),
(11, 64, 'EGA TYPE M11', '../../images/product/a2.png', 390, 96, '0.00'),
(12, 64, 'EGA TYPE M3', '../../images/product/a3.png', 590, 47, '0.00'),
(13, 63, 'EGA TYPE M4', '../../images/product/a4.png', 279, 53, '0.00'),
(14, 65, 'Rubber Dome', '../../images/product/a5.png', 390, 31, '0.00'),
(15, 66, 'สวิตช์เปลี่ยนคีย์บอร์ด', '../../images/product/a6.png', 49, 68, '0.00'),
(16, 66, 'Keycaps', '../../images/product/a7.png', 199, 21, '0.00'),
(17, 67, 'Headset LED', '../../images/product/a8.png', 379, 45, '0.00'),
(18, 67, 'หูฟังอินเอียร์', '../../images/product/a9.png', 490, 23, '0.00'),
(19, 63, 'Speaker USB', '../../images/product/a10.png', 299, 21, '0.00'),
(20, 68, 'S1 Mini Stereo', '../../images/product/a11.png', 199, 67, '0.00'),
(21, 69, 'Microphone 96KHz', '../../images/product/a12.png', 999, 89, '0.00'),
(22, 69, 'Bunge 100Hz-10kHz', '../../images/product/a13.png', 199, 45, '0.00'),
(23, 63, 'Headset Stand', '../../images/product/a14.png', 129, 67, '0.00'),
(24, 70, 'Mouse PAD', '../../images/product/a15.png', 0, 0, '0.00'),
(25, 70, 'COOLER PAD', '../../images/product/a16.png', 450, 56, '0.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`bill_id`),
  ADD KEY `orderID` (`orderID`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`coupon_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`od_id`),
  ADD KEY `orderID` (`orderID`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bill`
--
ALTER TABLE `bill`
  MODIFY `bill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `coupon`
--
ALTER TABLE `coupon`
  MODIFY `coupon_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `od_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bill`
--
ALTER TABLE `bill`
  ADD CONSTRAINT `bill_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `order` (`orderID`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `order` (`orderID`),
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
