-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2025 at 05:58 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rgmc`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categorycode` varchar(4) NOT NULL,
  `catdescription` varchar(25) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`categorycode`, `catdescription`, `id`) VALUES
('0001', 'Air Fittings', 1),
('0002', 'Band Heaters', 2),
('0003', 'Bearings', 3),
('0004', 'Belts', 4),
('0005', 'Bolts & Screws', 5),
('0006', 'Cartridge Heaters', 6),
('0007', 'Consumables', 7),
('0008', 'Control Valves', 8),
('0009', 'Cutter', 9),
('0010', 'Electrical', 10),
('0011', 'Extruder', 11),
('0012', 'Fabrication', 12),
('0013', 'Fans/Blowers', 13),
('0014', 'Filament', 14),
('0015', 'Forming', 15),
('0016', 'Granulator', 16),
('0017', 'Heaters', 17),
('0018', 'Hydraulics', 18),
('0019', 'Induction Motors', 19),
('0020', 'Others', 20),
('0021', 'PBJ', 21),
('0022', 'Plumbing', 22),
('0023', 'Pneumatics', 23),
('0024', 'Printer', 24),
('0025', 'Seals and O-Rings', 25),
('0026', 'Sensors', 26),
('0027', 'Straw', 27),
('0028', 'TOOLS', 28);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
