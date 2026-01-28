-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 29, 2026 at 06:21 AM
-- Server version: 5.7.44
-- PHP Version: 8.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vnvupzif_etusul`
--

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `comID` int(11) NOT NULL,
  `RD` int(7) NOT NULL,
  `comName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `comDir` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `comAbout` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `job` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `facebook` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `ognoo` datetime NOT NULL,
  `propic` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `domainName` varchar(1000) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`comID`, `RD`, `comName`, `comDir`, `comAbout`, `job`, `country`, `address`, `phone`, `email`, `facebook`, `password`, `ognoo`, `propic`, `status`, `domainName`) VALUES
(1, 3553523, 'Девсофт ХХК', 'Захирал', 'Девсофт ХХК нь 2009 оны 10-р сарын 31нд байгуулагдсан. Өнөөг хүртэл барилга, мэдээллийн технологийн салбарт тасралтгүй үйл ажиллагаагаа өргөжүүлэн ажилласаар байна.', 'Захирал', 'Монгол Улс', 'Улаанбаатар хот, Хан-уул дүүрэг, 15-р хороо Ривер Вилла хотхон, 8/2-3-27 тоот', '99991246', 'devsoft.xxk@gmail.com', '88021246', 'asasas', '2022-01-28 01:20:28', '1.jpg', 'ok', 'https://devsoft.etusul.com/'),
(2, 3566668, 'Фөүчир Инновэшн ХХК', 'Ж.Бат-Ирээдүй', 'Фөүчир Инновэйшн ХХК нь 2020 оны 1-р сарын 31 өдөр байгуулагдсан. Програм хангамж чиглэлээр үйл ажиллагаа явуулж байна', 'Гүйцэтгэх захирал', 'Монгол', 'Монгол Улс Өвөрхангай Арвайхээр 5-р баг', '88992842', 'j.batireedui@gmail.com', '', 'as', '2022-01-28 01:20:28', '2.jpg', 'ok', 'https://futureinnovation.etusul.com/'),
(20, 3756254, 'Чухаг Арвис ХХК', 'Ө.Мөнхбат', '', 'захирал', 'Монгол', 'Улаанбаатар ХУД 15-р хороо 8/2-27', '88887321', 'chuhagarvis@gmail.com', '', 'as', '2025-12-22 11:54:24', 'logo.png', 'test', 'https://chuhagarvis.etusul.com/');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`comID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `comID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
