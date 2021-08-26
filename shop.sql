-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2019 at 03:12 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.5.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(4) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `parent` int(11) NOT NULL,
  `Ordering` int(11) DEFAULT NULL,
  `Visiblity` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Ads` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `parent`, `Ordering`, `Visiblity`, `Allow_Comment`, `Allow_Ads`) VALUES
(9, 'Hand Made', '\\Hand made items', 0, 1, 1, 1, 1),
(10, 'Computers', 'all computers needs', 0, 2, 0, 0, 0),
(11, 'cell phones', 'mobile phones', 0, 3, 0, 0, 0),
(12, 'Clothing', 'Clothing and fashion', 0, 4, 0, 0, 0),
(13, 'Tools', 'All tolls ', 0, 5, 0, 0, 0),
(21, 'nokia', 'nokia cat for mobiles', 11, 6, 0, 0, 0),
(22, 'Accessories', 'this table for new accessories', 9, 0, 0, 0, 0),
(23, 'Boxers ', 'this for underwears clothes', 12, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `Cid` int(11) NOT NULL,
  `comment` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `comment_date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`Cid`, `comment`, `status`, `comment_date`, `item_id`, `user_id`) VALUES
(1, 'it will work', 1, '2018-09-05', 14, 6),
(2, 'this is fucking phoe', 1, '2018-10-27', 16, 6),
(3, 'this is fucking phoe', 1, '2018-10-27', 16, 6),
(4, 'this is fucking phoe', 1, '2018-10-27', 16, 6),
(5, 'hello from un visible comment ', 1, '2018-10-27', 16, 6),
(6, 'this Comment after i add the comments at page which will display the user name and it''s comment', 1, '2018-10-27', 16, 6),
(7, 'niceee game', 1, '2019-01-15', 20, 7);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `Item_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` varchar(255) NOT NULL,
  `Add_Date` date NOT NULL,
  `Country_Made` varchar(255) NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `Rating` smallint(6) NOT NULL,
  `Approve` tinyint(4) NOT NULL DEFAULT '0',
  `Cat_ID` int(11) NOT NULL,
  `Member_id` int(11) NOT NULL,
  `tags` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`Item_ID`, `Name`, `Description`, `Price`, `Add_Date`, `Country_Made`, `Image`, `Status`, `Rating`, `Approve`, `Cat_ID`, `Member_id`, `tags`) VALUES
(14, 'Speaker', 'Very good speaker', '10$', '2018-09-16', 'egy', '', '2', 0, 1, 10, 6, ''),
(15, 'Microphone', 'new one', '20$', '2018-09-16', 'Egypt', '', '1', 0, 1, 10, 2, ''),
(16, 'IPHONE 6PLUS', 'new phone apple', '200', '2018-09-16', 'USa', '', '1', 0, 1, 11, 6, ''),
(17, 'dog', 'bitbool dog for sale', '100', '2018-09-19', 'egy', '', '2', 0, 1, 10, 3, ''),
(18, 'glass', 'this glass or ttea or coffe', '5', '2018-12-23', 'dar Elsalam', '', '1', 0, 1, 13, 6, ''),
(19, 'Bocket Knief', 'to defend on ur self', '15', '2018-12-25', 'German', '', '3', 0, 1, 13, 3, 'discount , knief , ayad'),
(20, 'Wooden Game', 'A good wooden game ', '100', '2018-12-25', 'egypt', '', '1', 0, 1, 9, 6, 'wood , game , ayad'),
(21, 'pupg', 'this is new game ', '200', '2019-01-01', 'USA', '', '1', 0, 1, 11, 6, 'RBG , online , games'),
(22, 'Playstation', 'this is new game', '50', '2019-01-01', 'aaaa', '', '2', 0, 1, 10, 6, 'discount , games , rbg'),
(23, 'Hazazaa', 'this is new accessory type', '8', '2019-01-15', 'manual', '', '1', 0, 1, 22, 7, 'games , manual');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL COMMENT 'to ibentfy user',
  `UserName` varchar(255) NOT NULL COMMENT 'username to login',
  `password` varchar(255) NOT NULL COMMENT 'Password to login',
  `Email` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `FullName` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `GroupID` int(11) NOT NULL DEFAULT '0' COMMENT 'identify user groub',
  `TrustStatues` int(11) NOT NULL DEFAULT '0' COMMENT 'Seller Rank',
  `RegStatues` int(11) NOT NULL DEFAULT '0' COMMENT 'user approval',
  `AddDate` date NOT NULL,
  `avatar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `UserName`, `password`, `Email`, `FullName`, `GroupID`, `TrustStatues`, `RegStatues`, `AddDate`, `avatar`) VALUES
(1, 'ayad', '601f1889667efaebb33b8c12572835da3f027f78', 'ayad@123.com', 'Mohamed Taha', 1, 0, 1, '2017-08-16', ''),
(2, 'Adel', '601f1889667efaebb33b8c12572835da3f027f78', 'aaa@gmail.com', 'adel 5raa', 0, 0, 1, '2018-09-13', ''),
(3, 'sameh', '601f1889667efaebb33b8c12572835da3f027f78', 'sa@a.com', 'sameh ahmed', 0, 0, 1, '2018-09-13', ''),
(4, 'shaher', '601f1889667efaebb33b8c12572835da3f027f78', 'aa@sh.com', 'shaher elsaka', 0, 0, 1, '2018-09-14', ''),
(5, 'aliii', '601f1889667efaebb33b8c12572835da3f027f78', 'ali@a.com', 'ali ali', 0, 0, 0, '2018-09-14', ''),
(6, 'used', '601f1889667efaebb33b8c12572835da3f027f78', 'aa@gmail.com', 'App app', 0, 0, 1, '2018-09-19', ''),
(7, 'Nody', '601f1889667efaebb33b8c12572835da3f027f78', 'nody@n.com', 'Nody Ahmed', 0, 0, 1, '2019-01-15', '172699_46387229_331145124145672_5188751579435499520_n.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`Cid`),
  ADD KEY `ITEMS_COMMENT` (`item_id`),
  ADD KEY `comment_user` (`user_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`Item_ID`),
  ADD KEY `member_1` (`Member_id`),
  ADD KEY `cat_1` (`Cat_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `UserName` (`UserName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `Cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'to ibentfy user', AUTO_INCREMENT=8;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `ITEMS_COMMENT` FOREIGN KEY (`item_id`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `cat_1` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `member_1` FOREIGN KEY (`Member_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
