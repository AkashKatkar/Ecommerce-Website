-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2021 at 08:59 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce_website`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(5) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `code` varchar(20) NOT NULL,
  `parent_id` int(5) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category_name`, `code`, `parent_id`, `status`) VALUES
(1, 'Electronic', 'ELE', NULL, 'active'),
(2, 'Stationary', 'ST', NULL, 'active'),
(3, 'Mobile', 'MOB', 1, 'active'),
(4, 'Laptop', 'LAPTOP', 1, 'active'),
(5, 'Notebook', 'NTB', 2, 'active'),
(6, 'Watch', 'WTC', 1, 'active'),
(7, 'PEN', 'PEN', 2, 'active'),
(8, 'Jewellery', 'JWL', NULL, 'active'),
(9, 'Ring', 'RING', 8, 'active'),
(10, 'Car', 'CAR', NULL, 'active'),
(11, 'Tyres', 'TYRE', 10, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(10) NOT NULL,
  `prod_name` varchar(200) NOT NULL,
  `code` varchar(20) NOT NULL,
  `price` varchar(20) NOT NULL,
  `product_image` varchar(400) NOT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `prod_name`, `code`, `price`, `product_image`, `status`) VALUES
(2, 'Dell', 'LAPTOP', 'â‚¹50.5K', 'images/dell_laptop.jpg', 'active'),
(3, 'Fountain pen', 'PEN', 'â‚¹199', 'images/fountain_pen.jpg', 'active'),
(4, 'Gel Pen', 'PEN', 'â‚¹100', 'images/gel_pen.jpg', 'active'),
(5, 'Long Book', 'NTB', 'â‚¹45', 'images/long_book.jpg', 'active'),
(6, 'OnePlus', 'MOB', 'â‚¹29K', 'images/one_plus.jpg', 'active'),
(7, 'OPPO', 'MOB', 'â‚¹1.499K', 'images/oppo.png', 'active'),
(8, 'Realme', 'MOB', 'â‚¹1299', 'images/realme.png', 'active'),
(9, 'Samsung', 'MOB', 'â‚¹1599', 'images/samsung.jpg', 'active'),
(10, 'Timex', 'WTC', 'â‚¹5.5K', 'images/timex.jpg', 'active'),
(11, 'Attract Round Ring, White, Rose-Gold tone plated', 'RING', 'â‚¹159', 'images/White Round Ring.jpg', 'active'),
(12, 'Titan', 'WTC', 'â‚¹2.5K', 'images/titan.jpg', 'active'),
(13, 'JK Tyre Neo 155/80 R13 Tubeless Car Tyre', 'TYRE', 'â‚¹2580', 'images/civic-exterior-right-front-three-quarter.png', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
