-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 04, 2024 at 07:29 AM
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
-- Database: `sales_pipeline2`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `type` enum('KSG','Non-KSG') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `customer_name`, `customer_email`, `customer_phone`, `customer_address`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Polres Cilegon', 'polres@gmail.com', '08567353234', 'adadadsdasdadadsvdvd', 'Non-KSG', '2024-09-13 00:52:56', '2024-09-25 05:35:06'),
(3, 'KOMINFO KAB. SERANG', 'kominfoserang@gmail.com', '9780789634', 'okeajaudakfkafawf', 'Non-KSG', '2024-09-13 00:57:58', '2024-09-25 05:35:06'),
(4, 'Klinik Pratama', 'klinikpratama@gmail.com', '089723542674', 'akhknfajfajfawfafvvsdv', 'Non-KSG', '2024-09-13 01:21:11', '2024-09-25 05:35:06'),
(5, 'RS Banjarnegara', 'rsbanjarnegara@gmail.com', '973563335', 'kjgksabfakfgabka', 'Non-KSG', '2024-09-13 01:26:38', '2024-09-25 05:35:06'),
(6, 'CIMB Niaga', 'cimb@gmail.com', '867127141', 'adkagdjadajhwdad', 'Non-KSG', '2024-09-13 01:28:43', '2024-09-25 05:35:06'),
(7, 'KLINIK SETNEG', 'kliniksetneg@gmail.com', '7234235252', 'kjbbjkabkjakbwdkbadwjbk', 'Non-KSG', '2024-09-13 01:29:50', '2024-09-25 05:35:06'),
(8, 'PT Krakatau Steel (Persero) Tbk', 'krakataustell@gmail.com', '08864753234', 'Jl. akdadajdavfuahafiafaf', 'KSG', '2024-09-13 02:26:57', '2024-09-13 02:26:57'),
(9, 'PT Krakatau Chandra Energi', 'krakatauchandraenergi@gmail.com', '923131312525', 'Jl. Krakatau afkafgbafgafafa', 'KSG', '2024-09-13 02:30:03', '2024-09-13 02:30:03'),
(10, 'RS Sari Ningsih', 'rssariningsih@gmail.com', '078564535', 'Jl. dsadakdbwfdwfwf', 'Non-KSG', '2024-09-16 17:54:09', '2024-09-25 05:35:06'),
(11, 'POSCO', 'posco@gmail.com', '8656267', 'jl.posco', 'KSG', '2024-09-25 04:20:10', '2024-09-25 04:20:10'),
(12, 'Kimia Farma', 'kimiafarma@gmail.com', '867762347623', 'jl.kimiafarma', 'Non-KSG', '2024-09-25 04:25:03', '2024-09-25 04:25:03'),
(13, 'KS', 'KA@gmail.com', '12321313', '12441', 'KSG', '2024-09-26 20:39:09', '2024-09-26 20:39:09'),
(14, 'PT Krakatau Baja Industri', 'kbi@gmail.com', '234745', 'jl. kbi', 'KSG', '2024-09-30 21:18:06', '2024-09-30 21:18:06');

-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

CREATE TABLE `divisions` (
  `division_id` int(11) NOT NULL,
  `division_name` varchar(255) NOT NULL,
  `division_leader` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `divisions`
--

INSERT INTO `divisions` (`division_id`, `division_name`, `division_leader`) VALUES
(1, 'DIGITAL TRANSFORMATION', 'Sugiono'),
(2, 'INFRASTRUKTUR', 'Asep Susanto'),
(3, 'SAP', 'Jojon');

-- --------------------------------------------------------

--
-- Table structure for table `financial_details`
--

CREATE TABLE `financial_details` (
  `financial_id` int(11) NOT NULL,
  `prospect_id` int(11) DEFAULT NULL,
  `hpp` decimal(15,2) DEFAULT NULL,
  `plan_budget_sales` decimal(15,2) DEFAULT NULL,
  `margin` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `financial_details`
--

INSERT INTO `financial_details` (`financial_id`, `prospect_id`, `hpp`, `plan_budget_sales`, `margin`, `created_at`, `updated_at`) VALUES
(21, 48, 234.00, 234.00, 23.00, '2024-09-17 22:13:26', '2024-09-26 20:57:37'),
(22, 49, 650000.00, 20000.00, 330000.00, '2024-09-20 05:19:52', '2024-09-20 05:23:46'),
(23, 50, 13634480.00, 419522.00, 6922120.00, '2024-09-20 08:43:16', '2024-09-21 23:32:21'),
(24, 51, 13650000.00, 420000.00, 6930000.00, '2024-09-25 14:05:32', '2024-09-25 14:05:32'),
(25, 52, 36400000.00, 1120000.00, 18480000.00, '2024-09-26 06:24:48', '2024-09-30 21:00:46'),
(26, 53, 47450000.00, 1460000.00, 24090000.00, '2024-09-26 06:34:37', '2024-09-26 20:05:10'),
(27, 54, 35750000.00, 1100000.00, 18150000.00, '2024-09-26 19:58:48', '2024-09-26 20:12:47'),
(28, 55, 22230000.00, 684000.00, 11286000.00, '2024-09-26 20:16:14', '2024-09-26 20:17:49'),
(29, 56, 13650000.00, 420000.00, 6930000.00, '2024-09-26 20:19:47', '2024-09-26 20:21:50'),
(30, 57, 300000000.00, 20000000.00, 165000000.00, '2024-09-26 20:40:32', '2024-09-26 20:48:56'),
(31, 58, 11050000.00, 340000.00, 5610000.00, '2024-09-29 10:01:39', '2024-09-29 10:01:39'),
(32, 59, 7800000.00, 240000.00, 3960000.00, '2024-09-29 10:11:01', '2024-09-29 10:11:01'),
(33, 60, 7800000.00, 240000.00, 3960000.00, '2024-09-29 20:04:20', '2024-09-30 21:04:36'),
(34, 61, 195000000.00, 6000000.00, 99000000.00, '2024-09-30 21:16:12', '2024-09-30 21:16:12'),
(35, 62, 1950000.00, 60000.00, 990000.00, '2024-09-30 21:17:03', '2024-09-30 21:17:03'),
(37, 64, 194350000.00, 5980000.00, 98670000.00, '2024-09-30 21:19:54', '2024-09-30 21:28:11'),
(40, 67, 195000000.00, 6000000.00, 99000000.00, '2024-09-30 21:33:35', '2024-09-30 21:33:35'),
(42, 69, 0.02, 0.00, 0.01, '2024-09-30 21:37:07', '2024-09-30 21:37:07'),
(43, 70, 325000000.00, 10000000.00, 165000000.00, '2024-09-30 21:38:16', '2024-09-30 21:38:16'),
(44, 71, 260000000.00, 8000000.00, 132000000.00, '2024-09-30 21:42:31', '2024-09-30 21:42:31'),
(45, 72, 49400000.00, 1520000.00, 25080000.00, '2024-10-02 21:29:25', '2024-10-03 20:47:13'),
(46, 73, 22750000.00, 700000.00, 11550000.00, '2024-10-02 22:50:08', '2024-10-03 20:47:54');

-- --------------------------------------------------------

--
-- Table structure for table `milestones_prospect`
--

CREATE TABLE `milestones_prospect` (
  `milestone_id` int(11) NOT NULL,
  `prospect_id` int(11) NOT NULL,
  `milestone_name` enum('Scooping','Presentasi','PreSales Sourcing','Draft Proposal','Sent Proposal','Sent Komersial','Clarification','Tender dan Nego','Contract Draft','Contract Signed') NOT NULL,
  `milestone_date` date DEFAULT NULL,
  `milestone_status` enum('Pending','Completed','In Progress') DEFAULT 'Pending',
  `progress_percentage` int(3) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `milestone_document` varchar(255) DEFAULT NULL,
  `milestone_index` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `milestones_prospect`
--

INSERT INTO `milestones_prospect` (`milestone_id`, `prospect_id`, `milestone_name`, `milestone_date`, `milestone_status`, `progress_percentage`, `notes`, `milestone_document`, `milestone_index`) VALUES
(100, 49, 'Scooping', '2024-09-20', 'Completed', 100, 'tess', NULL, 1),
(101, 49, 'Presentasi', '2024-09-20', 'Completed', 100, 'adad', NULL, 2),
(102, 49, 'PreSales Sourcing', '2024-09-20', 'Completed', 100, 'mantap', NULL, 3),
(103, 49, 'Draft Proposal', '2024-09-20', 'Completed', 100, '', NULL, 4),
(104, 49, 'Sent Proposal', '2024-09-20', 'Completed', 100, '', NULL, 5),
(105, 46, 'Scooping', '2024-09-20', 'Completed', 100, '', NULL, 1),
(107, 46, 'Presentasi', '2024-09-20', 'Completed', 100, '', NULL, 2),
(108, 46, 'PreSales Sourcing', '2024-09-20', 'Completed', 100, '', NULL, 3),
(109, 46, 'Draft Proposal', '2024-09-20', 'Completed', 100, '', NULL, 4),
(110, 46, 'Sent Proposal', '2024-09-20', 'Completed', 100, '', NULL, 5),
(111, 50, 'Scooping', '2024-09-20', 'Completed', 100, '', 'uploads/documents/1726854104_acea63c11ff7a5c74b41.docx', 1),
(112, 49, 'Sent Komersial', '2024-09-20', 'Completed', 100, 'adad', NULL, 6),
(113, 50, 'Presentasi', '2024-09-20', 'Completed', 100, '', NULL, 2),
(114, 50, 'PreSales Sourcing', '2024-09-20', 'Completed', 100, 'ada', 'uploads/documents/1726850036_c1f53383c85263686eb3.pdf', 3),
(115, 50, 'Draft Proposal', '2024-09-20', 'Completed', 100, 'ada', NULL, 4),
(116, 50, 'Sent Proposal', '2024-09-20', 'Completed', 100, '', 'uploads/documents/1726850226_6b5a93a767ad207d5699.docx', 5),
(117, 50, 'Sent Komersial', '2024-09-20', 'Completed', 100, '', 'uploads/documents/1726850265_6248de646087041865dd.docx', 6),
(118, 50, 'Clarification', '2024-09-20', 'Completed', 100, 'adadaw', 'uploads/documents/1726854142_f502c8eba521aed9a860.pdf', 7),
(119, 50, 'Tender dan Nego', '2024-09-20', 'Completed', 100, 'ad', NULL, 8),
(120, 46, 'Sent Komersial', '2024-09-22', 'In Progress', 50, '', NULL, 6),
(121, 50, 'Contract Draft', '2024-09-22', 'Completed', 100, '', NULL, 9),
(123, 50, 'Contract Signed', '2024-09-22', 'Completed', 100, '', NULL, 10),
(124, 48, 'Scooping', '2024-09-25', 'Completed', 100, '', NULL, 1),
(125, 48, 'Presentasi', '2024-09-25', 'Completed', 100, '', NULL, 2),
(126, 48, 'PreSales Sourcing', '2024-09-25', 'Completed', 100, '', NULL, 3),
(127, 48, 'Draft Proposal', '2024-09-25', 'Completed', 100, '', NULL, 4),
(128, 48, 'Sent Proposal', '2024-09-25', 'Completed', 100, '', NULL, 5),
(129, 48, 'Sent Komersial', '2024-09-25', 'Completed', 100, '', NULL, 6),
(130, 48, 'Clarification', '2024-09-25', 'Completed', 100, '', NULL, 7),
(131, 48, 'Tender dan Nego', '2024-09-25', 'Completed', 100, '', NULL, 8),
(132, 48, 'Contract Draft', '2024-09-25', 'Completed', 100, '', NULL, 9),
(133, 48, 'Contract Signed', '2024-09-25', 'Completed', 100, '', NULL, 10),
(134, 49, 'Clarification', '2024-09-25', 'Completed', 100, '', NULL, 7),
(135, 49, 'Tender dan Nego', '2024-09-25', 'Completed', 100, '', NULL, 8),
(136, 52, 'Scooping', '2024-09-26', 'Completed', 100, '', NULL, 1),
(137, 52, 'Presentasi', '2024-09-26', 'Completed', 100, '', NULL, 2),
(138, 52, 'PreSales Sourcing', '2024-09-26', 'Completed', 100, '', NULL, 3),
(139, 52, 'Draft Proposal', '2024-09-26', 'Completed', 100, '', NULL, 4),
(140, 52, 'Sent Proposal', '2024-09-26', 'Completed', 100, '', NULL, 5),
(141, 52, 'Sent Komersial', '2024-09-26', 'Completed', 100, '', NULL, 6),
(142, 52, 'Clarification', '2024-09-26', 'Completed', 100, '', NULL, 7),
(143, 52, 'Tender dan Nego', '2024-09-26', 'Completed', 100, '', NULL, 8),
(144, 52, 'Contract Draft', '2024-09-26', 'Completed', 100, '', NULL, 9),
(145, 52, 'Contract Signed', '2024-09-26', 'Completed', 100, 'SUKSES', NULL, 10),
(146, 53, 'Scooping', '2024-09-26', 'Completed', 100, '', NULL, 1),
(147, 53, 'Presentasi', '2024-09-26', 'Completed', 100, '', NULL, 2),
(148, 53, 'PreSales Sourcing', '2024-09-26', 'Completed', 100, '', NULL, 3),
(149, 53, 'Draft Proposal', '2024-09-26', 'Completed', 100, '', NULL, 4),
(150, 53, 'Sent Proposal', '2024-09-26', 'Completed', 100, '', NULL, 5),
(151, 53, 'Sent Komersial', '2024-09-26', 'Completed', 100, '', NULL, 6),
(152, 53, 'Clarification', '2024-09-26', 'Completed', 100, '', NULL, 7),
(153, 53, 'Tender dan Nego', '2024-09-26', 'Completed', 100, '', NULL, 8),
(154, 53, 'Contract Draft', '2024-09-26', 'Completed', 100, '', NULL, 9),
(155, 53, 'Contract Signed', '2024-09-26', 'Completed', 100, 'done', NULL, 10),
(156, 54, 'Scooping', '2024-09-27', 'Completed', 100, '', NULL, 1),
(157, 54, 'Presentasi', '2024-09-27', 'Completed', 100, 'oke', 'uploads/documents/1727406625_225180a6e91295ed5270.pdf', 2),
(158, 54, 'PreSales Sourcing', '2024-09-27', 'Completed', 100, '', NULL, 3),
(159, 54, 'Draft Proposal', '2024-09-27', 'Completed', 100, '', NULL, 4),
(160, 54, 'Sent Proposal', '2024-09-27', 'Completed', 100, '', NULL, 5),
(161, 54, 'Sent Komersial', '2024-09-27', 'Completed', 100, '', NULL, 6),
(162, 54, 'Clarification', '2024-09-27', 'Completed', 100, '', NULL, 7),
(163, 54, 'Tender dan Nego', '2024-09-27', 'Completed', 100, '', NULL, 8),
(164, 54, 'Contract Draft', '2024-09-27', 'Completed', 100, '', NULL, 9),
(165, 54, 'Contract Signed', '2024-09-27', 'Completed', 100, '', NULL, 10),
(166, 55, 'Scooping', '2024-09-27', 'Completed', 100, '', NULL, 1),
(167, 55, 'Presentasi', '2024-09-27', 'Completed', 100, '', NULL, 2),
(168, 55, 'PreSales Sourcing', '2024-09-27', 'Completed', 100, '', NULL, 3),
(169, 55, 'Draft Proposal', '2024-09-27', 'Completed', 100, '', NULL, 4),
(170, 55, 'Sent Proposal', '2024-09-27', 'Completed', 100, '', NULL, 5),
(171, 55, 'Sent Komersial', '2024-09-27', 'Completed', 100, '', NULL, 6),
(172, 55, 'Clarification', '2024-09-27', 'Completed', 100, '', NULL, 7),
(173, 55, 'Tender dan Nego', '2024-09-27', 'Completed', 100, '', NULL, 8),
(174, 55, 'Contract Draft', '2024-09-27', 'Completed', 100, '', NULL, 9),
(175, 55, 'Contract Signed', '2024-09-27', 'Completed', 100, '', NULL, 10),
(176, 56, 'Scooping', '2024-09-27', 'Completed', 100, '', NULL, 1),
(177, 56, 'Presentasi', '2024-09-27', 'Completed', 100, '', NULL, 2),
(178, 56, 'PreSales Sourcing', '2024-09-27', 'Completed', 100, '', NULL, 3),
(179, 56, 'Draft Proposal', '2024-09-27', 'Completed', 100, '', NULL, 4),
(180, 56, 'Sent Proposal', '2024-09-27', 'Completed', 100, '', NULL, 5),
(181, 56, 'Sent Komersial', '2024-09-27', 'Completed', 100, '', NULL, 6),
(182, 56, 'Clarification', '2024-09-27', 'Completed', 100, '', NULL, 7),
(183, 56, 'Tender dan Nego', '2024-09-27', 'Completed', 100, '', NULL, 8),
(184, 56, 'Contract Draft', '2024-09-27', 'Completed', 100, '', NULL, 9),
(185, 56, 'Contract Signed', '2024-09-27', 'Completed', 100, '', NULL, 10),
(186, 57, 'Scooping', '2024-09-27', 'Completed', 100, 'xXc', NULL, 1),
(187, 57, 'Presentasi', '2024-09-27', 'Completed', 100, '', NULL, 2),
(188, 57, 'PreSales Sourcing', '2024-09-27', 'Completed', 100, '', NULL, 3),
(189, 57, 'Draft Proposal', '2024-09-27', 'Completed', 100, '', NULL, 4),
(190, 57, 'Sent Proposal', '2024-09-27', 'Completed', 100, '', NULL, 5),
(191, 57, 'Sent Komersial', '2024-09-27', 'Completed', 100, '', NULL, 6),
(192, 57, 'Clarification', '2024-09-27', 'Completed', 100, '', NULL, 7),
(193, 57, 'Tender dan Nego', '2024-09-27', 'Completed', 100, '', NULL, 8),
(194, 57, 'Contract Draft', '2024-09-27', 'Completed', 100, '', NULL, 9),
(195, 57, 'Contract Signed', '2024-09-27', 'Completed', 100, 'selesai', 'uploads/documents/1727408825_948c9669c69a431952aa.pdf', 10),
(196, 51, 'Scooping', '2024-09-29', 'Completed', 100, '', NULL, 1),
(197, 51, 'Presentasi', '2024-09-29', 'Completed', 100, '', NULL, 2),
(198, 51, 'PreSales Sourcing', '2024-09-29', 'Completed', 100, '', NULL, 3),
(199, 51, 'Draft Proposal', '2024-09-29', 'Completed', 100, '', NULL, 4),
(200, 51, 'Sent Proposal', '2024-09-29', 'Completed', 100, '', NULL, 5),
(201, 51, 'Sent Komersial', '2024-09-29', 'Completed', 100, '', NULL, 6),
(202, 51, 'Clarification', '2024-09-29', 'Completed', 100, '', NULL, 7),
(203, 51, 'Tender dan Nego', '2024-09-29', 'Completed', 100, '', NULL, 8),
(204, 51, 'Contract Draft', '2024-09-29', 'Completed', 100, '', NULL, 9),
(205, 51, 'Contract Signed', '2024-09-29', 'Completed', 100, '', NULL, 10),
(206, 49, 'Contract Draft', '2024-09-29', 'Completed', 100, '', NULL, 9),
(207, 49, 'Contract Signed', '2024-09-29', 'Completed', 100, '', NULL, 10),
(208, 58, 'Scooping', '2024-09-29', 'Completed', 100, '', NULL, 1),
(209, 58, 'Presentasi', '2024-09-29', 'Completed', 100, '', NULL, 2),
(210, 58, 'PreSales Sourcing', '2024-09-29', 'Completed', 100, '', NULL, 3),
(211, 58, 'Draft Proposal', '2024-09-29', 'Completed', 100, '', NULL, 4),
(212, 58, 'Sent Proposal', '2024-09-29', 'Completed', 100, '', NULL, 5),
(213, 58, 'Sent Komersial', '2024-09-29', 'Completed', 100, '', NULL, 6),
(214, 58, 'Clarification', '2024-09-29', 'Completed', 100, '', NULL, 7),
(215, 58, 'Tender dan Nego', '2024-09-29', 'Completed', 100, '', NULL, 8),
(216, 58, 'Contract Draft', '2024-09-29', 'Completed', 100, '', NULL, 9),
(217, 58, 'Contract Signed', '2024-09-29', 'Completed', 100, '', NULL, 10),
(218, 59, 'Scooping', '2024-09-29', 'Completed', 100, '', NULL, 1),
(219, 59, 'Presentasi', '2024-09-29', 'Completed', 100, '', NULL, 2),
(220, 59, 'PreSales Sourcing', '2024-09-29', 'Completed', 100, '', NULL, 3),
(221, 59, 'Draft Proposal', '2024-09-29', 'Completed', 100, '', NULL, 4),
(222, 59, 'Sent Proposal', '2024-09-29', 'Completed', 100, '', NULL, 5),
(223, 59, 'Sent Komersial', '2024-09-29', 'Completed', 100, '', NULL, 6),
(224, 59, 'Clarification', '2024-09-29', 'Completed', 100, '', NULL, 7),
(225, 59, 'Tender dan Nego', '2024-09-29', 'Completed', 100, '', NULL, 8),
(226, 59, 'Contract Draft', '2024-09-29', 'Completed', 100, '', NULL, 9),
(227, 59, 'Contract Signed', '2024-09-29', 'Completed', 100, '', NULL, 10),
(228, 60, 'Scooping', '2024-09-30', 'Completed', 100, '', NULL, 1),
(229, 60, 'Presentasi', '2024-10-01', 'Completed', 100, '', NULL, 2),
(230, 61, 'Scooping', '2024-10-01', 'Completed', 100, '', NULL, 1),
(231, 72, 'Scooping', '2024-10-03', 'Completed', 100, '', NULL, 1),
(232, 72, 'Presentasi', '2024-10-03', 'Completed', 100, '', NULL, 2),
(233, 72, 'PreSales Sourcing', '2024-10-03', 'Completed', 100, '', NULL, 3),
(234, 72, 'Draft Proposal', '2024-10-03', 'Completed', 100, '', NULL, 4),
(235, 72, 'Sent Proposal', '2024-10-03', 'Completed', 100, '', NULL, 5),
(236, 72, 'Sent Komersial', '2024-10-03', 'Completed', 100, '', NULL, 6),
(237, 72, 'Clarification', '2024-10-03', 'Completed', 100, '', NULL, 7),
(238, 72, 'Tender dan Nego', '2024-10-03', 'Completed', 100, '', NULL, 8),
(239, 72, 'Contract Draft', '2024-10-03', 'Completed', 100, '', NULL, 9),
(240, 72, 'Contract Signed', '2024-10-03', 'Completed', 100, '', NULL, 10),
(241, 73, 'Scooping', '2024-10-03', 'Completed', 100, '', NULL, 1),
(242, 73, 'Presentasi', '2024-10-03', 'Completed', 100, '', NULL, 2),
(243, 73, 'PreSales Sourcing', '2024-10-03', 'Completed', 100, '', NULL, 3),
(244, 73, 'Draft Proposal', '2024-10-03', 'Completed', 100, '', NULL, 4),
(245, 73, 'Sent Proposal', '2024-10-03', 'Completed', 100, '', NULL, 5),
(246, 73, 'Sent Komersial', '2024-10-03', 'Completed', 100, '', NULL, 6),
(247, 73, 'Clarification', '2024-10-03', 'Completed', 100, '', NULL, 7),
(248, 73, 'Tender dan Nego', '2024-10-03', 'Completed', 100, '', NULL, 8),
(249, 73, 'Contract Draft', '2024-10-03', 'Completed', 100, '', NULL, 9),
(250, 73, 'Contract Signed', '2024-10-03', 'Completed', 100, '', NULL, 10);

-- --------------------------------------------------------

--
-- Table structure for table `pre_sales_team`
--

CREATE TABLE `pre_sales_team` (
  `pre_sales_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `division_id` int(11) DEFAULT NULL,
  `status` enum('Manager','Chief','Staff','Other') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pre_sales_team`
--

INSERT INTO `pre_sales_team` (`pre_sales_id`, `name`, `division_id`, `status`) VALUES
(1, 'Haerudin', 1, 'Manager'),
(2, 'Zidan ', 2, 'Chief'),
(3, 'Jaenab', 3, 'Manager');

-- --------------------------------------------------------

--
-- Table structure for table `prospects`
--

CREATE TABLE `prospects` (
  `prospect_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `prospect_scope` text NOT NULL,
  `estimated_revenue` decimal(15,2) DEFAULT NULL,
  `actual_revenue` decimal(15,2) DEFAULT NULL,
  `projected_quarter` varchar(50) DEFAULT NULL,
  `target_month_contract` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `prospect_status` enum('CLOSED','ACTIVE','HOLD','FAILED') NOT NULL DEFAULT 'ACTIVE',
  `conversion` tinyint(1) DEFAULT 0,
  `conversion_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prospects`
--

INSERT INTO `prospects` (`prospect_id`, `customer_id`, `prospect_scope`, `estimated_revenue`, `actual_revenue`, `projected_quarter`, `target_month_contract`, `user_id`, `prospect_status`, `conversion`, `conversion_date`, `remarks`, `created_at`, `updated_at`) VALUES
(46, 7, 'Bank Keamanan', 124141342.00, NULL, 'Q4', 'Oktober', 1, 'ACTIVE', 0, NULL, 'tes', '2024-09-17 19:31:50', '2024-09-17 21:30:59'),
(47, 8, 'sdsfsfs', 5756.00, NULL, 'NEW', 'JULI', 1, 'CLOSED', 0, NULL, 'gg', '2024-09-17 19:50:33', '2024-09-25 19:32:06'),
(48, 9, 'go', 34534576.00, 35000000.00, 'Q1', 'July', 1, 'CLOSED', 1, '2023-09-25', '0152.52/2-1A', '2023-09-17 22:11:26', '2023-09-26 20:57:37'),
(49, 6, 'aplikasi bank aad', 1000000.00, NULL, 'Q1', 'December', 1, 'CLOSED', 1, '2024-09-29', 'tesad', '2024-09-20 05:19:52', '2024-09-29 09:51:20'),
(50, 4, 'obat', 20976124.00, 23000000.00, 'Q1', 'May', 1, 'CLOSED', 1, '2024-09-22', 'mantap', '2024-09-20 08:43:16', '2024-09-25 19:32:06'),
(51, 5, 'Aplikasi Rumah Sakit', 21000000.00, 0.00, 'NEW', 'OKTOBER', 1, 'CLOSED', 1, '2024-09-29', 'mafafafjafajfjbanfjafbfbjafafaf', '2024-09-25 14:05:32', '2024-09-29 02:25:38'),
(52, 8, 'membuatt aplikasi', 56000000.00, 57000000.00, 'Q1', 'SEPTEMBER', 2, 'ACTIVE', 1, '2024-09-26', 'KS', '2024-09-26 06:24:48', '2024-09-30 21:00:46'),
(53, 6, 'cyber security', 72000000.00, 74000000.00, 'NEW', 'JANUARI', 6, 'ACTIVE', 1, '2024-09-26', 'tessss', '2024-09-26 06:34:37', '2024-09-26 20:05:10'),
(54, 11, 'baja besi', 55000000.00, 56000000.00, 'Q2', 'NOVEMBER', 6, 'ACTIVE', 1, '2024-09-27', 'GOGO', '2024-09-26 19:58:48', '2024-09-26 20:12:47'),
(55, 9, 'energi angin', 34200000.00, 35000000.00, 'NEW', 'MEI', 7, 'ACTIVE', 1, '2024-09-27', 'SASWD', '2024-09-26 20:16:14', '2024-09-26 20:17:49'),
(56, 1, 'aplikasi tracking', 21000000.00, 21000000.00, 'NEW', 'JANUARI', 8, 'HOLD', 1, '2024-09-27', 'ASDAWDADADAD', '2024-09-26 20:19:47', '2024-09-26 20:21:50'),
(57, 13, 'Digitalisasi', 500000000.00, 466000000.00, 'q1', 'MEI', 8, 'ACTIVE', 1, '2024-09-27', 'JHHJBGU', '2024-09-26 20:40:32', '2024-09-26 20:48:56'),
(58, 4, 'awdawdwadawdawd', 17000000.00, 0.00, 'Q4', 'MAY', 6, 'CLOSED', 1, '2024-09-29', 'AWD', '2024-09-29 10:01:39', '2024-09-29 10:02:42'),
(59, 12, 'Cupidatat obcaecati ', 12000000.00, 0.00, 'Consequuntur culpa n', 'JANUARI', 6, 'CLOSED', 1, '2024-09-29', 'Tempora dolor fuga ', '2024-09-29 10:11:01', '2024-09-29 10:15:04'),
(60, 4, 'adojadaiwdad', 15000000.00, 0.00, 'Q2', 'FEBRUARI', 2, 'ACTIVE', 0, NULL, 'yaaaa', '2024-09-29 20:04:20', '2024-09-30 21:04:36'),
(61, 3, 'penambahan jaringan', 300000000.00, 0.00, 'Q2', 'JULI', 2, 'ACTIVE', 0, NULL, 'tpasdfsdf', '2024-09-30 21:16:12', '2024-09-30 21:16:12'),
(62, 3, 'penambahan jaringan asdada', 3000000.00, 0.00, 'Q2', 'JULI', 2, 'ACTIVE', 0, NULL, 'tpasdfsdfadawd', '2024-09-30 21:17:03', '2024-09-30 21:17:03'),
(64, 8, 'BAJAA', 302000000.00, 0.00, 'Q1', 'FEBRUARI', 2, 'FAILED', 0, NULL, 'ASFASFAF', '2024-09-30 21:19:54', '2024-09-30 21:28:11'),
(67, 12, 'penambahan jaringan', 300000000.00, 0.00, 'Q1', 'JANUARI', 3, 'ACTIVE', 0, NULL, ' c ssgf', '2024-09-30 21:33:35', '2024-09-30 21:33:35'),
(69, 1, 'tambah jaringan', 0.03, 0.00, 'Q1', 'MARET', 1, 'ACTIVE', 0, NULL, 'yaya', '2024-09-30 21:37:07', '2024-09-30 21:37:07'),
(70, 3, 'pembuatan jaringan baru', 500000000.00, 0.00, 'Q3', 'AGUSTUS', 1, 'ACTIVE', 0, NULL, 'yaya', '2024-09-30 21:38:16', '2024-09-30 21:38:16'),
(71, 9, 'perbaikan jaringan', 400000000.00, 0.00, 'Q3', 'DESEMBER', 10, 'ACTIVE', 0, NULL, 'yayaya', '2024-09-30 21:42:31', '2024-09-30 21:42:31'),
(72, 11, 'iya pos', 76000000.00, 75000000.00, 'Q1', 'OKTOBER', 7, 'CLOSED', 1, '2024-10-03', 'hj', '2024-10-02 21:29:25', '2024-10-03 20:47:13'),
(73, 13, 'asfavafacvccc', 35000000.00, 40000000.00, 'NEW', 'JULI', 8, 'CLOSED', 1, '2024-10-03', 'd', '2024-10-02 22:50:08', '2024-10-03 20:47:54');

-- --------------------------------------------------------

--
-- Table structure for table `prospect_divisions`
--

CREATE TABLE `prospect_divisions` (
  `id` int(11) NOT NULL,
  `prospect_id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prospect_divisions`
--

INSERT INTO `prospect_divisions` (`id`, `prospect_id`, `division_id`) VALUES
(7, 46, 2),
(8, 46, 3),
(9, 47, 2),
(17, 49, 1),
(22, 50, 1),
(23, 50, 3),
(26, 51, 2),
(33, 53, 3),
(36, 54, 2),
(37, 54, 3),
(39, 55, 1),
(41, 56, 3),
(45, 57, 1),
(46, 48, 1),
(47, 48, 3),
(48, 58, 3),
(49, 59, 2),
(50, 59, 3),
(52, 52, 1),
(56, 60, 1),
(57, 60, 2),
(58, 60, 3),
(59, 61, 1),
(60, 62, 3),
(66, 64, 2),
(68, 67, 1),
(69, 67, 2),
(72, 69, 1),
(73, 69, 2),
(74, 70, 1),
(75, 70, 2),
(76, 71, 2),
(77, 71, 3),
(106, 72, 3),
(107, 73, 1),
(108, 73, 3);

-- --------------------------------------------------------

--
-- Table structure for table `prospect_pre_sales`
--

CREATE TABLE `prospect_pre_sales` (
  `id` int(11) NOT NULL,
  `prospect_id` int(11) NOT NULL,
  `pre_sales_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prospect_pre_sales`
--

INSERT INTO `prospect_pre_sales` (`id`, `prospect_id`, `pre_sales_id`) VALUES
(7, 46, 1),
(8, 46, 2),
(9, 47, 2),
(17, 49, 1),
(22, 50, 1),
(23, 50, 3),
(26, 51, 2),
(33, 53, 3),
(36, 54, 2),
(37, 54, 3),
(39, 55, 1),
(41, 56, 3),
(45, 57, 1),
(46, 48, 1),
(47, 48, 3),
(48, 58, 3),
(49, 59, 2),
(50, 59, 3),
(52, 52, 1),
(54, 60, 2),
(55, 61, 1),
(56, 62, 3),
(62, 64, 2),
(64, 67, 1),
(66, 69, 1),
(67, 70, 1),
(68, 71, 1),
(97, 72, 3),
(98, 73, 1),
(99, 73, 3);

-- --------------------------------------------------------

--
-- Table structure for table `rkap`
--

CREATE TABLE `rkap` (
  `id` int(11) NOT NULL,
  `division_id` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `month` varchar(20) DEFAULT NULL,
  `target_revenue` decimal(15,2) DEFAULT NULL,
  `actual_revenue` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rkap`
--

INSERT INTO `rkap` (`id`, `division_id`, `year`, `month`, `target_revenue`, `actual_revenue`, `created_at`, `updated_at`) VALUES
(1, 1, 2024, 'January', 12000000.00, NULL, '2024-10-02 15:04:16', '2024-10-02 15:04:16'),
(2, 2, 2024, 'January', 15000000.00, NULL, '2024-10-02 15:05:09', '2024-10-02 15:05:09'),
(4, 2, 2024, 'October', 43000000.00, NULL, '2024-10-02 15:05:51', '2024-10-02 15:05:51'),
(6, 1, 2024, 'October', 34000000.00, 20000000.00, '2024-10-02 21:09:38', '2024-10-03 20:47:54'),
(7, 1, 2024, 'September', 27000000.00, 569500000.00, '2024-10-02 21:12:12', '2024-10-02 21:15:56'),
(8, 2, 2024, 'September', 27000000.00, 30000000.00, '2024-10-02 21:25:54', '2024-10-02 23:19:43'),
(9, 3, 2024, 'September', 40000000.00, 134500000.00, '2024-10-02 21:26:25', '2024-10-02 21:26:25'),
(15, 3, 2024, 'October', 100000000.00, 95000000.00, '2024-10-02 23:17:56', '2024-10-03 20:47:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('Admin','Sales','AM') NOT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `full_name`, `email`, `password_hash`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'ipang', 'Rivaldi Rasyid', 'rivaldirasyid2015@gmail.com', '$2y$10$snbjg438e7/Mn83p1QSFo.9wzGqCbs9cTDHwnzcls7PNxD3/lZliy', 'Sales', 'b26a4054b1be19cd9c8b4b0acb4aeeca', '2024-09-07 09:31:49', '2024-09-10 04:08:23'),
(2, 'fatan1', 'Fatan Ihsan', 'fatan@gmail.com', '$2y$10$J2RmX1i6Bcnhn2R53nhAzOKV3fOOpHfSaJ9iz2Sl1bUHXBEgbnWeW', 'Sales', NULL, '2024-09-07 09:38:48', '2024-09-07 09:38:48'),
(3, 'admin', 'Admin', 'admin@gmail.com', '$2y$10$5wP6j1.UBRe79DTAHmQnfuGROn/K4HntegCNBPNl/K6T39GdOdscC', 'Admin', NULL, '2024-09-07 10:43:29', '2024-09-08 18:39:48'),
(6, 'alip', 'Alief Irwansyah', 'alip@gmail.com', '$2y$10$XnhOG0KApsu.hen0WhpVEuAbkRHJclV3/At3UOOov37qxi.GcfWg6', 'Sales', NULL, '2024-09-26 06:29:36', '2024-09-26 06:29:36'),
(7, 'naura', 'Naura Kamila', 'naura@gmail.com', '$2y$10$AdTImW/rS6LVQWXepB1tk.YrL0iWWy0fyuu/Py.EfGJN9eCaVjGF.', 'Sales', NULL, '2024-09-26 20:14:10', '2024-09-26 20:14:10'),
(8, 'tiara', 'Tiara', 'tiara@gmail.com', '$2y$10$PRGXHOnZrYNRccj3fhL3DuxnOz2C8370hZA5iwvReK65p37gUOcCC', 'Sales', NULL, '2024-09-26 20:18:44', '2024-09-26 20:18:44'),
(9, 'nafis', 'Nafis AM', 'nafis@gmail.com', '$2y$10$YHENprB7GUpAprnyZQtH9u/mZNe.zFw.WCe9F8L2NW/xJ0oKvaXjG', 'AM', NULL, '2024-09-29 19:23:40', '2024-09-29 19:23:40'),
(10, 'rivaldi', 'ipang', 'rivaldi@gmail.com', '$2y$10$p6.fYBO9Bi/lHf68qtnfv.py/OLnxUlnrx8r6XmdlN0iTK8kW3Hmi', 'Sales', NULL, '2024-09-30 21:40:13', '2024-10-01 04:43:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `divisions`
--
ALTER TABLE `divisions`
  ADD PRIMARY KEY (`division_id`);

--
-- Indexes for table `financial_details`
--
ALTER TABLE `financial_details`
  ADD PRIMARY KEY (`financial_id`),
  ADD KEY `prospect_id` (`prospect_id`);

--
-- Indexes for table `milestones_prospect`
--
ALTER TABLE `milestones_prospect`
  ADD PRIMARY KEY (`milestone_id`),
  ADD KEY `prospect_id` (`prospect_id`);

--
-- Indexes for table `pre_sales_team`
--
ALTER TABLE `pre_sales_team`
  ADD PRIMARY KEY (`pre_sales_id`),
  ADD KEY `fk_presales_division` (`division_id`);

--
-- Indexes for table `prospects`
--
ALTER TABLE `prospects`
  ADD PRIMARY KEY (`prospect_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_prospect_status` (`prospect_status`),
  ADD KEY `fk_customer_prospect` (`customer_id`);

--
-- Indexes for table `prospect_divisions`
--
ALTER TABLE `prospect_divisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prospect_id` (`prospect_id`),
  ADD KEY `division_id` (`division_id`);

--
-- Indexes for table `prospect_pre_sales`
--
ALTER TABLE `prospect_pre_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prospect_id` (`prospect_id`),
  ADD KEY `pre_sales_id` (`pre_sales_id`);

--
-- Indexes for table `rkap`
--
ALTER TABLE `rkap`
  ADD PRIMARY KEY (`id`),
  ADD KEY `division_id` (`division_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `divisions`
--
ALTER TABLE `divisions`
  MODIFY `division_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `financial_details`
--
ALTER TABLE `financial_details`
  MODIFY `financial_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `milestones_prospect`
--
ALTER TABLE `milestones_prospect`
  MODIFY `milestone_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- AUTO_INCREMENT for table `pre_sales_team`
--
ALTER TABLE `pre_sales_team`
  MODIFY `pre_sales_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `prospects`
--
ALTER TABLE `prospects`
  MODIFY `prospect_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `prospect_divisions`
--
ALTER TABLE `prospect_divisions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `prospect_pre_sales`
--
ALTER TABLE `prospect_pre_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `rkap`
--
ALTER TABLE `rkap`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `financial_details`
--
ALTER TABLE `financial_details`
  ADD CONSTRAINT `financial_details_ibfk_1` FOREIGN KEY (`prospect_id`) REFERENCES `prospects` (`prospect_id`) ON DELETE CASCADE;

--
-- Constraints for table `milestones_prospect`
--
ALTER TABLE `milestones_prospect`
  ADD CONSTRAINT `milestones_prospect_ibfk_1` FOREIGN KEY (`prospect_id`) REFERENCES `prospects` (`prospect_id`) ON DELETE CASCADE;

--
-- Constraints for table `pre_sales_team`
--
ALTER TABLE `pre_sales_team`
  ADD CONSTRAINT `fk_presales_division` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`) ON DELETE SET NULL;

--
-- Constraints for table `prospects`
--
ALTER TABLE `prospects`
  ADD CONSTRAINT `fk_customer_prospect` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `prospects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `prospect_divisions`
--
ALTER TABLE `prospect_divisions`
  ADD CONSTRAINT `fk_prospect_divisions_division` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_prospect_divisions_prospect` FOREIGN KEY (`prospect_id`) REFERENCES `prospects` (`prospect_id`) ON DELETE CASCADE;

--
-- Constraints for table `prospect_pre_sales`
--
ALTER TABLE `prospect_pre_sales`
  ADD CONSTRAINT `fk_prospect_pre_sales_presales` FOREIGN KEY (`pre_sales_id`) REFERENCES `pre_sales_team` (`pre_sales_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_prospect_pre_sales_prospect` FOREIGN KEY (`prospect_id`) REFERENCES `prospects` (`prospect_id`) ON DELETE CASCADE;

--
-- Constraints for table `rkap`
--
ALTER TABLE `rkap`
  ADD CONSTRAINT `rkap_ibfk_1` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
