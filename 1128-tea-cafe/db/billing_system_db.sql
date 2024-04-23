-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: Apr 22, 2024 at 06:30 PM
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
(4, 'Premium Frappe', 'Our exclusive premium frappes for when you feel like indulging.', '2024-04-20 00:31:39'),
(5, 'Cheesecake', 'Sweet and savory cheesecake beverages, a dessert in a cup.', '2024-04-20 00:31:39'),
(6, 'Fruit Tea', 'Refreshing water-based fruit teas, perfect for cooling down.', '2024-04-20 00:31:39'),
(7, 'Hot Brew', 'Traditional hot brews to warm and wake up your senses.', '2024-04-20 00:31:39'),
(8, 'Milk Series', 'Specially crafted milk-based beverages offering a comforting and creamy experience.', '2024-04-20 00:31:39'),
(9, 'test', 'test', '2024-04-22 13:48:07');

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


-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderId` varchar(50) NOT NULL,
  `userId` int NOT NULL,
  `amount` int NOT NULL,
  `paymentMode` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '0=Maya, 1=Gcash, 2=Cash',
  `orderStatus` enum('0','1','2','3','4','5','6') NOT NULL DEFAULT '0' COMMENT '0=Order Placed, 1=Order Confirmed, 2=Preparing your Order, 3=Your order is ready for pick-up!, 4=Order Received, 5=Order Denied, 6=Order Cancelled.',
  `orderDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
(1, 'Wintermelon', 'A refreshing milk tea with a sweet, caramelized flavor.', 1, '2024-04-20 00:31:39'),
(2, 'Okinawa', 'Rich and creamy milk tea with a deep, roasted brown sugar aroma.', 1, '2024-04-20 00:31:39'),
(3, 'Dark Chocolate', 'Milk tea infused with decadent dark chocolate.', 1, '2024-04-20 00:31:39'),
(4, 'Dark Caramel', 'A sweet and bold frappe with dark caramel.', 2, '2024-04-20 00:31:39'),
(5, 'Dark Mocha', 'A rich blend of coffee and dark chocolate.', 2, '2024-04-20 00:31:39'),
(6, 'Java Chip', 'Frappe with mocha and chocolate chip pieces.', 2, '2024-04-20 00:31:39'),
(7, 'Strawberries and Milk', 'Sweet and creamy with real strawberry pieces.', 8, '2024-04-20 00:31:39'),
(8, 'Mango and Milk', 'A tropical blend of ripe mango and milk.', 8, '2024-04-20 00:31:39'),
(9, 'Blueberries and Milk', 'Rich milk mixed with sweet and tart blueberries.', 8, '2024-04-20 00:31:39'),
(10, 'Iced Americano', 'A strong and invigorating cold brew for coffee purists.', 3, '2024-04-20 00:31:39'),
(11, 'Spanish Latte', 'A sweet and creamy cold brew variation with a hint of spice.', 3, '2024-04-20 00:31:39'),
(12, 'White Chocolate', 'Cold brew coffee with sweet and luxurious white chocolate.', 3, '2024-04-20 00:31:39'),
(13, 'Dark Choco Lava', 'A rich and chocolatey premium frappe with a molten core of dark chocolate.', 4, '2024-04-20 00:31:39'),
(14, 'Red Velvet Cream Cheese', 'A dessert-like premium frappe with the classic flavors of red velvet and cream cheese.', 4, '2024-04-20 00:31:39'),
(15, 'Kopi Forest', 'An intense coffee-flavored premium frappe with a forest of whipped cream.', 4, '2024-04-20 00:31:39'),
(16, 'Wintermelon Cheesecake', 'A delightful cheesecake beverage with the sweet taste of wintermelon.', 5, '2024-04-20 00:31:39'),
(17, 'Okinawa Cheesecake', 'Cheesecake blended with the deep flavors of Okinawa brown sugar.', 5, '2024-04-20 00:31:39'),
(18, 'Matcha Cheesecake', 'Green tea matcha mixed with a creamy cheesecake base.', 5, '2024-04-20 00:31:39'),
(19, 'Lychee', 'A fragrant and sweet fruit tea with lychee flavors.', 6, '2024-04-20 00:31:39'),
(20, 'Strawberry', 'Fresh and fruity strawberry tea, a delightful refresher.', 6, '2024-04-20 00:31:39'),
(21, 'Green Apple', 'Tangy and crisp green apple fruit tea for a zesty pick-me-up.', 6, '2024-04-20 00:31:39'),
(22, 'Americano', 'A classic hot brew, robust and energizing.', 7, '2024-04-20 00:31:39'),
(23, 'Spanish Latte', 'A creamy and sweet latte with a touch of spice.', 7, '2024-04-20 00:31:39'),
(24, 'White Chocolate', 'Luxurious hot white chocolate for a cozy treat.', 7, '2024-04-20 00:31:39'),
(27, 'test', 'test', 7, '2024-04-22 14:59:10');

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
(1, 'Large', 38),
(1, 'Medium', 28),
(2, 'Large', 38),
(2, 'Medium', 28),
(3, 'Large', 38),
(3, 'Medium', 28),
(4, 'Large', 55),
(4, 'Medium', 45),
(5, 'Large', 55),
(5, 'Medium', 45),
(6, 'Large', 55),
(6, 'Medium', 45),
(7, 'Large', 65),
(7, 'Medium', 55),
(8, 'Large', 65),
(8, 'Medium', 55),
(9, 'Large', 65),
(9, 'Medium', 55),
(10, 'Large', 55),
(10, 'Medium', 45),
(11, 'Large', 55),
(11, 'Medium', 45),
(12, 'Large', 55),
(12, 'Medium', 45),
(13, 'Standard', 88),
(14, 'Standard', 88),
(15, 'Standard', 88),
(16, 'Large', 53),
(16, 'Medium', 43),
(17, 'Large', 53),
(17, 'Medium', 43),
(18, 'Large', 53),
(18, 'Medium', 43),
(19, 'Large', 45),
(19, 'Medium', 35),
(20, 'Large', 45),
(20, 'Medium', 35),
(21, 'Large', 45),
(21, 'Medium', 35),
(22, 'Large', 45),
(23, 'Large', 45),
(24, 'Large', 45),
(27, 'Large', 45);

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
(1, 'adminuser', 'Admin', 'User', 'admin@gmail.com', 9876543210, '1', '2yn.4fvaTgedM', '2024-04-20 00:31:39'),
(2, 'johndoe', 'John', 'Doe', 'john@example.com', 1234567890, '0', '2yvvkxG8VuAto', '2024-04-20 00:31:39'),
(3, 'mcagbanlog', 'Mc Joseph', 'Agbanlog', 'mcagbanlog@gmail.com', 9762623231, '1', '2yuklu89kvUwI', '2024-04-20 14:58:09'),
(4, 'fas', 'Mc Joseph', 'Agbanlog', 'mc@gmail.com', 9762623231, '0', '2yqlWKRW02Xf.', '2024-04-22 17:03:26'),
(5, 'fasfsafa', 'Mc Joseph', 'Agbanlog', 'joseph@gmail.com', 9762623231, '0', '2yqlWKRW02Xf.', '2024-04-22 18:13:13');

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
-- Dumping data for table `viewcart`
--

INSERT INTO `viewcart` (`cartItemId`, `userId`, `prodId`, `size`, `itemQuantity`, `addedDate`) VALUES
(31, 3, 2, 'Large', 1, '2024-04-22 05:52:29');

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
  ADD KEY `prodId` (`prodId`);

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
  ADD KEY `prodCategoryId` (`prodCategoryId`);

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
  ADD KEY `prodId` (`prodId`);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `prod`
--
ALTER TABLE `prod`
  MODIFY `prodId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `sitedetail`
--
ALTER TABLE `sitedetail`
  MODIFY `tempId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `viewcart`
--
ALTER TABLE `viewcart`
  MODIFY `cartItemId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

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
  ADD CONSTRAINT `prod_ibfk_1` FOREIGN KEY (`prodCategoryId`) REFERENCES `categories` (`categoryId`);

--
-- Constraints for table `prod_sizes`
--
ALTER TABLE `prod_sizes`
  ADD CONSTRAINT `prod_sizes_ibfk_1` FOREIGN KEY (`prodId`) REFERENCES `prod` (`prodId`) ON DELETE CASCADE;

--
-- Constraints for table `viewcart`
--
ALTER TABLE `viewcart`
  ADD CONSTRAINT `viewcart_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `viewcart_ibfk_2` FOREIGN KEY (`prodId`) REFERENCES `prod` (`prodId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
