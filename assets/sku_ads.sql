-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 08, 2019 at 09:04 PM
-- Server version: 5.6.40-84.0-log
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stanle14_vpd`
--

-- --------------------------------------------------------

--
-- Table structure for table `sku_ads`
--

CREATE TABLE `sku_ads` (
  `sku_ad_id` int(11) NOT NULL,
  `sku_ad_sku_id` varchar(100) NOT NULL,
  `sku_ad_link` text NOT NULL,
  `sku_ad_date` date NOT NULL,
  `sku_ad_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sku_ads`
--

INSERT INTO `sku_ads` (`sku_ad_id`, `sku_ad_sku_id`, `sku_ad_link`, `sku_ad_date`, `sku_ad_user`) VALUES
(1, '309329302', '<script type=\"text/javascript\">\r\namzn_assoc_tracking_id = \"visualpartsdb-20\";\r\namzn_assoc_ad_mode = \"manual\";\r\namzn_assoc_ad_type = \"smart\";\r\namzn_assoc_marketplace = \"amazon\";\r\namzn_assoc_region = \"US\";\r\namzn_assoc_design = \"enhanced_links\";\r\namzn_assoc_asins = \"B010HCK4N8\";\r\namzn_assoc_placement = \"adunit\";\r\namzn_assoc_linkid = \"a68c13f0ff8f9b792103287da1e91856\";\r\n</script>\r\n<script src=\"//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US\"></script>', '2019-04-08', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sku_ads`
--
ALTER TABLE `sku_ads`
  ADD PRIMARY KEY (`sku_ad_id`),
  ADD KEY `sku_ad_sku_ids` (`sku_ad_sku_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sku_ads`
--
ALTER TABLE `sku_ads`
  MODIFY `sku_ad_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `sku_ads`
--
ALTER TABLE `sku_ads`
  ADD CONSTRAINT `sku_ad_sku_ids` FOREIGN KEY (`sku_ad_sku_id`) REFERENCES `sku` (`sku_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
