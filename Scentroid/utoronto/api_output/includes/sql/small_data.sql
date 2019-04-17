-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 17, 2019 at 01:12 PM
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
  `value1_co_mv` float(8,3) NOT NULL,
  `value1_co_ppm` float(8,3) NOT NULL,
  `value1_no2_mv` float(8,3) NOT NULL,
  `value1_no2_ppm` float(8,3) NOT NULL,
  `value1_o3_mv` float(8,3) NOT NULL,
  `value1_o3_ppm` float(8,3) NOT NULL,
  `value1_pm1_ugm3` float(8,3) NOT NULL,
  `value1_pm25_ugm3` float(8,3) NOT NULL,
  `value1_pm4_ugm3` float(8,3) NOT NULL,
  `value1_pm10_ugm3` float(8,3) NOT NULL,
  `value1_temp` float(8,3) NOT NULL,
  `value1_humid` float(8,3) NOT NULL,
  `value1_aqi` float(4,1) DEFAULT NULL,
  `value2_co_mv` float(8,3) NOT NULL,
  `value2_co_ppm` float(8,3) NOT NULL,
  `value2_no2_mv` float(8,3) NOT NULL,
  `value2_no2_ppm` float(8,3) NOT NULL,
  `value2_o3_mv` float(8,3) NOT NULL,
  `value2_o3_ppm` float(8,3) NOT NULL,
  `value2_pm1_ugm3` float(8,3) NOT NULL,
  `value2_pm25_ugm3` float(8,3) NOT NULL,
  `value2_pm4_ugm3` float(8,3) NOT NULL,
  `value2_pm10_ugm3` float(8,3) NOT NULL,
  `value2_temp` float(8,3) NOT NULL,
  `value2_humid` float(8,3) NOT NULL,
  `value2_aqi` float(4,1) DEFAULT NULL,
  `timestamp` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `small_data`
--

INSERT INTO `small_data` (`id`, `lat`, `lon`, `wind_speed`, `wind_direction`, `vehicle_speed`, `vehicle_direction`, `value1_co_mv`, `value1_co_ppm`, `value1_no2_mv`, `value1_no2_ppm`, `value1_o3_mv`, `value1_o3_ppm`, `value1_pm1_ugm3`, `value1_pm25_ugm3`, `value1_pm4_ugm3`, `value1_pm10_ugm3`, `value1_temp`, `value1_humid`, `value1_aqi`, `value2_co_mv`, `value2_co_ppm`, `value2_no2_mv`, `value2_no2_ppm`, `value2_o3_mv`, `value2_o3_ppm`, `value2_pm1_ugm3`, `value2_pm25_ugm3`, `value2_pm4_ugm3`, `value2_pm10_ugm3`, `value2_temp`, `value2_humid`, `value2_aqi`, `timestamp`) VALUES
(3, 1.307888, 103.717079, 35, 300, 52, 179, 112.400, 23.000, 245.000, 11.900, 339.000, 4.500, 123.000, 8.900, 234.000, 12.400, 25.000, 0.610, NULL, 142.000, 41.000, 351.000, 18.000, 222.000, 14.500, 211.000, 15.400, 152.000, 45.000, 24.500, 0.600, NULL, '1571254563'),
(4, -10.727909, 25.384945, 11, 272, 63, 359, 56.000, 4.450, 144.000, 9.700, 342.000, 26.100, 79.000, 6.300, 234.000, 15.800, 26.000, 0.570, NULL, 236.000, 75.300, 173.000, 9.300, 13.000, 2.000, 321.000, 18.600, 321.000, 74.500, 27.100, 0.550, NULL, '1578546345');

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
