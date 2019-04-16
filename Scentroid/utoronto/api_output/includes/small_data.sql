-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 16, 2019 at 02:59 PM
-- Server version: 5.7.23-0ubuntu0.18.04.1
-- PHP Version: 7.2.10-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `utoronto`
--

-- --------------------------------------------------------

--
-- Table structure for table `small_data`
--

CREATE TABLE `small_data` (
  `id` int(10) UNSIGNED NOT NULL,
  `lat` float(10,6) DEFAULT NULL,
  `lon` float(10,6) DEFAULT NULL,
  `wind_speed` float NOT NULL,
  `wind_direction` float NOT NULL,
  `vehicle_speed` float NOT NULL,
  `vehicle_direction` float NOT NULL,
  `value_co_mv` float(8,3) NOT NULL,
  `value_co_ppm` float(8,3) NOT NULL,
  `value_no2_mv` float(8,3) NOT NULL,
  `value_no2_ppm` float(8,3) NOT NULL,
  `value_o3_mv` float(8,3) NOT NULL,
  `value_o3_ppm` float(8,3) NOT NULL,
  `value_pm25_mv` float(8,3) NOT NULL,
  `value_pm25_ugm3` float(8,3) NOT NULL,
  `value_pm10_mv` float(8,3) NOT NULL,
  `value_pm10_ugm3` float(8,3) NOT NULL,
  `timestamp` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `small_data`
--

INSERT INTO `small_data` (`id`, `lat`, `lon`, `wind_speed`, `wind_direction`, `vehicle_speed`, `vehicle_direction`, `value_co_mv`, `value_co_ppm`, `value_no2_mv`, `value_no2_ppm`, `value_o3_mv`, `value_o3_ppm`, `value_pm25_mv`, `value_pm25_ugm3`, `value_pm10_mv`, `value_pm10_ugm3`, `timestamp`) VALUES
(3, 1.307888, 103.717079, 35, 300, 52, 179, 112.400, 23.000, 245.000, 11.900, 339.000, 4.500, 123.000, 8.900, 234.000, 12.400, '1571254563'),
(4, -10.727909, 25.384945, 11, 272, 63, 359, 56.000, 4.450, 144.000, 9.700, 342.000, 26.100, 79.000, 6.300, 234.000, 15.800, '1578546345');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `small_data`
--
ALTER TABLE `small_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `small_data`
--
ALTER TABLE `small_data`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
