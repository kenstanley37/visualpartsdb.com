-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2019 at 07:25 AM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.0

SET FOREIGN_KEY_CHECKS=0;
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

DROP TABLE IF EXISTS `sku_ads`;
CREATE TABLE `sku_ads` (
  `sku_ad_id` int(11) NOT NULL,
  `sku_ad_sku_id` varchar(100) NOT NULL,
  `sku_ad_banner` text NOT NULL,
  `sku_ad_other` text NOT NULL,
  `sku_ad_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sku_ad_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `sku_ads`
--

TRUNCATE TABLE `sku_ads`;
--
-- Dumping data for table `sku_ads`
--

INSERT INTO `sku_ads` (`sku_ad_id`, `sku_ad_sku_id`, `sku_ad_banner`, `sku_ad_other`, `sku_ad_date`, `sku_ad_user`) VALUES
(1, '309329302', '<script type=\"text/javascript\">\r\namzn_assoc_tracking_id = \"visualpartsdb-20\";\r\namzn_assoc_ad_mode = \"manual\";\r\namzn_assoc_ad_type = \"smart\";\r\namzn_assoc_marketplace = \"amazon\";\r\namzn_assoc_region = \"US\";\r\namzn_assoc_design = \"enhanced_links\";\r\namzn_assoc_asins = \"B010HCK4N8\";\r\namzn_assoc_placement = \"adunit\";\r\namzn_assoc_linkid = \"a68c13f0ff8f9b792103287da1e91856\";\r\n</script>\r\n<script src=\"//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US\"></script>', '', '2019-04-08 00:00:00', 3),
(3, '5304461970', '<script type=\"text/javascript\">\r\namzn_assoc_tracking_id = \"visualpartsdb-20\";\r\namzn_assoc_ad_mode = \"manual\";\r\namzn_assoc_ad_type = \"smart\";\r\namzn_assoc_marketplace = \"amazon\";\r\namzn_assoc_region = \"US\";\r\namzn_assoc_design = \"enhanced_links\";\r\namzn_assoc_asins = \"B00D8M9TV8\";\r\namzn_assoc_placement = \"adunit\";\r\namzn_assoc_linkid = \"04e6009cca4d7347a518a4beda753b65\";\r\n</script>\r\n<script src=\"//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US\"></script>', '', '2019-04-09 00:00:00', 3),
(4, '318122721', '<script type=\"text/javascript\">\r\namzn_assoc_tracking_id = \"visualpartsdb-20\";\r\namzn_assoc_ad_mode = \"manual\";\r\namzn_assoc_ad_type = \"smart\";\r\namzn_assoc_marketplace = \"amazon\";\r\namzn_assoc_region = \"US\";\r\namzn_assoc_design = \"enhanced_links\";\r\namzn_assoc_asins = \"B00EDDNAMU\";\r\namzn_assoc_placement = \"adunit\";\r\namzn_assoc_linkid = \"f017ef2af62e22364b90d1aad3eb7fc7\";\r\n</script>\r\n<script src=\"//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US\"></script>', '', '2019-04-09 00:00:00', 3),
(5, '316540702', '<script type=\"text/javascript\">\r\namzn_assoc_tracking_id = \"visualpartsdb-20\";\r\namzn_assoc_ad_mode = \"manual\";\r\namzn_assoc_ad_type = \"smart\";\r\namzn_assoc_marketplace = \"amazon\";\r\namzn_assoc_region = \"US\";\r\namzn_assoc_design = \"enhanced_links\";\r\namzn_assoc_asins = \"B01LF2PNVM\";\r\namzn_assoc_placement = \"adunit\";\r\namzn_assoc_linkid = \"10b112afda7055835ba09c3205c98941\";\r\n</script>\r\n<script src=\"//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US\"></script>', '', '2019-04-09 00:00:00', 3),
(6, 'WF2CB', '<script type=\"text/javascript\">\r\namzn_assoc_tracking_id = \"visualpartsdb-20\";\r\namzn_assoc_ad_mode = \"manual\";\r\namzn_assoc_ad_type = \"smart\";\r\namzn_assoc_marketplace = \"amazon\";\r\namzn_assoc_region = \"US\";\r\namzn_assoc_design = \"enhanced_links\";\r\namzn_assoc_asins = \"B01N26I0QJ\";\r\namzn_assoc_placement = \"adunit\";\r\namzn_assoc_linkid = \"97e780904f70d8113a2c79d482c4333c\";\r\n</script>\r\n<script src=\"//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US\"></script>', '', '2019-04-09 00:00:00', 3),
(7, '9911', '<script type=\"text/javascript\">\r\namzn_assoc_tracking_id = \"visualpartsdb-20\";\r\namzn_assoc_ad_mode = \"manual\";\r\namzn_assoc_ad_type = \"smart\";\r\namzn_assoc_marketplace = \"amazon\";\r\namzn_assoc_region = \"US\";\r\namzn_assoc_design = \"enhanced_links\";\r\namzn_assoc_asins = \"B01DO3S1AU\";\r\namzn_assoc_placement = \"adunit\";\r\namzn_assoc_linkid = \"858e3305a728e1237bdb4a9844dc1dc0\";\r\n</script>\r\n<script src=\"//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US\"></script>', '', '2019-04-09 00:32:56', 3),
(8, 'ULTRAWF', '<script type=\"text/javascript\">\r\namzn_assoc_tracking_id = \"visualpartsdb-20\";\r\namzn_assoc_ad_mode = \"manual\";\r\namzn_assoc_ad_type = \"smart\";\r\namzn_assoc_marketplace = \"amazon\";\r\namzn_assoc_region = \"US\";\r\namzn_assoc_design = \"enhanced_links\";\r\namzn_assoc_asins = \"B002JAKRAM\";\r\namzn_assoc_placement = \"adunit\";\r\namzn_assoc_linkid = \"5e498575d7654de35a69ec0058661603\";\r\n</script>\r\n<script src=\"//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US\"></script>', '', '2019-04-09 00:33:46', 3),
(9, 'WF3CB', '<script type=\"text/javascript\">\r\namzn_assoc_tracking_id = \"visualpartsdb-20\";\r\namzn_assoc_ad_mode = \"manual\";\r\namzn_assoc_ad_type = \"smart\";\r\namzn_assoc_marketplace = \"amazon\";\r\namzn_assoc_region = \"US\";\r\namzn_assoc_design = \"enhanced_links\";\r\namzn_assoc_asins = \"B07JHTXFBL\";\r\namzn_assoc_placement = \"adunit\";\r\namzn_assoc_linkid = \"1a726d66a88fede25b8709c999a51284\";\r\n</script>\r\n<script src=\"//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US\"></script>', '', '2019-04-09 00:34:31', 3),
(10, 'PAULTRA', '<script type=\"text/javascript\">\r\namzn_assoc_tracking_id = \"visualpartsdb-20\";\r\namzn_assoc_ad_mode = \"manual\";\r\namzn_assoc_ad_type = \"smart\";\r\namzn_assoc_marketplace = \"amazon\";\r\namzn_assoc_region = \"US\";\r\namzn_assoc_design = \"enhanced_links\";\r\namzn_assoc_asins = \"B00FKFJYUW\";\r\namzn_assoc_placement = \"adunit\";\r\namzn_assoc_linkid = \"222032f6b926f23ec452b31a41731630\";\r\n</script>\r\n<script src=\"//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US\"></script>', '', '2019-04-09 00:35:47', 3),
(11, '5304488411', '<script type=\"text/javascript\">\r\namzn_assoc_tracking_id = \"visualpartsdb-20\";\r\namzn_assoc_ad_mode = \"manual\";\r\namzn_assoc_ad_type = \"smart\";\r\namzn_assoc_marketplace = \"amazon\";\r\namzn_assoc_region = \"US\";\r\namzn_assoc_design = \"enhanced_links\";\r\namzn_assoc_asins = \"B00J7DO0E8\";\r\namzn_assoc_placement = \"adunit\";\r\namzn_assoc_linkid = \"013af070233e2454b1ee668eb856e75c\";\r\n</script>\r\n<script src=\"//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US\"></script>', '', '2019-04-09 00:38:59', 3),
(12, '5304472196', '<script type=\"text/javascript\">\r\namzn_assoc_tracking_id = \"visualpartsdb-20\";\r\namzn_assoc_ad_mode = \"manual\";\r\namzn_assoc_ad_type = \"smart\";\r\namzn_assoc_marketplace = \"amazon\";\r\namzn_assoc_region = \"US\";\r\namzn_assoc_design = \"enhanced_links\";\r\namzn_assoc_asins = \"B01KRNFH9I\";\r\namzn_assoc_placement = \"adunit\";\r\namzn_assoc_linkid = \"e9137310143946ad101624cac5146859\";\r\n</script>\r\n<script src=\"//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US\"></script>', '', '2019-04-09 00:40:21', 3),
(13, '5304457617', '<script type=\"text/javascript\">\r\namzn_assoc_tracking_id = \"visualpartsdb-20\";\r\namzn_assoc_ad_mode = \"manual\";\r\namzn_assoc_ad_type = \"smart\";\r\namzn_assoc_marketplace = \"amazon\";\r\namzn_assoc_region = \"US\";\r\namzn_assoc_design = \"enhanced_links\";\r\namzn_assoc_asins = \"B00PU04K56\";\r\namzn_assoc_placement = \"adunit\";\r\namzn_assoc_linkid = \"499aecfb56403065240c5a9e80210c63\";\r\n</script>\r\n<script src=\"//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US\"></script>', '<script type=\"text/javascript\">\r\namzn_assoc_tracking_id = \"visualpartsdb-20\";\r\namzn_assoc_ad_mode = \"manual\";\r\namzn_assoc_ad_type = \"smart\";\r\namzn_assoc_marketplace = \"amazon\";\r\namzn_assoc_region = \"US\";\r\namzn_assoc_design = \"enhanced_links\";\r\namzn_assoc_asins = \"B01AV20POW\";\r\namzn_assoc_placement = \"adunit\";\r\namzn_assoc_linkid = \"ec299bd23d25bbaea0584a782c6c5640\";\r\n</script>\r\n<script src=\"//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US\"></script>', '2019-04-09 00:47:07', 3);

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
  MODIFY `sku_ad_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sku_ads`
--
ALTER TABLE `sku_ads`
  ADD CONSTRAINT `sku_ad_sku_ids` FOREIGN KEY (`sku_ad_sku_id`) REFERENCES `sku` (`sku_id`);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
