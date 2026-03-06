-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 04, 2026 at 11:26 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `it_inventory_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `borrowings`
--

CREATE TABLE `borrowings` (
  `id` int(11) NOT NULL,
  `equipment_id` int(11) NOT NULL COMMENT 'อ้างอิง ID อุปกรณ์',
  `employee_id` varchar(50) NOT NULL COMMENT 'รหัสพนักงานผู้ยืม',
  `location` varchar(150) DEFAULT NULL COMMENT 'ห้องที่ตั้งอุปกรณ์',
  `employee_name` varchar(150) NOT NULL COMMENT 'ชื่อพนักงานผู้ยืม',
  `borrow_date` datetime DEFAULT current_timestamp() COMMENT 'วันที่และเวลายืม',
  `return_date` datetime DEFAULT NULL COMMENT 'วันที่และเวลาคืน (ถ้ายังไม่คืนจะว่าง)',
  `status` enum('active','returned') DEFAULT 'active' COMMENT 'สถานะการยืม (active=กำลังยืม, returned=คืนแล้ว)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowings`
--

INSERT INTO `borrowings` (`id`, `equipment_id`, `employee_id`, `location`, `employee_name`, `borrow_date`, `return_date`, `status`) VALUES
(4, 11, '3', 'ห้องบัญชี', '', '2026-02-02 08:30:00', NULL, 'active'),
(5, 12, '5', 'ห้องCS', '', '2025-03-01 08:30:00', '2025-05-31 17:30:00', 'returned'),
(6, 12, '6', 'ห้องCS', '', '2025-06-04 08:30:00', NULL, 'active'),
(7, 43, '7', 'ห้องHR', '', '2026-01-26 08:30:00', NULL, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `emp_code` varchar(50) NOT NULL COMMENT 'รหัสพนักงาน',
  `emp_name` varchar(150) NOT NULL COMMENT 'ชื่อ-นามสกุล',
  `department` varchar(100) DEFAULT NULL COMMENT 'แผนก/ฝ่าย',
  `position` varchar(100) DEFAULT NULL COMMENT 'ตำแหน่ง',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `emp_code`, `emp_name`, `department`, `position`, `created_at`) VALUES
(1, '69002', 'กังสดาล อ่ำอ่อน', 'IT', 'IT Support', '2026-02-27 08:07:44'),
(3, '69001', 'กิตติพน จงจอหอ', 'Accounting', 'Account Officer', '2026-03-02 06:09:38'),
(5, '68033', 'ตระกูล ทัพพิมล', '', '', '2026-03-02 06:30:04'),
(6, '68044', 'พรสุดา เห็นสม', 'Customer Service (Air)', 'Customer Service (Air)', '2026-03-02 06:51:40'),
(7, '61002', 'อรรถสิทธิ์ อินทพัฒน์', 'Customs Clearance', 'Stock control Supervisor', '2026-03-04 08:18:12'),
(8, '60001', 'พณิชญา เรืองแก้ว', 'Express', 'Customs Clearance Manager', '2026-03-04 08:18:58');

-- --------------------------------------------------------

--
-- Table structure for table `equipments`
--

CREATE TABLE `equipments` (
  `id` int(11) NOT NULL,
  `barcode` varchar(100) NOT NULL COMMENT 'รหัสบาร์โค้ดติดอุปกรณ์ (จำเป็น)',
  `serial_number` varchar(100) DEFAULT NULL COMMENT 'หมายเลขซีเรียลของหน้าจอ (กันสลับจอ)',
  `location` varchar(255) DEFAULT NULL,
  `type` varchar(50) NOT NULL COMMENT 'ประเภท เช่น PC, Notebook, Monitor',
  `brand` varchar(100) DEFAULT NULL COMMENT 'ยี่ห้อ',
  `model` varchar(100) DEFAULT NULL COMMENT 'รุ่น',
  `cpu_gen` varchar(100) DEFAULT NULL COMMENT 'สเปค CPU เช่น i5 Gen 12',
  `ram_gb` int(11) DEFAULT NULL COMMENT 'ขนาด RAM (GB)',
  `storage_type` enum('HDD','SSD','M.2') DEFAULT NULL COMMENT 'ประเภทที่เก็บข้อมูล',
  `storage_gb` int(11) DEFAULT NULL COMMENT 'ขนาดพื้นที่เก็บข้อมูล (GB)',
  `os` varchar(50) DEFAULT NULL COMMENT 'ระบบปฏิบัติการ เช่น Windows 11',
  `status` enum('available','borrowed','maintenance','broken') DEFAULT 'available' COMMENT 'สถานะเครื่อง',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `remark` text DEFAULT NULL COMMENT 'หมายเหตุการซ่อมหรืออื่นๆ',
  `storage_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'ข้อมูล storage หลายตัว' CHECK (json_valid(`storage_json`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipments`
--

INSERT INTO `equipments` (`id`, `barcode`, `serial_number`, `location`, `type`, `brand`, `model`, `cpu_gen`, `ram_gb`, `storage_type`, `storage_gb`, `os`, `status`, `created_at`, `remark`, `storage_json`) VALUES
(9, 'CTG00012IT', '', NULL, 'PC', '', 'DESKTOP-1DE537P', 'i5-11400F', 16, 'SSD', 1000, 'Windows11 Pro', 'available', '2026-03-02 06:00:43', NULL, NULL),
(10, 'CTG00150IT', 'PB4H714900506', NULL, 'Monitor', 'MSI', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 06:02:55', NULL, NULL),
(11, 'CTG00024IT', 'ATQPA9A000446', NULL, 'Monitor', 'AOC', '', '', NULL, 'SSD', NULL, '', 'borrowed', '2026-03-02 06:08:55', NULL, NULL),
(12, 'CTG00149IT', '', NULL, 'PC', '', '', '', NULL, 'SSD', NULL, '', 'borrowed', '2026-03-02 06:12:09', NULL, NULL),
(13, 'CTG00045IT', 'ZZMCH4ZJ802761J', NULL, 'Monitor', 'Samsung', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 06:12:46', NULL, NULL),
(14, 'CTG00044IT', '', '', 'PC', '', 'DESKTOP-3IFMM7D', 'i3-8100', 16, 'SSD', NULL, 'Windows 10 Pro', 'available', '2026-03-02 06:16:09', '', '[{\"type\":\"HDD\",\"gb\":1000}]'),
(15, 'CTG00144IT', 'C2412N00062146B9', NULL, 'Notebook', 'MSI', 'LAPTOP-CTG28POL', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 06:21:52', NULL, NULL),
(16, 'CTG00147IT', 'C2412N0006272B28', NULL, 'Notebook', 'MSI', 'LAPTOP-CTG8Y5TO', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 06:22:47', NULL, NULL),
(17, 'CTG00151IT', '', NULL, 'PC', '', 'DESKTOP-CTGT0R3', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 06:23:25', NULL, NULL),
(18, 'CTG00137IT', 'UK02417049847', NULL, 'Monitor', 'Philips', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 06:23:47', NULL, NULL),
(20, 'CTG00152IT', 'pf5a74gzPF9XB5123069', NULL, 'Notebook', 'Lenovo', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 06:25:02', NULL, NULL),
(21, 'CTG00135IT', 'pf59bzjzPF9XB5123069', NULL, 'Notebook', 'Lenovo', 'LAPTOP-8Q08588U', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 06:25:37', NULL, NULL),
(22, 'CTG00154IT', 'pf59bw1jPF9XB5123069', NULL, 'Notebook', 'Lenovo', 'LAPTOP-8996MGMS', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 06:26:11', NULL, NULL),
(23, 'CTG00156IT', 'pf594b0wPF9XB5123069', NULL, 'Notebook', 'Lenovo', 'LAPTOP-BJ5MPVDP', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 06:26:34', NULL, NULL),
(24, 'CTG00155IT', 'pf5a7a3rPF9XB5123069', NULL, 'Notebook', 'Lenovo', 'LAPTOP-5QUHF02K', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 06:27:52', NULL, NULL),
(25, 'CTG00157IT', 'pf592hxcPF9XB5123069', NULL, 'Notebook', 'Lenovo', 'LAPTOP-S6DV4QJ9', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 06:28:15', NULL, NULL),
(26, 'CTG00158IT', 'pf5a79r9PF9XB5123069', NULL, 'Notebook', 'Lenovo', 'LAPTOP-2IQHQDKE', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 06:28:35', NULL, NULL),
(27, 'CTG00159IT', 'TH545K80B7080C', 'ห้องXunyu', 'Printer', 'HP', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 07:52:08', NULL, NULL),
(28, 'CTG00160IT', 'C2503N001968104D', '', 'Notebook', 'MSI', 'LAPTOP-CTGKI01', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 07:52:55', NULL, NULL),
(29, 'CTG00161IT', 'C2503N0019545047', '', 'Notebook', 'MSI', 'LAPTOP-CTGER98', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 07:56:48', NULL, NULL),
(30, 'CTG00162IT', 'C2503N001949241A', '', 'Notebook', 'MSI', 'LAPTOP-CTGB41F', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 07:57:37', NULL, NULL),
(31, 'CTG00163IT', 'E82897K4N541449', 'ห้องผู้บริหาร', 'Printer', 'Brother L2640DW', 'Brother L2640DW', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 07:59:19', NULL, NULL),
(32, 'CTG00076IT', '', '', 'PC', '', 'DESKTOP-GNQ6MGM', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 07:59:40', NULL, NULL),
(33, 'CTG00164IT', 'KRKE36397M', 'อ๊อฟฟิศแหลมฉบัง', 'Printer', 'Canon', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 08:20:23', NULL, NULL),
(34, 'CTG00165IT', '', '', 'PC', '', 'DESKTOP-HBIT9F9', 'Ryzen5 5600G', 32, 'SSD', 1000, 'Windows11 Pro', 'available', '2026-03-02 08:20:42', NULL, NULL),
(35, 'CTG00166IT', 'PB4H714900483', '', 'Monitor', 'MSI', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 08:21:51', NULL, NULL),
(36, 'CTG00167IT', '0D4UHNAT900163T', '', 'Monitor', 'Samsung', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 08:22:19', NULL, NULL),
(37, 'CTG00013IT', '209INUB4A254', '', 'Monitor', 'LG', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 08:23:43', NULL, NULL),
(38, 'CTG00014IT', '', '', 'PC', '', 'ADMIN', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 08:24:09', NULL, NULL),
(39, 'CTG00007IT', 'MMT5XSS0096310373C2455', '', 'Monitor', 'Acer', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 08:24:36', NULL, NULL),
(40, 'CTG00009IT', '', '', 'Monitor', 'AOC', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 08:25:00', NULL, NULL),
(41, 'CTG00169IT', 'MMT5XSS009701042BA2455', '', 'Monitor', 'Acer', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 08:25:29', NULL, NULL),
(42, 'CTG00170IT', '806INUB25582', '', 'Monitor', 'LG', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 09:03:12', NULL, NULL),
(43, 'CTG00168IT', '', '', 'PC', '', 'DESKTOP-KKKS1AV', '', NULL, 'SSD', NULL, '', 'borrowed', '2026-03-02 09:03:27', NULL, NULL),
(44, 'CTG00008IT', '', '', 'PC', '', 'CTM9999', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 09:03:56', NULL, NULL),
(45, 'CTG00171IT', '', '', 'PC', '', 'DESKTOP-RNT5E8B', '', NULL, 'SSD', NULL, '', 'available', '2026-03-02 09:04:09', NULL, NULL),
(46, 'CTG00030IT', '', '', 'PC', '', 'User', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 06:28:08', NULL, NULL),
(47, 'CTG00075IT', 'ATMP59A001680', '', 'Monitor', 'AOC', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 06:28:29', NULL, NULL),
(48, 'CTG00070IT', '208INPT91896', '', 'Monitor', 'LG', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:37:59', NULL, NULL),
(49, 'CTG00172IT', '', '', 'PC', '', 'คอมพิวเตอร์ Desktop', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:38:47', NULL, NULL),
(50, 'CTG00041IT', 'ATQP79A005212', '', 'Monitor', 'AOC', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:39:06', NULL, NULL),
(51, 'CTG00173IT', '806INFK25587', '', 'Monitor', 'LG', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:39:25', NULL, NULL),
(52, 'CTG00040IT', '', '', 'PC', '', 'DESKTOP-G8NUVG3', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:39:37', NULL, NULL),
(53, 'CTG00140IT', 'UK02417049870', '', 'Monitor', 'Philips', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:39:58', NULL, NULL),
(54, 'CTG00139IT', '', '', 'PC', '', 'DESKTOP-CTHY1B6', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:40:08', NULL, NULL),
(55, 'CTG00072IT', '', '', 'PC', '', 'คอมพิวเตอร์ Desktop', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:40:19', NULL, NULL),
(56, 'CTG00017IT', 'ZZMCH4ZJ703490P', '', 'Monitor', 'Samsung', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:40:33', NULL, NULL),
(57, 'CTG00042IT', '209INJL4A188', '', 'Monitor', 'LG', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:40:46', NULL, NULL),
(58, 'CTG00043IT', '', '', 'PC', '', 'User', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:41:03', NULL, NULL),
(59, 'CTG00003IT', '', '', 'Other', 'Asus All in One', 'R9PTCJ009962370', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:42:03', NULL, NULL),
(60, 'CTG00065IT', '', '', 'PC', '', 'INTER-122022', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:42:15', NULL, NULL),
(61, 'CTG00106IT', '', '', 'Other', 'HP All in One', 'BA9H434600608', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:43:08', NULL, NULL),
(62, 'CTG00074IT', '806INMF25675', '', 'Monitor', 'LG', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:43:23', NULL, NULL),
(63, 'CTG00071IT', 'ZZMCH4ZK400333D', '', 'Monitor', 'Samsung', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:43:38', NULL, NULL),
(64, 'CTG00077IT', '', '', 'PC', '', 'CHINA THAI45 (Server)', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:50:52', NULL, NULL),
(65, 'CTG00078IT', 'ZZMCH4ZK501395M', '', 'Monitor', 'Samsung (Server)', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:51:18', NULL, NULL),
(66, 'CTG00180IT', '', '', 'PC', '', 'DESKTOP-CRD51AJ', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:51:39', NULL, NULL),
(67, 'CTG00175IT', 'ZZMCH4ZJ800299V', '', 'Monitor', 'Samsung', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:52:11', NULL, NULL),
(68, 'CTG00016IT', '205INUB75254', '', 'Monitor', 'LG', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:52:25', NULL, NULL),
(69, 'CTG00174IT', '', '', 'Other', 'Acer All in One', '11500522430', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:53:01', NULL, NULL),
(70, 'CTG00080IT', 'ZZMCH4ZJ702403T', '', 'Monitor', 'Samsung', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:53:14', NULL, NULL),
(71, 'CTG00023IT', '', '', 'PC', '', 'User', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:53:30', NULL, NULL),
(72, 'CTG00022IT', '', '', 'PC', '', 'DESKTOP-KD64J2Q', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:53:39', NULL, NULL),
(73, 'CTG00021IT', 'ATQPA9A000442', '', 'Monitor', 'AOC', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:53:51', NULL, NULL),
(74, 'CTG00143IT', '', '', 'PC', '', 'DESKTOP-HBIT9F9', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:54:02', NULL, NULL),
(75, 'CTG00142IT', 'PB4H714900425', '', 'Monitor', 'MSI', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:54:16', NULL, NULL),
(76, 'CTG00177IT', 'ZZMCH4ZJ801400P', '', 'Monitor', 'Samsung', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:54:33', NULL, NULL),
(77, 'CTG00178IT', 'ZZNPH4ZM400683V', '', 'Monitor', 'Samsung', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:54:50', NULL, NULL),
(78, 'CTG00179IT', '', '', 'PC', '', 'DESKTOP-M90LMHV', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:55:01', NULL, NULL),
(79, 'CTG00061IT', '', '', 'PC', '', 'CTM-1102', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:55:11', NULL, NULL),
(80, 'CTG00062IT', '4713392734038', '', 'Monitor', 'Acer', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:55:22', NULL, NULL),
(81, 'CTG00181IT', 'ZZMCH4ZJ802761J', '', 'Monitor', 'Samsung', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:55:42', NULL, NULL),
(82, 'CTG00046IT', 'CXRNH4ZT902638H', '', 'Monitor', 'MSI', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:56:14', NULL, NULL),
(83, 'CTG00047IT', '', '', 'PC', '', 'DESKTOP-7TOP8B3', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:56:24', NULL, NULL),
(84, 'CTG00064IT', '205INBS75261', '', 'Monitor', 'LG', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:56:36', NULL, NULL),
(85, 'CTG00049IT', '209INRC4A166', '', 'Monitor', 'LG', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:56:51', NULL, NULL),
(86, 'CTG00176IT', '4713392734038', '', 'Monitor', 'Acer', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:57:05', NULL, NULL),
(87, 'CTG00048IT', '', '', 'PC', '', 'DESKTOP-EJPO6D5', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:57:14', NULL, NULL),
(88, 'CTG00054IT', 'ATQP79A005137', '', 'Monitor', 'AOC', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:57:25', NULL, NULL),
(89, 'CTG00053IT', '', '', 'PC', '', 'DESKTOP-V9G5OJ4', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:57:40', NULL, NULL),
(90, 'CTG00121IT', '', '', 'PC', '', 'DESKTOP-OUP8CI8', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:57:48', NULL, NULL),
(91, 'CTG00122IT', 'UK02417049859', '', 'Monitor', 'Philips', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:58:00', NULL, NULL),
(92, 'CTG00083IT', '', '', 'Other', 'Asus All in One', 'DESKTOP-S2BCNO2', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:58:34', NULL, NULL),
(93, 'CTG00028IT', 'ZZMCH4ZK500595D', '', 'Monitor', 'Samsung', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:58:47', NULL, NULL),
(94, 'CTG00029IT', '', '', 'PC', '', 'Chila', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:59:05', NULL, NULL),
(95, 'CTG00059IT', 'ZZMCH4ZJ600511F', '', 'Monitor', 'Samsung', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:59:15', NULL, NULL),
(96, 'CTG00050IT', 'MMT5XSS009701042C52455', '', 'Monitor', 'Acer', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:59:33', NULL, NULL),
(97, 'CTG00051IT', 'MMT5XSS009631037102455', '', 'Monitor', '', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:59:45', NULL, NULL),
(98, 'CTG00060IT', '', '', 'PC', '', 'DESKTOP-A2IA29G', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 07:59:54', NULL, NULL),
(99, 'CTG00052IT', '', '', 'PC', '', 'CTM100', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 08:00:08', NULL, NULL),
(100, 'CTG00056IT', '', '', 'PC', '', 'DESKTOP-DKPJ71B', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 08:00:17', NULL, NULL),
(101, 'CTG00058IT', 'ZZMCH4ZK500664J', '', 'Monitor', 'Samsung', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 08:00:28', NULL, NULL),
(102, 'CTG00055IT', 'ZZMCH4ZK101518H', '', 'Monitor', 'Samsung', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 08:00:43', NULL, NULL),
(103, 'CTG00184IT', '', '', 'PC', '', 'CHAINA30', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 08:00:55', NULL, NULL),
(104, 'CTG00183IT', 'ATQPA9A000240', '', 'Monitor', 'AOC', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 08:01:06', NULL, NULL),
(105, 'CTG00182IT', '811INDP4V930', '', 'Monitor', 'LG', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 08:01:19', NULL, NULL),
(106, 'CTG00005IT', '', '', 'Other', 'Asus All in One', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 08:01:58', NULL, NULL),
(107, 'CTG00025IT', '', '', 'PC', '', 'DESKTOP-QHA3FA2', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 08:02:08', NULL, NULL),
(108, 'CTG00136IT', '2K02417049902', '', 'Monitor', 'Philips', '', '', NULL, 'SSD', NULL, '', 'available', '2026-03-04 08:02:20', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `borrowings`
--
ALTER TABLE `borrowings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emp_code` (`emp_code`);

--
-- Indexes for table `equipments`
--
ALTER TABLE `equipments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `barcode` (`barcode`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `borrowings`
--
ALTER TABLE `borrowings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `equipments`
--
ALTER TABLE `equipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrowings`
--
ALTER TABLE `borrowings`
  ADD CONSTRAINT `borrowings_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipments` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
