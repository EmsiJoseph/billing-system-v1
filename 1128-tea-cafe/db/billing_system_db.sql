-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: Apr 23, 2024 at 03:32 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `billing-system-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categoryId` int NOT NULL,
  `categoryName` varchar(255) NOT NULL,
  `categoryDesc` text NOT NULL,
  `categoryCreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categoryId`, `categoryName`, `categoryDesc`, `categoryCreateDate`) VALUES
(1, 'Milk Tea', 'Deliciously creamy milk tea varieties with a range of flavors.', '2024-04-20 00:31:39'),
(2, 'Frappe', 'A delightful mix of frappes, available in coffee-based and non-coffee-based options.', '2024-04-20 00:31:39'),
(3, 'Cold Brew', 'Smooth and rich cold brew selections for a refreshing caffeine fix.', '2024-04-20 00:31:39'),
(5, 'Cheesecake', 'Sweet and savory cheesecake beverages, a dessert in a cup.', '2024-04-20 00:31:39'),
(6, 'Fruit Tea', 'Refreshing water-based fruit teas, perfect for cooling down.', '2024-04-20 00:31:39'),
(7, 'Hot Brew', 'Traditional hot brews to warm and wake up your senses.', '2024-04-20 00:31:39'),
(8, 'Milk Series', 'Specially crafted milk-based beverages offering a comforting and creamy experience.', '2024-04-20 00:31:39');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `contactId` int NOT NULL,
  `userId` int NOT NULL,
  `email` varchar(35) NOT NULL,
  `phoneNo` bigint NOT NULL,
  `orderId` varchar(50) DEFAULT NULL COMMENT 'If problem is not related to the order then order id = 0',
  `message` text NOT NULL,
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contactreply`
--

CREATE TABLE `contactreply` (
  `id` int NOT NULL,
  `contactId` int NOT NULL,
  `userId` int NOT NULL,
  `message` text NOT NULL,
  `datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `id` int NOT NULL,
  `orderId` varchar(50) NOT NULL,
  `prodId` int NOT NULL,
  `size` varchar(255) NOT NULL,
  `itemQuantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orderitems`
--


-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderId` varchar(50) NOT NULL,
  `userId` int NOT NULL,
  `amount` int NOT NULL,
  `paymentMode` enum('0','1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '2' COMMENT '0=Maya, 1=Gcash, 2=Cash',
  `orderStatus` enum('0','1','2','3','4','5','6') NOT NULL DEFAULT '0' COMMENT '0=Order Placed, 1=Order Confirmed, 2=Preparing your Order, 3=Your order is ready for pick-up!, 4=Order Received, 5=Order Denied, 6=Order Cancelled.',
  `orderDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--


-- --------------------------------------------------------

--
-- Table structure for table `prod`
--

CREATE TABLE `prod` (
  `prodId` int NOT NULL,
  `prodName` varchar(255) NOT NULL,
  `prodDesc` text NOT NULL,
  `prodCategoryId` int NOT NULL,
  `prodPubDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `prod`
--

INSERT INTO `prod` (`prodId`, `prodName`, `prodDesc`, `prodCategoryId`, `prodPubDate`) VALUES
(6, 'Java Chip', 'Frappe with mocha and chocolate chip pieces.', 2, '2024-04-20 00:31:39'),
(23, 'Spanish Latte', 'A creamy and sweet latte with a touch of spice.', 7, '2024-04-20 00:31:39'),
(31, 'Caramel Macchiato', 'This unique blend of ingredients gives the caramel macchiato a distinct, sweet flavor profile with a delightful hint of caramel. The vanilla syrup brings a touch of sweetness, while the espresso provides a bold, coffee-forward taste.', 1, '2024-04-23 01:30:34');

-- --------------------------------------------------------

--
-- Table structure for table `prod_sizes`
--

CREATE TABLE `prod_sizes` (
  `prodId` int NOT NULL,
  `size` varchar(255) NOT NULL,
  `price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `prod_sizes`
--

INSERT INTO `prod_sizes` (`prodId`, `size`, `price`) VALUES
(6, 'Large', 55),
(6, 'Medium', 45),
(23, 'Large', 45),
(31, 'Large', 45),
(31, 'Medium', 35);

-- --------------------------------------------------------

--
-- Table structure for table `sitedetail`
--

CREATE TABLE `sitedetail` (
  `tempId` int NOT NULL,
  `systemName` varchar(21) NOT NULL,
  `email` varchar(35) NOT NULL,
  `contact1` bigint NOT NULL,
  `contact2` bigint DEFAULT NULL COMMENT 'Optional',
  `address` text NOT NULL,
  `dateTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sitedetail`
--

INSERT INTO `sitedetail` (`tempId`, `systemName`, `email`, `contact1`, `contact2`, `address`, `dateTime`) VALUES
(1, '1128 Tea & Cafe', 'contact@1128teaandcafe.com', 1234567890, NULL, '123 Tea Street, Beverage City', '2024-04-20 00:31:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int NOT NULL,
  `nickname` varchar(21) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `firstName` varchar(21) NOT NULL,
  `lastName` varchar(21) NOT NULL,
  `email` varchar(35) NOT NULL,
  `phone` bigint NOT NULL,
  `userType` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=customer, 1=admin',
  `password` varchar(255) NOT NULL,
  `joinDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `nickname`, `firstName`, `lastName`, `email`, `phone`, `userType`, `password`, `joinDate`) VALUES
(1, 'adminuser', 'Admin', 'User', 'admin@gmail.com', 9876543210, '1', 'admin', '2024-04-20 00:31:39'),


-- --------------------------------------------------------

--
-- Table structure for table `viewcart`
--

CREATE TABLE `viewcart` (
  `cartItemId` int NOT NULL,
  `userId` int NOT NULL,
  `prodId` int NOT NULL,
  `size` varchar(10) NOT NULL,
  `itemQuantity` int NOT NULL,
  `addedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoryId`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`contactId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `orderId` (`orderId`);

--
-- Indexes for table `contactreply`
--
ALTER TABLE `contactreply`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contactId` (`contactId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orderId` (`orderId`),
  ADD KEY `fk_orderitems_product` (`prodId`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `prod`
--
ALTER TABLE `prod`
  ADD PRIMARY KEY (`prodId`),
  ADD KEY `fk_category` (`prodCategoryId`);

--
-- Indexes for table `prod_sizes`
--
ALTER TABLE `prod_sizes`
  ADD PRIMARY KEY (`prodId`,`size`);

--
-- Indexes for table `sitedetail`
--
ALTER TABLE `sitedetail`
  ADD PRIMARY KEY (`tempId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `viewcart`
--
ALTER TABLE `viewcart`
  ADD PRIMARY KEY (`cartItemId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `viewcart_ibfk_2` (`prodId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categoryId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `contactId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contactreply`
--
ALTER TABLE `contactreply`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `prod`
--
ALTER TABLE `prod`
  MODIFY `prodId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `sitedetail`
--
ALTER TABLE `sitedetail`
  MODIFY `tempId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `viewcart`
--
ALTER TABLE `viewcart`
  MODIFY `cartItemId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `contact_ibfk_2` FOREIGN KEY (`orderId`) REFERENCES `orders` (`orderId`);

--
-- Constraints for table `contactreply`
--
ALTER TABLE `contactreply`
  ADD CONSTRAINT `contactreply_ibfk_1` FOREIGN KEY (`contactId`) REFERENCES `contact` (`contactId`) ON DELETE CASCADE,
  ADD CONSTRAINT `contactreply_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);

--
-- Constraints for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD CONSTRAINT `fk_orderitems_product` FOREIGN KEY (`prodId`) REFERENCES `prod` (`prodId`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderitems_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `orders` (`orderId`),
  ADD CONSTRAINT `orderitems_ibfk_2` FOREIGN KEY (`prodId`) REFERENCES `prod` (`prodId`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);

--
-- Constraints for table `prod`
--
ALTER TABLE `prod`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`prodCategoryId`) REFERENCES `categories` (`categoryId`) ON DELETE CASCADE,
  ADD CONSTRAINT `prod_ibfk_1` FOREIGN KEY (`prodCategoryId`) REFERENCES `categories` (`categoryId`);

--
-- Constraints for table `prod_sizes`
--
ALTER TABLE `prod_sizes`
  ADD CONSTRAINT `fk_prod_sizes_product` FOREIGN KEY (`prodId`) REFERENCES `prod` (`prodId`) ON DELETE CASCADE,
  ADD CONSTRAINT `prod_sizes_ibfk_1` FOREIGN KEY (`prodId`) REFERENCES `prod` (`prodId`) ON DELETE CASCADE;

--
-- Constraints for table `viewcart`
--
ALTER TABLE `viewcart`
  ADD CONSTRAINT `viewcart_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `viewcart_ibfk_2` FOREIGN KEY (`prodId`) REFERENCES `prod` (`prodId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
