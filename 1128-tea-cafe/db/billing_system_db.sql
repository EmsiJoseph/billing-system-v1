

CREATE DATABASE IF NOT EXISTS `billing-system-db`;
USE `billing-system-db`;

-- --------------------------------------------------------

CREATE TABLE `categories` (
  `categoryId` int(12) NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(255) NOT NULL,
  `categoryDesc` text NOT NULL,
  `categoryCreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`categoryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

CREATE TABLE `users` (
  `userId` int(21) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(21) NOT NULL,
  `firstName` varchar(21) NOT NULL,
  `lastName` varchar(21) NOT NULL,
  `email` varchar(35) NOT NULL,
  `phone` bigint(20) NOT NULL,
  `userType` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=customer, 1=admin',
  `password` varchar(255) NOT NULL,
  `joinDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

CREATE TABLE `orders` (
  `orderId` varchar(50) NOT NULL,
  `userId` int(21) NOT NULL,
  `amount` int(200) NOT NULL,
  `paymentMode` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '0=Maya, 1=Gcash, 2=Cash',
  `orderStatus` enum('0','1','2','3','4','5','6') NOT NULL DEFAULT '0' COMMENT '0=Order Placed, 1=Order Confirmed, 2=Preparing your Order, 3=Your order is ready for pick-up!, 4=Order Received, 5=Order Denied, 6=Order Cancelled.',
  `orderDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pickupPersonName` VARCHAR(255) NOT NULL,
  `pickupPersonPhone` BIGINT(20) NOT NULL,
  `pickupTime` DATETIME NOT NULL,
  PRIMARY KEY (`orderId`),
  FOREIGN KEY (`userId`) REFERENCES `users`(`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

CREATE TABLE `prod` (
  `prodId` int(12) NOT NULL AUTO_INCREMENT,
  `prodName` varchar(255) NOT NULL,
  `prodDesc` text NOT NULL,
  `prodCategoryId` int(12) NOT NULL,
  `prodPubDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`prodId`),
  FOREIGN KEY (`prodCategoryId`) REFERENCES `categories`(`categoryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

CREATE TABLE `prod_sizes` (
  `prodId` int(12) NOT NULL,
  `size` varchar(255) NOT NULL,
  `price` int(12) NOT NULL,
  PRIMARY KEY (`prodId`, `size`),
  FOREIGN KEY (`prodId`) REFERENCES `prod`(`prodId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

CREATE TABLE `contact` (
  `contactId` int(21) NOT NULL AUTO_INCREMENT,
  `userId` int(21) NOT NULL,
  `email` varchar(35) NOT NULL,
  `phoneNo` bigint(21) NOT NULL,
  `orderId` varchar(50) DEFAULT NULL COMMENT 'If problem is not related to the order then order id = 0',
  `message` text NOT NULL,
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`contactId`),
  FOREIGN KEY (`userId`) REFERENCES `users`(`userId`),
  FOREIGN KEY (`orderId`) REFERENCES `orders`(`orderId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

CREATE TABLE `contactreply` (
  `id` int(21) NOT NULL AUTO_INCREMENT,
  `contactId` int(21) NOT NULL,
  `userId` int(21) NOT NULL,
  `message` text NOT NULL,
  `datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`contactId`) REFERENCES `contact`(`contactId`) ON DELETE CASCADE,
  FOREIGN KEY (`userId`) REFERENCES `users`(`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

CREATE TABLE `orderitems` (
  `id` int(21) NOT NULL AUTO_INCREMENT,
  `orderId` varchar(50) NOT NULL,
  `prodId` int(12) NOT NULL,
  `size` varchar(255) NOT NULL,
  `itemQuantity` int(100) NOT NULL,
  `price` decimal(10,2) NOT NULL, -- Assumes a maximum price of 99999999.99
  PRIMARY KEY (`id`),
  FOREIGN KEY (`orderId`) REFERENCES `orders`(`orderId`),
  FOREIGN KEY (`prodId`) REFERENCES `prod`(`prodId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

CREATE TABLE `viewcart` (
  `cartItemId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `prodId` int(11) NOT NULL,
  `size` varchar(10) NOT NULL,
  `itemQuantity` int(100) NOT NULL,
  `addedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cartItemId`),
  FOREIGN KEY (`userId`) REFERENCES `users`(`userId`),
  FOREIGN KEY (`prodId`) REFERENCES `prod`(`prodId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Insert sample data into `categories`
-- Followed by sample data for `prod`, `prod_sizes`, etc.

-- Inserting data for table `categories`
INSERT INTO `categories` (`categoryName`, `categoryDesc`) VALUES
('Milk Tea', 'Deliciously creamy milk tea varieties with a range of flavors.'),
('Frappe', 'A delightful mix of frappes, available in coffee-based and non-coffee-based options.'),
('Cold Brew', 'Smooth and rich cold brew selections for a refreshing caffeine fix.'),
('Premium Frappe', 'Our exclusive premium frappes for when you feel like indulging.'),
('Cheesecake', 'Sweet and savory cheesecake beverages, a dessert in a cup.'),
('Fruit Tea', 'Refreshing water-based fruit teas, perfect for cooling down.'),
('Hot Brew', 'Traditional hot brews to warm and wake up your senses.'),
('Milk Series', 'Specially crafted milk-based beverages offering a comforting and creamy experience.');

-- Inserting product details into `prod`
INSERT INTO `prod` (prodName, prodDesc, prodCategoryId, prodPubDate) VALUES
('Wintermelon', 'A refreshing milk tea with a sweet, caramelized flavor.', 1, CURRENT_TIMESTAMP),
('Okinawa', 'Rich and creamy milk tea with a deep, roasted brown sugar aroma.', 1, CURRENT_TIMESTAMP),
('Dark Chocolate', 'Milk tea infused with decadent dark chocolate.', 1, CURRENT_TIMESTAMP),
('Dark Caramel', 'A sweet and bold frappe with dark caramel.', 2, CURRENT_TIMESTAMP),
('Dark Mocha', 'A rich blend of coffee and dark chocolate.', 2, CURRENT_TIMESTAMP),
('Java Chip', 'Frappe with mocha and chocolate chip pieces.', 2, CURRENT_TIMESTAMP),
('Strawberries and Milk', 'Sweet and creamy with real strawberry pieces.', 8, CURRENT_TIMESTAMP),
('Mango and Milk', 'A tropical blend of ripe mango and milk.', 8, CURRENT_TIMESTAMP),
('Blueberries and Milk', 'Rich milk mixed with sweet and tart blueberries.', 8, CURRENT_TIMESTAMP),
('Iced Americano', 'A strong and invigorating cold brew for coffee purists.', 3, CURRENT_TIMESTAMP),
('Spanish Latte', 'A sweet and creamy cold brew variation with a hint of spice.', 3, CURRENT_TIMESTAMP),
('White Chocolate', 'Cold brew coffee with sweet and luxurious white chocolate.', 3, CURRENT_TIMESTAMP),
('Dark Choco Lava', 'A rich and chocolatey premium frappe with a molten core of dark chocolate.', 4, CURRENT_TIMESTAMP),
('Red Velvet Cream Cheese', 'A dessert-like premium frappe with the classic flavors of red velvet and cream cheese.', 4, CURRENT_TIMESTAMP),
('Kopi Forest', 'An intense coffee-flavored premium frappe with a forest of whipped cream.', 4, CURRENT_TIMESTAMP),
('Wintermelon Cheesecake', 'A delightful cheesecake beverage with the sweet taste of wintermelon.', 5, CURRENT_TIMESTAMP),
('Okinawa Cheesecake', 'Cheesecake blended with the deep flavors of Okinawa brown sugar.', 5, CURRENT_TIMESTAMP),
('Matcha Cheesecake', 'Green tea matcha mixed with a creamy cheesecake base.', 5, CURRENT_TIMESTAMP),
('Lychee', 'A fragrant and sweet fruit tea with lychee flavors.', 6, CURRENT_TIMESTAMP),
('Strawberry', 'Fresh and fruity strawberry tea, a delightful refresher.', 6, CURRENT_TIMESTAMP),
('Green Apple', 'Tangy and crisp green apple fruit tea for a zesty pick-me-up.', 6, CURRENT_TIMESTAMP),
('Americano', 'A classic hot brew, robust and energizing.', 7, CURRENT_TIMESTAMP),
('Spanish Latte', 'A creamy and sweet latte with a touch of spice.', 7, CURRENT_TIMESTAMP),
('White Chocolate', 'Luxurious hot white chocolate for a cozy treat.', 7, CURRENT_TIMESTAMP);

INSERT INTO `prod_sizes` (prodId, size, price) VALUES
(1, 'Medium', 28),
(1, 'Large', 38),
(2, 'Medium', 28),
(2, 'Large', 38),
(3, 'Medium', 28),
(3, 'Large', 38),
(4, 'Medium', 45),
(4, 'Large', 55),
(5, 'Medium', 45),
(5, 'Large', 55),
(6, 'Medium', 45),
(6, 'Large', 55),
(7, 'Medium', 55),
(7, 'Large', 65),
(8, 'Medium', 55),
(8, 'Large', 65),
(9, 'Medium', 55),
(9, 'Large', 65),
(10, 'Medium', 45),
(10, 'Large', 55),
(11, 'Medium', 45),
(11, 'Large', 55),
(12, 'Medium', 45),
(12, 'Large', 55),
(13, 'Standard', 88),
(14, 'Standard', 88),
(15, 'Standard', 88),
(16, 'Medium', 43),
(16, 'Large', 53),
(17, 'Medium', 43),
(17, 'Large', 53),
(18, 'Medium', 43),
(18, 'Large', 53),
(19, 'Medium', 35),
(19, 'Large', 45),
(20, 'Medium', 35),
(20, 'Large', 45),
(21, 'Medium', 35),
(21, 'Large', 45),
(22, 'Large', 45),  -- Assuming only one size available for hot brews
(23, 'Large', 45),
(24, 'Large', 45);

-- Table structure for table `sitedetail`
CREATE TABLE `sitedetail` (
  `tempId` int(11) NOT NULL AUTO_INCREMENT,
  `systemName` varchar(21) NOT NULL,
  `email` varchar(35) NOT NULL,
  `contact1` bigint(21) NOT NULL,
  `contact2` bigint(21) DEFAULT NULL COMMENT 'Optional',
  `address` text NOT NULL,
  `dateTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`tempId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table `sitedetail`
INSERT INTO `sitedetail` (`systemName`, `email`, `contact1`, `contact2`, `address`) VALUES
('1128 Tea & Café', 'contact@1128teaandcafe.com', 1234567890, NULL, '123 Tea Street, Beverage City');


-- Dumping data for table `users`
INSERT INTO `users` (nickname, firstName, lastName, email, phone, userType, password) VALUES
('adminuser', 'Admin', 'User', 'admin@gmail.com', 9876543210, '1', 'admin'),
('johndoe', 'John', 'Doe', 'john@example.com', 1234567890, '0', 'user');

-- ...Continue for all users...

COMMIT;
