-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 22, 2024 at 12:49 PM
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
-- Database: `bookshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `bs_authors`
--

CREATE TABLE `bs_authors` (
  `auth_id` int(11) NOT NULL COMMENT 'รหัสผู้แต่ง',
  `auth_name` varchar(100) NOT NULL COMMENT 'ขื่อผู้แต่ง',
  `auth_img` varchar(100) NOT NULL COMMENT 'รูปผู้แต่ง',
  `auth_detail` varchar(100) NOT NULL COMMENT 'รายละเอียดผู้แต่ง',
  `auth_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'สถานะ 1 = แสด\r\n, 0 = ไม่แต่ง',
  `auth_time_create` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วัน เวลาสร้าง',
  `auth_time_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลาแก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ข้อมูลผู้แต่ง';

--
-- Dumping data for table `bs_authors`
--

INSERT INTO `bs_authors` (`auth_id`, `auth_name`, `auth_img`, `auth_detail`, `auth_status`, `auth_time_create`, `auth_time_update`) VALUES
(21, 'An Chi Hyeon', 'img_66ee90c12658b3bb4e547584029d1aa801726910657.png', 'An Chi Hyeon', 1, '2024-09-21 09:24:17', '2024-09-21 11:47:32'),
(22, 'Yim Chang-ho', 'img_66eeb370de775a792884a354e6eedaf8a1726919536.png', 'Yim Chang-ho', 1, '2024-09-21 11:52:16', '2024-09-21 11:52:16'),
(23, 'Er Mu', 'img_66eeb455c933d1a2320417cac9361fe561726919765.png', 'Er Mu', 1, '2024-09-21 11:56:05', '2024-09-21 11:56:05'),
(24, 'มิคาอิล บูลกาคอฟ', 'img_66eeb53aeeaa26e5cf941357ccf0dedcd1726919994.png', 'มิคาอิล บูลกาคอฟ', 1, '2024-09-21 11:59:54', '2024-09-21 11:59:54'),
(25, 'Mo Xiang Tong Xiu', 'img_66eeb64d9cfa9a3b80d2f9585a105c23d1726920269.png', 'Mo Xiang Tong Xiu', 1, '2024-09-21 12:04:29', '2024-09-21 12:04:29'),
(26, 'ไลอ้อน ยุวสินธุ์ ตะวงษา (LION YUWASIN)', 'img_66eeb81aa3a5ea55fbd2e78ef8cce82621726920730.png', 'ไลอ้อน ยุวสินธุ์ ตะวงษา (LION YUWASIN)', 1, '2024-09-21 12:12:10', '2024-09-21 12:12:10'),
(27, 'สุดสัปดาห์สำนักพิมพ์', 'img_66eeba391a0d2f1d8d934fc2e9045542d1726921273.png', 'สุดสัปดาห์สำนักพิมพ์', 1, '2024-09-21 12:21:13', '2024-09-21 12:21:13'),
(28, 'จางเจียเจีย', 'img_66eebaf2b7cc9f637d8265c22ad4ab8de1726921458.png', 'จางเจียเจีย', 1, '2024-09-21 12:24:18', '2024-09-21 12:24:18'),
(29, 'องอาจ ชัยชาญชีพ', 'img_66eebe8b1880ea8d4c5d03ffeade1f4281726922379.png', 'องอาจ ชัยชาญชีพ', 1, '2024-09-21 12:39:39', '2024-09-21 12:39:39'),
(30, 'Tsuneko Nakamura', 'img_66eebf1faca3c5daa3c00e3433c0115f81726922527.png', 'Tsuneko Nakamura', 1, '2024-09-21 12:42:07', '2024-09-21 12:42:07'),
(31, 'กรกฎ หลอดคำ', 'img_66eec0281fbe8dedddbbb23432c3e59c61726922792.png', 'กรกฎ หลอดคำ', 1, '2024-09-21 12:46:32', '2024-09-21 12:46:32'),
(32, 'Little tree', 'img_66eec118c9c2a68482f1610912d0517be1726923032.png', 'Little tree', 1, '2024-09-21 12:50:32', '2024-09-21 12:50:32'),
(33, 'Yana toboso (ยานะ โทโบโซ)', 'img_66eec214cbc2399969527feb6468351821726923284.png', 'Yana toboso (ยานะ โทโบโซ)', 1, '2024-09-21 12:54:44', '2024-09-21 12:54:44');

-- --------------------------------------------------------

--
-- Table structure for table `bs_banners`
--

CREATE TABLE `bs_banners` (
  `bn_id` int(11) NOT NULL COMMENT 'รหัสแบนเนอร์',
  `bn_name` varchar(100) NOT NULL COMMENT 'ชื่ิอแบนเนอร์',
  `bn_img` varchar(100) NOT NULL COMMENT 'รูปแบนเนอร์',
  `bn_link` text NOT NULL DEFAULT '#' COMMENT 'URL แบนเนอร์',
  `bn_number_list` int(11) NOT NULL COMMENT 'ลำดับการแสดงแบนเนอร์',
  `bn_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'สถานะ 1 แสด, 0 = ไม่แสดง',
  `bn_time_create` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วัน เวลาสร้าง',
  `bn_time_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลาแก้ไช'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ข้อมูลแบนเนอร์ / โฆษณา / โปรโมท';

--
-- Dumping data for table `bs_banners`
--

INSERT INTO `bs_banners` (`bn_id`, `bn_name`, `bn_img`, `bn_link`, `bn_number_list`, `bn_status`, `bn_time_create`, `bn_time_update`) VALUES
(13, 'แบนเนอร์ 1', 'img_66eed1e446f6b3a37e2f8ec51d580992a1726927332.jpg', '', 2, 1, '2024-09-21 14:02:12', '2024-09-21 14:20:09'),
(14, 'แบนเนอร์ 2', 'img_66eed6137a5ebfbc92e6deb263335b2c01726928403.png', '', 1, 1, '2024-09-21 14:20:03', '2024-09-21 14:20:09');

-- --------------------------------------------------------

--
-- Table structure for table `bs_cart`
--

CREATE TABLE `bs_cart` (
  `crt_id` int(11) NOT NULL COMMENT 'รหัสตะกร้า',
  `mem_id` int(11) NOT NULL COMMENT 'รหัสสมาชิก',
  `prd_id` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `crt_qty` int(11) NOT NULL COMMENT 'จำนวนสินค้า',
  `crt_time_create` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน / เวลาที่เลือก'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ข้อมูลตะกร้าสินค้า';

--
-- Dumping data for table `bs_cart`
--

INSERT INTO `bs_cart` (`crt_id`, `mem_id`, `prd_id`, `crt_qty`, `crt_time_create`) VALUES
(66, 5, 126, 1, '2024-09-22 06:08:48'),
(67, 5, 122, 2, '2024-09-22 06:08:48');

-- --------------------------------------------------------

--
-- Table structure for table `bs_contacts`
--

CREATE TABLE `bs_contacts` (
  `ct_id` int(11) NOT NULL COMMENT 'รหัสช่องทางติดต่อ',
  `ct_name` varchar(100) NOT NULL COMMENT 'ชื่อช่องทางติดต่อ',
  `ct_detail` text DEFAULT NULL COMMENT 'รายละเอียดช่องทางติดต่อ',
  `ct_name_link` varchar(100) DEFAULT NULL COMMENT 'ชื่อแทน Link',
  `ct_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'สถานะ 1 = แสดง, 0 = ไม่แสดง',
  `ct_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลา'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ข้อมูลช่องทางติดต่อ';

--
-- Dumping data for table `bs_contacts`
--

INSERT INTO `bs_contacts` (`ct_id`, `ct_name`, `ct_detail`, `ct_name_link`, `ct_status`, `ct_time`) VALUES
(1, 'Facebook', NULL, NULL, 0, '2024-09-21 11:32:04'),
(2, 'Youtube', 'https://www.youtube.com/', 'BookStore CH', 1, '2024-09-21 07:11:13'),
(3, 'Twitter', 'https://x.com/', 'แนะนำหนังสือ', 1, '2024-09-21 07:11:20'),
(4, 'Email', 'example@gmail.com', NULL, 1, '2024-07-12 13:31:24'),
(5, 'Phone Number', '4444444444', NULL, 1, '2024-07-12 13:45:43'),
(6, 'Address', 'aaaaaaa', NULL, 1, '2024-07-12 13:37:49'),
(7, 'Location', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31580.447787283658!2d99.4797319!3d8.3468379!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x305234d19a79bc95%3A0x9a69990efacfb328!2sShell!5e0!3m2!1sen!2sth!4v1720077035946!5m2!1sen!2sth\" width=\"100%\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', NULL, 1, '2024-09-18 14:02:23');

-- --------------------------------------------------------

--
-- Table structure for table `bs_employees`
--

CREATE TABLE `bs_employees` (
  `emp_id` int(11) NOT NULL COMMENT 'รหัสพนักงาน',
  `emp_profile` varchar(100) NOT NULL COMMENT 'โปรไฟล์',
  `emp_fname` varchar(50) NOT NULL COMMENT 'ชื่ิอ',
  `emp_lname` varchar(50) NOT NULL COMMENT 'นามสกุล',
  `emp_username` varchar(50) NOT NULL COMMENT 'ชื่อผู้ใช้',
  `emp_password` varchar(255) NOT NULL COMMENT 'รหัสผ่าน',
  `emp_email` varchar(100) NOT NULL COMMENT 'อีเมล',
  `emp_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'สถานะ 1 = ใช้งานได้, 0 = บล็อค',
  `emp_time_create` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วัน เวลาสร้าง',
  `emp_time_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลาแก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ข้อมูลพนักงาน';

--
-- Dumping data for table `bs_employees`
--

INSERT INTO `bs_employees` (`emp_id`, `emp_profile`, `emp_fname`, `emp_lname`, `emp_username`, `emp_password`, `emp_email`, `emp_status`, `emp_time_create`, `emp_time_update`) VALUES
(27, 'img_66ee773c8c754fea85fec392d4f0d8e101726904124.png', 'ผู้ดูแลระบบ', 'สูงสุด', 'superAdmin', '$2y$10$ufwPgUYdZqkLK/3DtpPYT.U5InDVr8/JqNScvsUMLbe5qzmuRq8S6', 'Adisak@example.com', 1, '2024-09-21 07:30:03', '2024-09-21 07:40:31'),
(29, 'img_66ee7d15c06c3fb30f3d1552a35381c001726905621.png', 'เจ้าของร้าน', 'ทดสอบหนึ่ง', 'owner1', '$2y$10$azj28NHG0nAuGSeW5AHdWu0ym1Mr1Z78IoCX9tnt.K3ske/o0nWB6', 'owner1@gmail.com', 1, '2024-09-21 08:00:21', '2024-09-21 08:00:21'),
(31, 'img_66ee7f524482d69dfd3b5191ab40466c31726906194.png', 'ผู้ดูแลระบบ', 'ทดสอบหนึ่ง', 'admin1', '$2y$10$kVcpeRjaiKzomiaWaaIK0OlBhA28ICHW63DaT/EmtdY//Ikww0M1y', 'admin1@example.com', 1, '2024-09-21 08:07:13', '2024-09-21 08:09:54'),
(34, 'img_66ee8351156a15767de6c598458e46d731726907217.png', 'พนักงาน', 'ทดสอบหนึ่ง', 'employee1', '$2y$10$ePJiv7Mb0JxMXyNlQL3qeu9Iab6SXO2PfUCPuOBYhbfiwbxqsuez.', 'employee1@gmail.com', 1, '2024-09-21 08:26:57', '2024-09-22 10:02:38');

-- --------------------------------------------------------

--
-- Table structure for table `bs_employees_authority`
--

CREATE TABLE `bs_employees_authority` (
  `ea_id` int(11) NOT NULL COMMENT 'รหัสรายการ',
  `emp_id` int(11) NOT NULL COMMENT 'รหัสพนักงาน',
  `eat_id` int(11) NOT NULL COMMENT 'รหัสประเภทสิทธิ์',
  `ea_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลา'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='สิทธิ์พนักงานที่มีแต่ละคน';

--
-- Dumping data for table `bs_employees_authority`
--

INSERT INTO `bs_employees_authority` (`ea_id`, `emp_id`, `eat_id`, `ea_time`) VALUES
(186, 27, 1, '2024-09-21 07:30:03'),
(190, 29, 2, '2024-09-21 08:00:21'),
(192, 31, 3, '2024-09-21 08:07:13'),
(208, 34, 6, '2024-09-22 10:02:38');

-- --------------------------------------------------------

--
-- Table structure for table `bs_employees_authority_type`
--

CREATE TABLE `bs_employees_authority_type` (
  `eat_id` int(11) NOT NULL COMMENT 'รหัสสิทธิ์',
  `eat_name` varchar(100) NOT NULL COMMENT 'ชื่อสิทธิ์',
  `eat_detail` varchar(100) NOT NULL COMMENT 'รายละเอียด',
  `eat_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'สถานะ 1 = แสดง, 0 = ไม่แสดง',
  `eat_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลา'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ข้อมูลประเภทสิทธิ์พนักงาน';

--
-- Dumping data for table `bs_employees_authority_type`
--

INSERT INTO `bs_employees_authority_type` (`eat_id`, `eat_name`, `eat_detail`, `eat_status`, `eat_time`) VALUES
(1, 'Super Admin', 'เจ้าของระบบ', 1, '2024-09-21 06:51:36'),
(2, 'Owner', 'เจ้าของร้าน / ผู้บริหาร', 1, '2024-09-21 06:51:36'),
(3, 'Admin', 'ผู้ดูแลระบบ', 1, '2024-09-21 06:51:36'),
(4, 'Accounting', 'พนักงานบัญชี', 1, '2024-09-21 06:51:36'),
(5, 'Sale', 'พนักงานขาย', 1, '2024-09-21 06:51:36'),
(6, 'Employee', 'พนักงานทั่วไป', 1, '2024-09-21 06:51:36');

-- --------------------------------------------------------

--
-- Table structure for table `bs_members`
--

CREATE TABLE `bs_members` (
  `mem_id` int(11) NOT NULL COMMENT 'รหัสสมาชิก',
  `mem_fname` varchar(50) NOT NULL COMMENT 'ชื่อสมาชิก',
  `mem_lname` varchar(50) NOT NULL COMMENT 'นามสกุลสมาชิก',
  `mem_coin` int(11) NOT NULL DEFAULT 0 COMMENT 'เหรียญที่มี',
  `mem_username` varchar(50) NOT NULL COMMENT 'ชื่อผู้ใช้สมาชิก',
  `mem_password` varchar(255) NOT NULL COMMENT 'รหัสผ่านสมาชิก',
  `mem_email` varchar(100) NOT NULL COMMENT 'อีเมลสมาชิก',
  `mem_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'สถานะ 1 = ใช้งานได้, 0 = ระงับ',
  `mem_profile` varchar(100) NOT NULL COMMENT 'โปรไฟล์สมาชิก',
  `mem_time_create` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วัน เวลาสร้าง',
  `mem_time_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลาอัปเดท'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ข้อมูลสมาชิก';

--
-- Dumping data for table `bs_members`
--

INSERT INTO `bs_members` (`mem_id`, `mem_fname`, `mem_lname`, `mem_coin`, `mem_username`, `mem_password`, `mem_email`, `mem_status`, `mem_profile`, `mem_time_create`, `mem_time_update`) VALUES
(5, 'สมาชิก', 'ทดสอบหนึ่ง', 18, 'member1', '$2y$10$ulIWRfJQWZay4P3SPEqeueFA4BW/a5xAvNXZvrUW8iCfwLJirznTC', 'member1@gmail.com', 1, 'img_66ee84baab2fe957815281aceda48be201726907578.png', '2024-09-21 08:28:45', '2024-09-22 06:26:17'),
(7, 'สมาชิกสอง', 'ทดสอบสอง', 8, 'member2', '$2y$10$FG1D.4x4AAMkgL7f1tu6LOppu8raHi25GLhf7NeYv58RVlXFwE8Wa', 'member2@example.com', 1, 'img_66efb82a23e619f1a60832a7ad5aac4141726986282.png', '2024-09-22 06:24:42', '2024-09-22 10:44:23');

-- --------------------------------------------------------

--
-- Table structure for table `bs_members_address`
--

CREATE TABLE `bs_members_address` (
  `addr_id` int(11) NOT NULL COMMENT 'รหัสรายการ',
  `mem_id` int(11) NOT NULL COMMENT 'รหัสสมาชิก',
  `addr_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'ประเภทที่อยู่ 1 = บ้าน, 2 = ที่ทำงาน',
  `addr_fname` varchar(50) NOT NULL COMMENT 'ชื่อ',
  `addr_lname` varchar(50) NOT NULL COMMENT 'นามสกุล',
  `addr_phone` varchar(15) NOT NULL COMMENT 'เบอร์โทร',
  `addr_province` varchar(100) NOT NULL COMMENT 'จังหวัด',
  `addr_district` varchar(100) NOT NULL COMMENT 'อำเภอ/เขต',
  `addr_subdistrict` varchar(100) NOT NULL COMMENT 'ตำบล/แขวง',
  `addr_zip_code` int(10) NOT NULL COMMENT 'รหัสไปรษณีย์',
  `addr_detail` text NOT NULL COMMENT 'รายละเอียด',
  `addr_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'สถานะการแสดง 1 = แสดง, 0 = ไม่แสดง',
  `addr_time_create` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วัน เวลาสร้าง',
  `addr_time_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลาแก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ข้อมูลที่อยู่สมาชิก';

--
-- Dumping data for table `bs_members_address`
--

INSERT INTO `bs_members_address` (`addr_id`, `mem_id`, `addr_type`, `addr_fname`, `addr_lname`, `addr_phone`, `addr_province`, `addr_district`, `addr_subdistrict`, `addr_zip_code`, `addr_detail`, `addr_status`, `addr_time_create`, `addr_time_update`) VALUES
(6, 5, 1, 'สมาชิก', 'ทดสอบหนึ่ง', '0844566021', 'กรุงเทพมหานคร', 'เขตหนองจอก', 'คลองสิบสอง', 10530, 'บ้านเลขที่ 15/18, ซอย 74', 1, '2024-09-22 03:57:07', '2024-09-22 04:43:39');

-- --------------------------------------------------------

--
-- Table structure for table `bs_members_history_coins`
--

CREATE TABLE `bs_members_history_coins` (
  `mhc_id` int(11) NOT NULL COMMENT 'รหัสรายการ',
  `mhc_from_mem_id` int(11) DEFAULT NULL COMMENT 'รหัสสมาชิกผู้ส่ง (null = ส่งจากระบบ)',
  `mhc_to_mem_id` int(11) DEFAULT NULL COMMENT 'รหัสสมาชิกผู้รับ (null = ใช้เป็นส่วนลด)',
  `mhc_coin_amount` int(11) NOT NULL COMMENT 'จำนวนเหรียญ',
  `mhc_transaction_type` enum('transfer','purchase','discount','refund') NOT NULL COMMENT 'ประเภทรายการ',
  `ord_id` int(11) DEFAULT NULL COMMENT 'รหัสรายการสั่งซื้อ (null = ไม่ได้ซื้อ)',
  `mhc_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'สถานะ 1 = แสดง, 0 = ไม่แสดง',
  `mhc_time` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วัน เวลา ที่ทำรายการ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ข้อมูลประวัติเหรียญ';

--
-- Dumping data for table `bs_members_history_coins`
--

INSERT INTO `bs_members_history_coins` (`mhc_id`, `mhc_from_mem_id`, `mhc_to_mem_id`, `mhc_coin_amount`, `mhc_transaction_type`, `ord_id`, `mhc_status`, `mhc_time`) VALUES
(22, NULL, 5, 27, 'purchase', 24, 1, '2024-09-22 06:07:39'),
(23, 5, 7, 10, 'transfer', NULL, 1, '2024-09-22 06:26:17'),
(24, 7, NULL, 5, 'discount', 25, 1, '2024-09-22 10:41:54'),
(25, NULL, 7, 3, 'purchase', 25, 1, '2024-09-22 10:44:23');

-- --------------------------------------------------------

--
-- Table structure for table `bs_members_wishlist`
--

CREATE TABLE `bs_members_wishlist` (
  `mwl_id` int(11) NOT NULL COMMENT 'รหัสรายการ',
  `mem_id` int(11) NOT NULL COMMENT 'รหัสสมาชิก',
  `prd_id` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `mwl_time` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วัน เวลาที่เพิ่ม'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ข้อมูลสินค้าที่อยากได้';

--
-- Dumping data for table `bs_members_wishlist`
--

INSERT INTO `bs_members_wishlist` (`mwl_id`, `mem_id`, `prd_id`, `mwl_time`) VALUES
(22, 5, 125, '2024-09-22 04:23:42');

-- --------------------------------------------------------

--
-- Table structure for table `bs_orders`
--

CREATE TABLE `bs_orders` (
  `ord_id` int(11) NOT NULL COMMENT 'รหัสรายการสั่งซื้อ',
  `mem_id` int(11) DEFAULT NULL COMMENT 'รหัสสมาชิก',
  `ord_coins_discount` int(11) NOT NULL COMMENT 'เหรียญที่ใช้เป็นส่วนลด',
  `ord_coins_earned` int(11) NOT NULL COMMENT 'เหรียญที่ได้รับ',
  `ord_price` decimal(10,2) NOT NULL COMMENT 'ราคาสุทธิ',
  `ord_tracking_number` varchar(50) DEFAULT NULL COMMENT 'หมายเลขติดตามพัสดุ',
  `ord_status` enum('Pending Payment','Under Review','Payment Retry','Awaiting Shipment','Shipped','Completed','Cancelled') NOT NULL DEFAULT 'Pending Payment' COMMENT 'สถานะการสั่งซื้อ (รอชำระเงิน = Pending Payment, รอตรวจสอบ = Under Review, ชำระเงินใหม่ = Payment Retry, รอจัดส่ง = Awaiting Shipment, จัดส่งแล้ว = Shipped, สำเร็จ = Completed,\r\nยกเลิก = Cancelled',
  `ord_time_create` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วัน เาลา สั่งซื้อ',
  `ord_time_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เาลา แก้ไขสั่งซื้อ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ข้อมูลรายการสั่งซื้อ';

--
-- Dumping data for table `bs_orders`
--

INSERT INTO `bs_orders` (`ord_id`, `mem_id`, `ord_coins_discount`, `ord_coins_earned`, `ord_price`, `ord_tracking_number`, `ord_status`, `ord_time_create`, `ord_time_update`) VALUES
(24, 5, 0, 27, 653.25, '7893541258', 'Completed', '2024-09-22 04:12:36', '2024-09-22 06:07:39'),
(25, 7, 5, 3, 191.98, '775849911111111', 'Completed', '2024-08-01 10:41:53', '2024-08-03 10:44:23');

-- --------------------------------------------------------

--
-- Table structure for table `bs_orders_address`
--

CREATE TABLE `bs_orders_address` (
  `oad_id` int(11) NOT NULL COMMENT 'รหัสที่อยู่จัดส่ง',
  `ord_id` int(11) NOT NULL COMMENT 'รหัสรายการสั่งซื้อ',
  `addr_id` int(11) DEFAULT NULL,
  `oad_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'ประเภทที่อยู่ 1 = บ้าน, 2 = ที่ทำงาน',
  `oad_fname` varchar(50) NOT NULL COMMENT 'ชื่อ',
  `oad_lname` varchar(50) NOT NULL COMMENT 'นามสกุล',
  `oad_phone` varchar(15) NOT NULL COMMENT 'เบอร์โทร',
  `oad_province` varchar(100) NOT NULL COMMENT 'จังหวัด',
  `oad_district` varchar(100) NOT NULL COMMENT 'อำเภอ/เขต',
  `oad_subdistrict` varchar(100) NOT NULL COMMENT 'ตำบล/แขวง',
  `oad_zip_code` int(10) NOT NULL COMMENT 'รหัสไปรษณีย์',
  `oad_detail` text NOT NULL COMMENT 'รายละเอียด',
  `oad_time_create` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วัน เวลาสร้าง',
  `oad_time_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลาแก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ข้อมูลที่อยู่จัดส่ง';

--
-- Dumping data for table `bs_orders_address`
--

INSERT INTO `bs_orders_address` (`oad_id`, `ord_id`, `addr_id`, `oad_type`, `oad_fname`, `oad_lname`, `oad_phone`, `oad_province`, `oad_district`, `oad_subdistrict`, `oad_zip_code`, `oad_detail`, `oad_time_create`, `oad_time_update`) VALUES
(17, 24, 6, 1, 'สมาชิก', 'ทดสอบหนึ่ง', '0844566021', 'กรุงเทพมหานคร', 'เขตหนองจอก', 'คลองสิบสอง', 10530, 'บ้านเลขที่ 15/18, ซอย 74', '2024-09-22 04:12:36', '2024-09-22 04:12:36'),
(18, 25, NULL, 2, 'สมาชิกทดสอบสอง', 'ทดสอบสอง', '0265781235', 'นครนายก', 'ปากพลี', 'โคกกรวด', 26130, 'บ้านเลขที่ 35 , ซอย 4', '2024-09-22 10:41:53', '2024-09-22 10:41:53');

-- --------------------------------------------------------

--
-- Table structure for table `bs_orders_items`
--

CREATE TABLE `bs_orders_items` (
  `oit_id` int(11) NOT NULL COMMENT 'รหัสสินค้าในรายการสั่งซื้อ',
  `ord_id` int(11) NOT NULL COMMENT 'รหัสรายการสั่งซื้อ',
  `prd_id` int(11) DEFAULT NULL COMMENT 'รหัสสินค้า',
  `oit_name` varchar(100) NOT NULL COMMENT 'ชื่อสินค้า',
  `oit_coin` int(11) NOT NULL COMMENT 'เหรียญ',
  `oit_quantity` int(11) NOT NULL COMMENT 'จำนวนที่ซื้อ',
  `oit_price` decimal(10,2) NOT NULL COMMENT 'ราคาสินค้า',
  `oit_percent_discount` int(11) NOT NULL COMMENT 'ส่วนลด %',
  `oit_time_create` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วัน เวลา สร้าง',
  `oit_time_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลา แก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='สินค้าในรายการสั่งซื้อ';

--
-- Dumping data for table `bs_orders_items`
--

INSERT INTO `bs_orders_items` (`oit_id`, `ord_id`, `prd_id`, `oit_name`, `oit_coin`, `oit_quantity`, `oit_price`, `oit_percent_discount`, `oit_time_create`, `oit_time_update`) VALUES
(28, 24, 126, 'THE GREATEST VERSION OF MINE ตำราการพัฒนาตัวเองที่ใช้ได้ ', 15, 1, 450.00, 0, '2024-09-22 04:12:36', '2024-09-22 04:12:36'),
(29, 24, 122, 'ครอบครัวตึ๋งหนืด37 เศรษฐีออนไลน์', 6, 2, 185.00, 50, '2024-09-22 04:12:36', '2024-09-22 04:12:36'),
(30, 25, 121, 'เรื่องวุ่นๆของวัยรุ่นมือใหม่:ฮอร์โมน ร่างกาย ใจว้าวุ่น', 3, 1, 195.00, 10, '2024-09-22 10:41:53', '2024-09-22 10:41:53');

-- --------------------------------------------------------

--
-- Table structure for table `bs_orders_payments`
--

CREATE TABLE `bs_orders_payments` (
  `opm_id` int(11) NOT NULL COMMENT 'รหัสรายการ',
  `ord_id` int(11) NOT NULL COMMENT 'รหัสรายการสั่งซื้อ',
  `pmt_id` int(11) DEFAULT NULL COMMENT 'รหัสช่องทางชำระ',
  `opm_bank` varchar(100) NOT NULL COMMENT 'ธนาคารช่องทางชำระ',
  `opm_name` varchar(100) NOT NULL COMMENT 'ชื่อช่องทางชำระ',
  `opm_number` varchar(10) NOT NULL COMMENT 'รหัสช่องทางชำระ',
  `opm_time_create` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วัน เวลา สร้าง',
  `opm_time_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลา แก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ช่องทางชำระเงินของรายการสั่งซื้อ';

--
-- Dumping data for table `bs_orders_payments`
--

INSERT INTO `bs_orders_payments` (`opm_id`, `ord_id`, `pmt_id`, `opm_bank`, `opm_name`, `opm_number`, `opm_time_create`, `opm_time_update`) VALUES
(17, 24, 16, 'ออมสิน', 'นายบัญชี ทดสอบ', '1234567890', '2024-09-22 04:12:36', '2024-09-22 04:12:36'),
(18, 25, 17, 'กสิกร', 'นายกสิกร ทดสอบโอน', '1145789302', '2024-09-22 10:41:53', '2024-09-22 10:41:53');

-- --------------------------------------------------------

--
-- Table structure for table `bs_orders_promotions`
--

CREATE TABLE `bs_orders_promotions` (
  `opm_id` int(11) NOT NULL COMMENT 'รหัสรายการโปรโมชั่นที่ใช้',
  `ord_id` int(11) NOT NULL COMMENT 'รหัสรายการสั่งซื้อ',
  `pro_id` int(11) DEFAULT NULL COMMENT 'รหัสโปรโมชั่น',
  `opm_name` varchar(100) NOT NULL COMMENT 'ชื่ิอโปรโมชั่น',
  `opm_percent_discount` int(3) NOT NULL COMMENT 'ส่วนลด %',
  `opm_time_create` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วัน เวลา สร้าง',
  `opm_time_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลา แก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='โปรโมชั่นที่ใช้สั่งซื้อ';

--
-- Dumping data for table `bs_orders_promotions`
--

INSERT INTO `bs_orders_promotions` (`opm_id`, `ord_id`, `pro_id`, `opm_name`, `opm_percent_discount`, `opm_time_create`, `opm_time_update`) VALUES
(7, 24, 5, 'ลดฤดูร้อน', 5, '2024-09-22 04:12:36', '2024-09-22 04:12:36'),
(8, 25, 5, 'ลดฤดูร้อน', 5, '2024-09-22 10:41:53', '2024-09-22 10:41:53');

-- --------------------------------------------------------

--
-- Table structure for table `bs_orders_shippings`
--

CREATE TABLE `bs_orders_shippings` (
  `osp_id` int(11) NOT NULL COMMENT 'รหัสรายการ',
  `ord_id` int(11) NOT NULL COMMENT 'รหัสรายการสั่งซื้อ',
  `shp_id` int(11) DEFAULT NULL COMMENT 'รหัสช่องทางขนส่ง',
  `osp_name` varchar(100) NOT NULL COMMENT 'ชื่อช่องทางขนส่ง',
  `osp_price` decimal(10,2) NOT NULL COMMENT 'ราคาช่องทางขนส่ง',
  `osp_time_create` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วัน เวลา สร้าง',
  `osp_time_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลา แก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ช่องทางขนส่งของรายการสั่งซื้อ';

--
-- Dumping data for table `bs_orders_shippings`
--

INSERT INTO `bs_orders_shippings` (`osp_id`, `ord_id`, `shp_id`, `osp_name`, `osp_price`, `osp_time_create`, `osp_time_update`) VALUES
(17, 24, 11, 'Kerry', 50.00, '2024-09-22 04:12:36', '2024-09-22 04:12:36'),
(18, 25, 13, 'Flash Express', 30.00, '2024-09-22 10:41:53', '2024-09-22 10:41:53');

-- --------------------------------------------------------

--
-- Table structure for table `bs_orders_slips`
--

CREATE TABLE `bs_orders_slips` (
  `osl_id` int(11) NOT NULL COMMENT 'รหัสรายการ',
  `ord_id` int(11) NOT NULL COMMENT 'รหัสรายการสั่งซื้อ',
  `mem_id` int(11) DEFAULT NULL COMMENT 'รหัสสมาชิกผู้ชำระ',
  `osl_slip` varchar(100) NOT NULL COMMENT 'รูปสลิปหลักฐานโอนเงืน',
  `osl_time` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วัน เวลา'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='หลักฐานการชำระเงิน';

--
-- Dumping data for table `bs_orders_slips`
--

INSERT INTO `bs_orders_slips` (`osl_id`, `ord_id`, `mem_id`, `osl_slip`, `osl_time`) VALUES
(7, 24, 5, 'img_66ef9bb1d81265a76a2958f6ea695c00f1726978993.jpg', '2024-09-22 04:23:13'),
(8, 25, 7, 'img_66eff4be0706a48ad22701b3ffe805b841727001790.jpg', '2024-09-22 10:43:10');

-- --------------------------------------------------------

--
-- Table structure for table `bs_payments`
--

CREATE TABLE `bs_payments` (
  `pmt_id` int(11) NOT NULL COMMENT 'รหัสรายการ',
  `pmt_bank` varchar(100) NOT NULL COMMENT 'ชื่อธนาคาร',
  `pmt_bank_logo` varchar(100) DEFAULT NULL COMMENT 'โลโก้ ธนาคาร',
  `pmt_qrcode` varchar(100) NOT NULL COMMENT 'QR Code ธนาคาร',
  `pmt_name` varchar(100) NOT NULL COMMENT 'ชื่อบัญชี',
  `pmt_number` varchar(10) NOT NULL COMMENT 'รหัสบัญชี',
  `pmt_detail` text NOT NULL COMMENT 'รายละเอียด',
  `pmt_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'สถานะ 1 = แสดง, 0 = ไม่แสดง',
  `pmt_time_create` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วัน เวลา สร้าง',
  `pmt_time_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลา แก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ข้อมูลช่องทางชำระเงิน';

--
-- Dumping data for table `bs_payments`
--

INSERT INTO `bs_payments` (`pmt_id`, `pmt_bank`, `pmt_bank_logo`, `pmt_qrcode`, `pmt_name`, `pmt_number`, `pmt_detail`, `pmt_status`, `pmt_time_create`, `pmt_time_update`) VALUES
(16, 'ออมสิน', 'img_66ef9213d4f84212de62138e07aea73c21726976531.png', 'img_66ef9213d6518595da9116a0eadbdaec41726976531.png', 'นายบัญชี ทดสอบ', '1234567890', 'บัญชีหลัก', 1, '2024-09-22 03:34:58', '2024-09-22 03:42:11'),
(17, 'กสิกร', 'img_66ef9244e9529727487579fcfba9061121726976580.png', 'img_66ef921ceb2a746fbc7abf189bcb8b0601726976540.png', 'นายกสิกร ทดสอบโอน', '1145789302', 'กสิกร\r\n', 1, '2024-09-22 03:35:55', '2024-09-22 03:43:00'),
(18, 'ทรูมันนี่วอลเล็ท', 'img_66ef9393c4d13713b5cdf1ba92762219d1726976915.png', 'img_66ef9393c63c45efe87732991d1e712961726976915.png', 'นายทรูมันนี่ วอลเล็ท', '0258741235', 'ทรูมันนี่วอลเล็ท', 1, '2024-09-22 03:48:12', '2024-09-22 03:48:35');

-- --------------------------------------------------------

--
-- Table structure for table `bs_products`
--

CREATE TABLE `bs_products` (
  `prd_id` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `prd_name` varchar(100) NOT NULL COMMENT 'ชื่อสินค้า',
  `prd_img1` varchar(100) NOT NULL COMMENT 'รูปภาพหลัก',
  `prd_img2` varchar(100) DEFAULT NULL COMMENT 'รูปภาพรอง',
  `prd_isbn` varchar(13) NOT NULL COMMENT 'รหัส ISBN',
  `prd_coin` int(11) NOT NULL DEFAULT 0 COMMENT 'จำนวนเหรียญ',
  `prd_quantity` int(11) NOT NULL COMMENT 'จำนวนสินค้า',
  `prd_number_pages` int(11) NOT NULL COMMENT 'จำนวนหน้า',
  `prd_detail` text DEFAULT 'ไม่พบรายละเอียดสินค้า' COMMENT 'รายละอียดสินค้า',
  `prd_price` decimal(10,2) NOT NULL COMMENT 'ราคาสินค้า',
  `prd_percent_discount` int(11) NOT NULL DEFAULT 0 COMMENT 'เปอร์เซ็นส่วนลดสินค้า',
  `pty_id` int(11) NOT NULL COMMENT 'รหัสประเภทสินค้า',
  `pub_id` int(11) NOT NULL COMMENT 'รหัสสำนักพิมพิ์',
  `auth_id` int(11) NOT NULL COMMENT 'รหัสผู้แต่ง',
  `prd_preorder` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = ปกติ, 0 = Pre Order',
  `prd_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'สถานะ 1 = แสดง, 0 = ไม่แสดง',
  `prd_time_create` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วัน เวลาสร้าง',
  `prd_time_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลาแก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ข้อมูลสินค้า';

--
-- Dumping data for table `bs_products`
--

INSERT INTO `bs_products` (`prd_id`, `prd_name`, `prd_img1`, `prd_img2`, `prd_isbn`, `prd_coin`, `prd_quantity`, `prd_number_pages`, `prd_detail`, `prd_price`, `prd_percent_discount`, `pty_id`, `pub_id`, `auth_id`, `prd_preorder`, `prd_status`, `prd_time_create`, `prd_time_update`) VALUES
(121, 'เรื่องวุ่นๆของวัยรุ่นมือใหม่:ฮอร์โมน ร่างกาย ใจว้าวุ่น', 'img_66eeb32e3152a1b3295969111f6b212fb1726919470.jpg', 'img_66eeb32e325bf9260028ad6aa365bfaa61726919470.jpg', '9786160468928', 3, 29, 192, '<p style=\"text-align:justify;\"><span style=\"color:rgb(0,0,0);font-family:THSarabun, Arial, sans-serif;font-size:13px;\">&nbsp;&nbsp; ช่วงเวลาที่ร่างกายและจิตใจเติบโตและเปลี่ยนผ่านจากวัยเด็กเข้าสู่วัยรุ่นอาจทำให้น้อง ๆ รู้สึกขัดเขิน วิตกกังวล รวมทั้งอยากรู้อยากเห็นเรื่องเพศแต่ไม่กล้าถามใคร ผู้เชี่ยวชาญจากศูนย์การเรียนรู้และให้คำปรึกษาเรื่องเพศศึกษาเพื่อเยาวชน \"อะฮ่า\" ประเทศเกาหลีใต้ (Aha Sexuality Education &amp; Counseling Center for Youth หรือ Aha! Center) จึงรวบรวมคำถามที่วัยรุ่นส่วนใหญ่อยากรู้ พร้อมคำตอบจากผู้เชี่ยวชาญเพื่อให้น้อง ๆ ได้เรียนรู้เรื่องเพศศึกษาอย่างถูกต้อง มีทัศนคติที่ดีเกี่ยวกับเรื่องเพศ เข้าใจการเปลี่ยนแปลงที่ตัวเองกำลังเผชิญอยู่ และใช้ชีวิตวัยรุ่นได้อย่างปลอดภัยและมีความสุข</span></p>', 195.00, 10, 17, 10, 21, 1, 1, '2024-09-21 11:48:35', '2024-09-22 10:41:53'),
(122, 'ครอบครัวตึ๋งหนืด37 เศรษฐีออนไลน์', 'img_66eeb3ee1ded3e6339834e853fd7f2bdf1726919662.jpg', 'img_66eeb3ee1fc17f289021e0ac9cec9ac5f1726919662.jpg', '9786160469215', 6, 68, 184, '<p style=\"text-align:justify;\"><span style=\"color:rgb(0,0,0);font-family:THSarabun, Arial, sans-serif;font-size:13px;\">&nbsp; ในยุคนี้ใครๆ ก็อยากร่ำรวยจากการขายของออนไลน์ว่าแต่ไม่ใช่เรื่องง่ายเลย เพราะไม่ใช่ทุกอย่างที่ขายดี แต่ไม่ต้องห่วง ครอบครัวตึ๋งหนืดจะมาสอนวิธีให้เคล็ดลับขายได้ ขายดี ลงทุนน้อย กำไรงามแบบตึ๋งหนืด พร้อมด้วยเทคนิคการประหยัดแบบสุดตืดสุดฮา&nbsp;</span></p>', 185.00, 50, 17, 10, 22, 1, 1, '2024-09-21 11:53:21', '2024-09-22 04:12:36'),
(123, 'ปล่อยแม่มดคนนั้นซะ เล่ม 22', 'img_66eeb507a8e43ff8ce5d4ed1e2da62a6d1726919943.jpg', 'img_66eeb507a9ffd53132c841e2c651c58881726919943.jpg', '9786160632190', 10, 100, 336, '<p style=\"text-align:justify;\"><span style=\"color:rgb(0,0,0);font-family:THSarabun, Arial, sans-serif;font-size:13px;\">\'เดิมทีโรแลนด์มองว่าโลกแห่งความฝันเป็นเหมือนคลังความรู้ไม่นึกเลยว่าพักนี้มันจะสร้างความวุ่นวายให้เขาไม่เว้นวันหลังการกัดกินที่สะเทือนเลื่อนลั่นไปทั่วเมืองปริซึมโรแลนด์ก็ได้พบกับแขกที่มาเยือนโดยไม่คาดฝันอีกฝ่ายหน้าตาเหมือนปีศาจในเศษเสี้ยวความทรงจำเกือบทุกประการจะขาดก็แค่ดวงตาที่สามเท่านั้นแต่ยังไม่ทันสืบสาวจนพบคำตอบ เขาก็ได้เจอกับเทวทูตที่มิสต์เคยพูดถึง!เวลานี้สถานการณ์ฝั่งโลกแห่งความจริงก็ไม่ได้ดีไปกว่ากันภารกิจขนย้ายผู้อพยพจากต่างแดนที่ดูแล้วไม่ซับซ้อนกลับต้องติดขัดสาเหตุก็เพราะหมอกสีแดงได้คืบคลานเข้ามาในดินแดนมนุษย์แล้ว...</span></p>', 439.00, 30, 16, 11, 23, 1, 1, '2024-09-21 11:57:19', '2024-09-21 11:59:03'),
(124, 'มาสเตอร์กับมาร์การิตา THE MASTER AND MARGARITA', 'img_66eeb5d2612a703bb6a343ede95febb591726920146.jpg', 'img_66eeb5d2623649b098a48ede7d00fde6b1726920146.jpg', '9786167691749', 0, 25, 472, '<p style=\"text-align:justify;\"><span style=\"color:rgb(0,0,0);font-family:THSarabun, Arial, sans-serif;font-size:13px;\">บ่ายวันหนึ่งในฤดูใบไม้ผลิ ซาตานปรากฏกายขึ้นในกรุงมอสโก</span><br><span style=\"color:rgb(0,0,0);font-family:THSarabun, Arial, sans-serif;font-size:13px;\">พร้อมกับพวกพ้องสร้างความโกลาหล แตกตื่น และเปลี่ยนวัน</span><br><span style=\"color:rgb(0,0,0);font-family:THSarabun, Arial, sans-serif;font-size:13px;\">ธรรมดาสามัญ ให้กลายเป็นวันมืดมิดที่สุดของรัสเซีย \"อีวาน</span><br><span style=\"color:rgb(0,0,0);font-family:THSarabun, Arial, sans-serif;font-size:13px;\">เบซดอมนี\" ถูกจับส่งตัวเข้าโรงพยาบาลบ้า หลังจากนั้น \"อีวาน\"</span><br><span style=\"color:rgb(0,0,0);font-family:THSarabun, Arial, sans-serif;font-size:13px;\">ได้พบกับ \"มาสเตอร์\" ชายผู้อุทิศตนให้กับความจริงและได้รับฟัง</span><br><span style=\"color:rgb(0,0,0);font-family:THSarabun, Arial, sans-serif;font-size:13px;\">เรื่องราวของ \"มาร์การิตา\" หญิงผู้อุทิศตนให้กับความรัก</span></p>', 350.00, 3, 16, 12, 24, 1, 1, '2024-09-21 12:01:06', '2024-09-21 12:02:26'),
(125, 'ชุด ปรมาจารย์ลัทธิมาร การ์ตูน 2-3+ของแถม', 'img_66eeb6f07c9b3723ba80292cd5dc7f2c31726920432.jpg', NULL, '5524300003301', 0, 15, 0, '<p style=\"text-align:justify;\"><span style=\"color:rgb(0,0,0);font-family:THSarabun, Arial, sans-serif;font-size:13px;\">&nbsp;เซ็ตพรีเมี่ยม หนังสือ เล่ม&nbsp;2-3&nbsp;ชริงค์ฟิล์มแยกเล่ม + แม่เหล็กขนาด&nbsp;13x18&nbsp;ซม.&nbsp;1&nbsp;แผ่น</span><br><br><span style=\"color:rgb(0,0,0);font-family:THSarabun, Arial, sans-serif;font-size:13px;\">เมื่อสิบสามปีก่อน เว่ยอู๋เซี่ยน...ต้นกำเนิดจอมมาร ถูกบรรดาตระกูลใหญ่ฝ่ายธรรมะปิดล้อมปราบจนสิ้นชื่อ บัดนี้เขากลับถูกอัญเชิญวิญญาณเข้าร่างโม่เสวียนอวี่ด้วยอาคมต้องห้าม แต่ต้องแลกกับการฆ่าล้างตระกูลโม่เพื่อแก้แค้นแทนเจ้าของร่างเดิม ทว่าเหตุการณ์เกิดพลิกผันเมื่อมือผีประหลาดโจมตีบ้านสกุลโม่ เพื่อปกป้องยุวชนที่เพิ่งออกท่องยุทธจักร บีบให้เว่ยอู๋เซี่ยนต้องใช้วิชามารอย่างหลีกเลี่ยงไม่ได้ มิหนำซ้ำหลานวั่งจี...สหายเก่า ซึ่งมีความหลังร่วมกัน กลับปรากฏตัวขึ้นมาอย่างประจวบเหมาะ ฐานะของเขาจะถูกเปิดโปงหรือไม่!?</span></p>', 878.00, 5, 18, 13, 25, 0, 1, '2024-09-21 12:06:14', '2024-09-22 07:54:28'),
(126, 'THE GREATEST VERSION OF MINE ตำราการพัฒนาตัวเองที่ใช้ได้ ', 'img_66eeb8ecba7c0fd46e6773eb3a0d5cf861726920940.jpg', 'img_66eeb8ecbb91ef59d2531da0b72fd177f1726920940.jpg', '9786169456803', 15, 89, 288, '<p style=\"text-align:justify;\"><span style=\"color:rgb(0,0,0);font-family:THSarabun, Arial, sans-serif;font-size:13px;\">&nbsp;&nbsp; นอกจากเรื่องราวในหนังสือที่เน้นในเรื่องของการพัฒนาชีวิต สิ่งที่สำคัญไม่แพ้กันคือ การมองเห็น</span><br><span style=\"color:rgb(0,0,0);font-family:THSarabun, Arial, sans-serif;font-size:13px;\">ถึงนิยามความสำเร็จที่ตนปรารถนา ยิ่งคนมีความรู้ผสมกับแรงบันดาลใจมากเท่าไหร่ คนจะกล้าคิดกล้าทำ กล้าฝันมากขึ้นเท่านั้น และนั่นคือเหตุผลที่หนังสือเล่มนี้ได้ถูกเขียนขึ้นมาครับซึ่งทุกผลลัพธ์แห่งความสำเร็จนั้น เกิดขึ้นได้จากศักยภาพของเราทั้งสิ้น และแนวทางการสร้างศักยภาพเหล่านั้น</span><br><span style=\"color:rgb(0,0,0);font-family:THSarabun, Arial, sans-serif;font-size:13px;\">อยู่ในหนังสือ The greatest version of mineไว้มาเจอผมในเนื้อหากันนะครับ</span></p>', 450.00, 0, 19, 14, 26, 0, 1, '2024-09-21 12:13:47', '2024-09-22 04:12:36'),
(127, 'สุดสัปดาห์ Special คนหล่อฯปี17/Mark Tuan', 'img_66eebc6a308cc865d28aea695c4ac165b1726921834.jpg', NULL, '9770857146053', 0, 100, 182, '<p>ไม่พบรายละเอียดสินค้า</p>', 399.00, 0, 20, 15, 27, 1, 1, '2024-09-21 12:21:54', '2024-09-21 12:30:34'),
(128, 'ร้านชำอิงอิง ที่พักพิงสำหรับผู้อ่อนล้า', 'img_66eebcb69aeb6fedf633fc432d49ac00f1726921910.jpg', NULL, '9786161864934', 9, 100, 397, '<p style=\"margin-left:0px;\">แด่เหล่าผู้ล้มเหลว</p><p style=\"margin-left:0px;\">ที่ใช้ชีวิตอย่างไร้จุดหมายในโลกกว้างใหญ่นี้</p><p style=\"margin-left:0px;\">.</p><p style=\"margin-left:0px;\">\"ถ้าการออกไปใช้ชีวิตมันเหนื่อยนัก ก็กลับมาพักดีกว่าไหม\" หลิวสือซานเติบโตในร้านขายของชำเมืองอวิ๋นเปียนกับยายสองคน เมื่อโตขึ้นเขาย้ายจากชนบทไปยังเมืองหลวงเพื่อตามหาความสำเร็จ แต่กลับล้มเหลวไปเสียทุกอย่าง ไม่ว่าจะเป็นเรื่องเรียน การงาน หรือความรัก</p><p style=\"margin-left:0px;\">.</p><p style=\"margin-left:0px;\">วันหนึ่งหลังจากเมาแล้วตื่นขึ้นมา เขาพบว่าตัวเองได้กลับมายังบ้านเกิดอีกครั้ง เขาจึงจำต้องใช้ชีวิตอยู่ในเมืองเล็กๆที่ดูเหมือนสงบแห่งนี้ พร้อมกับทำภารกิจที่ดูเป็นไปไม่ได้ให้สำเร็จด้วยความช่วยเหลือจากคนใกล้ตัว โดยที่เขาไม่รู้เลยว่า ความท้าทายครั้งสำคัญในชีวิตกำลังจะมาเยือนแล้ว...</p>', 315.00, 55, 21, 16, 28, 1, 1, '2024-09-21 12:29:39', '2024-09-21 12:31:50'),
(129, 'THE LAST BREAKFAST', 'img_66eebeec7578e9c9ec849e0f5058e9c5d1726922476.jpg', NULL, '9000122124', 5, 40, 192, '<p style=\"text-align:justify;\"><span style=\"color:rgb(0,0,0);font-family:THSarabun, Arial, sans-serif;font-size:13px;\">The Last Breakfast หนังสือนิยายภาพ/กวี/ชีวิต ผลงานเรื่องเล่าแฝงปรัชญาชีวิตอันเปี่ยมไปด้วยชั้นเชิงและจินตนาการจาก “องอาจ ชัยชาญชีพ” ที่จะพาคุณผู้อ่านเข้าไปมีส่วนร่วมในการเดินทางของ ‘ไฮอัง’ ท่ามกลางความฝันประหลาดที่เกิดขึ้นระหว่างมื้อเช้า ผ่านเรื่องราวและเหตุการณ์อันโหดร้ายรุนแรงมากมายที่ถั่งโถมเข้ามาทดสอบความแข็งแกร่งของจิตใจ ความคิด ความกล้าหาญ ความมุ่งมั่น และความศรัทธาที่มี โดยบทสรุปสุดท้ายของการเดินทางอันยาวนานนี้จะเป็นเช่นไรนั้นเป็นสิ่งที่รอให้คุณผู้อ่านได้เป็นผู้ค้นหาคำตอบด้วยตนเอง</span></p>', 378.00, 5, 21, 17, 29, 1, 1, '2024-09-21 12:40:44', '2024-09-21 12:41:16'),
(130, 'ฉันตกลงสงบศึกกับจิตใจ', 'img_66eebf7d7d1ff3e22ebaf6d9febac07ee1726922621.jpg', 'img_66eebf7d7e70f49dec6189b1bf5a385b51726922621.jpg', '9786160468201', 9, 150, 144, '<p><span style=\"color:rgb(0,0,0);font-family:THSarabun, Arial, sans-serif;font-size:13px;\">&nbsp;&nbsp;&nbsp; ชีวิตวุ่นวายเหน็ดเหนื่อยไม่จบสิ้น งานไม่ได้ดั่งใจ ความสัมพันธ์แสนจะยุ่งเหยิง และอีกหลายเรื่องที่ประดังประเดเข้ามา อยากหักห้ามความคิดลบ หรือปรับเปลี่ยนตนเองให้มองโลกในแง่ดีก็ไม่ใช่เรื่องง่าย&nbsp; แต่หนังสือเล่มนี้จะมาบอกคุณเองว่า “การใช้ชีวิตอย่างมีความสุขไม่ใช่เรื่องยาก” ผ่านเรื่องราวของคุณหมอนะกะมุระ สึเนะโกะ จิตแพทย์ผู้ทำงานมานานกว่า 70 ปี&nbsp; เธอได้ถ่ายทอดทั้งทุกข์และสุขในชีวิตตนเอง ผสมผสานกับเรื่องราวของผู้ป่วย ซึ่งล้วนมีปัญหาแตกต่างกัน เมื่ออ่านจบคุณอาจรู้สึกเต็มอิ่มในหัวใจว่าไม่ได้มีเพียงเราที่เผชิญหน้ากับความทุกข์สาหัส&nbsp; สำคัญที่ทำอย่างไรจะใช้ชีวิตได้อย่างมีความสุขต่างหาก เพราะไม่ว่าชีวิตจะเป็นอย่างไร ท้ายที่สุดแล้วคนที่ตัดสินใจก็คือตัวเราเอง จะจดจ่อกับความทุกข์หรือโอบรับความสุข ก็ขึ้นอยู่กับเราทั้งสิ้น หวังว่าหลังอ่านเล่มนี้แล้ว ทุกท่านจะเลือกทางที่ตนเองมีความสุข</span></p>', 265.00, 0, 19, 10, 30, 1, 1, '2024-09-21 12:43:03', '2024-09-21 12:43:41'),
(131, 'บ้านและสวนฉ.พิเศษ บ้านในเมือง อยู่สบายฯ', 'img_66eec0aed61d573f6350cf3812f63cc3e1726922926.jpg', NULL, '9786169454748', 18, 200, 160, '<p><span style=\"color:rgb(0,0,0);font-family:THSarabun, Arial, sans-serif;font-size:13px;\">พื้นที่ในเมืองท้าทายต่อการออกแบบบ้านเพื่อการอยู่อาศัยเสมอ เพราะเต็มไปด้วยข้อจำกัดทางสภาพแวดล้อมมากมายอันจะเอื้อให้บ้านอาศัยอยู่ได้อย่างสบาย หนังสือสองภาษา (ไทย-อังกฤษ) เล่มนี้ จึงต้องการเผยแผ่รูปแบบบ้านและการออกแบบที่อยู่อาศัย ที่พลิกข้อจำกัดของสภาพแวดล้อมในเมืองเป็นแรงบันดาลใจ โดยได้รวบรวมบ้านหลังเล็กในเมืองใหญ่หลายเมือง 10 หลัง ในภูมิภาคอาเซียน ที่ใช้ไอเดียการออกแบบลบข้อจำกัดของที่ตั้งได้อย่างน่าสนใจ เพื่อเป็นแรงบันดาลใจให้กับทั้งผู้อ่านคนไทยและคนต่างชาติ ได้ศึกษาและเป็นแนวทางของการอยู่อาศัยแบบยั่งยืน</span></p>', 525.00, 10, 22, 18, 31, 1, 1, '2024-09-21 12:48:11', '2024-09-21 12:48:46'),
(132, 'The Mediterranean Garden', 'img_66eec17f142b468b1121b177a94f8b8311726923135.jpg', NULL, '9786161861346', 20, 30, 160, '<p><span style=\"color:rgb(0,0,0);font-family:THSarabun, Arial, sans-serif;font-size:13px;\">สวนเมดิเตอร์เรเนียนบรรยากาศธรรมชาติ สไตล์ชนบทฝรั่งเศสและอิตาลี ที่เข้าถึงคนไทย ปลูกพรรณไม้ในท้องถิ่นที่ดูแลง่าย และเหมาะกับสภาพอากาศในเมืองไทย&nbsp; อย่าง โอลีฟ สนไซเปรส&nbsp; ไม้ดอกสีสวย และสมุนไพรฝรั่งที่ส่งกลิ่นหอม เติมพรรณไม้ที่เพิ่มความธรรมชาติ อย่าง หญ้าประดับ และพรรณไม้ในเขตร้อน จำพวกปาล์มต่างๆ และไม้ทนแล้งริมทะเล อย่าง อากาเว่ ยุกก้า อโล และไม้อวบน้ำอื่นๆ ซึ่งพรรณไม้เหล่านี้ ชอบแสงแดดจัด และความชื้นต่ำ ผสมผสานกับพรรณไม้อื่นๆในเมืองไทยที่มีคาแรกเตอร์เข้ากับสวนแนวนี้ ดังนั้น หากสวนของเรามีการระบายน้ำที่ดี ได้แสงแดดทั่วถึง พืชแหล่านี้จะอยู่ได้อย่างยืนยาวและดูแลง่าย ยังมีองค์ประกอบอื่นๆในสวน อย่าง การใช้กระถางดินเผา พื้นกรวด การทำเนิน และเปิดช่องแสง เพือดึงธรรมชาติมาไว้ในสวน</span></p>', 550.00, 10, 22, 18, 32, 0, 1, '2024-09-21 12:51:26', '2024-09-21 12:52:15'),
(133, 'คนลึกไขปริศนาลับ Black Butler เล่ม 27', 'img_66eedafe84fe20fd39cd3e59ae0b3d3f21726929662.jpg', NULL, '9786166130003', 9, 55, 174, '<p><span style=\"color:rgb(0,0,0);font-family:THSarabun, Arial, sans-serif;font-size:13px;\">&nbsp; ตอบมาเถิด ใดรเป็นตนเรียกข้า?วันที่ 14 ธันวาดม 1885 วันเกิดอายุ 10 ขวบของซิเอล \"ดวามสิ้นหวัง\" ได้เริ่มขึ้น...เด็กที่ไร้ซึ่งพลังตะโกนร้องให้เสียงดัง โดลนสีดำมะเมื่อมที่ถูกดึงดูดด้วยเสียงแห่งความเกลียดชังกระเพื่อมไป...กระเพื่อมมาการ์ตูนเกี่ยวกับหัวหน้าตนรับใช้ที่โหดร้ายป้าเถื่อนที่สุดในโสกมาถึงมือคุณแล้ว...</span></p>', 95.50, 10, 18, 19, 33, 1, 1, '2024-09-21 12:55:55', '2024-09-21 14:41:02');

-- --------------------------------------------------------

--
-- Table structure for table `bs_products_request`
--

CREATE TABLE `bs_products_request` (
  `prq_id` int(11) NOT NULL COMMENT 'รหัสรายการ',
  `mem_id` int(11) NOT NULL COMMENT 'รหัสสมาชิก',
  `prq_title` varchar(255) NOT NULL COMMENT 'หัวข้อการค้นหา',
  `prq_prd_name` varchar(255) DEFAULT NULL COMMENT 'ชื่อสินค้า',
  `prq_publisher` varchar(100) DEFAULT NULL COMMENT 'สำนักพิมพ์',
  `prq_author` varchar(100) DEFAULT NULL COMMENT 'ผู้แต่ง',
  `prq_prd_volume_number` int(10) DEFAULT NULL COMMENT 'สินค้าเล่มที่',
  `prq_detail` text NOT NULL COMMENT 'รายละเอียด',
  `prq_img` varchar(100) DEFAULT NULL COMMENT 'รูปตัวอย่าง',
  `prq_status` enum('checking','result','success','cancel') NOT NULL DEFAULT 'checking' COMMENT 'สถานะ',
  `prq_time_create` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วัน เวลา ค้นหา',
  `prq_time_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลาการแก้ไขคำค้นหา'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ข้อมูลรายการค้นหาสินค้า';

--
-- Dumping data for table `bs_products_request`
--

INSERT INTO `bs_products_request` (`prq_id`, `mem_id`, `prq_title`, `prq_prd_name`, `prq_publisher`, `prq_author`, `prq_prd_volume_number`, `prq_detail`, `prq_img`, `prq_status`, `prq_time_create`, `prq_time_update`) VALUES
(16, 5, 'หนังสือเรียนภาษาไทย', 'ภาษาไทย', '', '', 0, 'หนังสือเรียนภาษาไทย ป.3 หน้าปกมีนักเรียน 2 คน', 'img_66ef9e95acd86998521c2f4f67e79f5b61726979733.jpg', 'success', '2024-09-22 04:35:33', '2024-09-22 05:52:33');

-- --------------------------------------------------------

--
-- Table structure for table `bs_products_response`
--

CREATE TABLE `bs_products_response` (
  `prp_id` int(11) NOT NULL COMMENT 'รหัสรายการ',
  `prq_id` int(11) NOT NULL COMMENT 'รหัสรายการค้นหา',
  `emp_id` int(11) NOT NULL COMMENT 'รหัสพนักงาน',
  `prp_prd_name` varchar(255) NOT NULL COMMENT 'ชื่อสินค้า',
  `prp_publisher` varchar(100) NOT NULL COMMENT 'สำนักพิมพ์',
  `prp_author` varchar(100) NOT NULL COMMENT 'ผู้แต่ง',
  `prp_prd_volume_number` int(11) NOT NULL COMMENT 'สินค้าเล่มที่',
  `prp_detail` text NOT NULL COMMENT 'รายละเอียด',
  `prp_img` varchar(100) NOT NULL COMMENT 'รูปสินค้า',
  `prp_status` enum('wait','true','false') NOT NULL DEFAULT 'wait' COMMENT 'สถานะ',
  `prp_time_create` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วัน เวลา ตอบกลับ',
  `prp_time_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลาการแก้ไขตอบกลับ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ข้อมูลตอบกลับรายการค้นหาสินค้า';

--
-- Dumping data for table `bs_products_response`
--

INSERT INTO `bs_products_response` (`prp_id`, `prq_id`, `emp_id`, `prp_prd_name`, `prp_publisher`, `prp_author`, `prp_prd_volume_number`, `prp_detail`, `prp_img`, `prp_status`, `prp_time_create`, `prp_time_update`) VALUES
(12, 16, 27, 'แบบฝึกหัด ภาษาไทย ป.3', 'Life Balance, สนพ.', ' มานพ สอนศิริ และคณะ', 1, 'แบบฝึกหัด รายวิชาพื้นฐาน ภาษาไทย ระดับชั้น ป.3 ฉบับอนุญาต (อญ.) ตรงตามหลักสูตรแกนกลางฯ ’51 กลุ่มสาระการเรียนรู้วิชาภาษาไทย เน้นกิจกรรมพัฒนาความรู้และรูปแบบการฝึกฝนทักษะในการใช้ภาษา', 'img_66efa7910b72c0ac1e4bbee67fdeae5091726982033.jpg', 'false', '2024-09-22 05:13:53', '2024-09-22 05:37:29'),
(13, 16, 27, 'สรุปภาษาไทย ป.3 เข้าใจง่าย เก่งได้ในเล่มเดียว ฉบับสมบูรณ์', 'Life Balance, สนพ.', 'สุกัญญา สันติเจริญเลิศ (เชาว์น้ำทิพย์)', 0, '  รวบรวมเนื้อหาภาษาไทย ชั้นประถมศึกษาปีที่ 3 ตรงตามหลักสูตรแกนกลางการศึกษาขั้นพื้นฐาน (ฉบับล่าสุด) ละเอียด ครบถ้วน พัฒนาทักษะภาษาไทย ทั้งการอ่าน การเขียน และสะกดคำตามหลักภาษาไทยที่ถูกต้อง', 'img_66efaf8144a62218b52f28cd935ba289a1726984065.jpg', 'true', '2024-09-22 05:47:45', '2024-09-22 05:52:33');

-- --------------------------------------------------------

--
-- Table structure for table `bs_products_reviews`
--

CREATE TABLE `bs_products_reviews` (
  `prv_id` int(11) NOT NULL COMMENT 'รหัสรายการรีวิว',
  `prd_id` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `ord_id` int(11) DEFAULT NULL COMMENT 'รหัสคำสั่งซื้อ',
  `mem_id` int(11) NOT NULL COMMENT 'รหัสสมาชิก',
  `prv_rating` int(1) NOT NULL COMMENT 'เรท 1-5',
  `prv_detail` varchar(255) NOT NULL COMMENT 'รายละเอียดรีวิว',
  `prv_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'สถานะ 1 = แสดง, 0 = ไม่แสดง',
  `prv_time_create` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วัน เวลา สร้าง',
  `prv_time_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลา แก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ขเอมูลรีวิวสินค้า';

--
-- Dumping data for table `bs_products_reviews`
--

INSERT INTO `bs_products_reviews` (`prv_id`, `prd_id`, `ord_id`, `mem_id`, `prv_rating`, `prv_detail`, `prv_status`, `prv_time_create`, `prv_time_update`) VALUES
(11, 122, 24, 5, 5, 'อ่านสนุกมาก เหมาะสำหรับทุกวัย', 1, '2024-09-22 06:10:42', '2024-09-22 06:16:46');

-- --------------------------------------------------------

--
-- Table structure for table `bs_products_type`
--

CREATE TABLE `bs_products_type` (
  `pty_id` int(11) NOT NULL COMMENT 'รหัสประเภทสินค้า',
  `pty_name` varchar(100) NOT NULL COMMENT 'ชื่อประเภทสินค้า',
  `pty_cover` varchar(100) NOT NULL COMMENT 'รูปปกประเภทสินค้า',
  `pty_detail` text NOT NULL COMMENT 'รายละเอียดประเภทสินค้า',
  `pty_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'สถานะ 1 = แสดง, 0 = ไม่แสดง',
  `pty_time_create` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วัน เวลา สร้าง',
  `pty_time_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลา แก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ข้อมูลประเภทสินค้า';

--
-- Dumping data for table `bs_products_type`
--

INSERT INTO `bs_products_type` (`pty_id`, `pty_name`, `pty_cover`, `pty_detail`, `pty_status`, `pty_time_create`, `pty_time_update`) VALUES
(16, 'นิยาย', 'img_66ee8a560e0fd0e8f1578700a09d24d821726909014.png', 'นิยายอ่านสนุข', 1, '2024-09-21 08:56:54', '2024-09-21 08:56:54'),
(17, 'มังงะ', 'img_66eeb0d496b0480fff5ef08c6681baa1e1726918868.png', 'รวมมังงะ', 1, '2024-09-21 11:41:08', '2024-09-21 11:41:08'),
(18, 'การ์ตูน', 'img_66eeb6c608492373a576e16feadd05eb31726920390.png', 'การ์ตูน', 1, '2024-09-21 12:06:30', '2024-09-21 12:06:30'),
(19, 'จิตวิทยา การพัฒนาตัวเอง', 'img_66eeb7e83b6e6c4202d8ef951717bd8201726920680.png', 'จิตวิทยา การพัฒนาตัวเอง', 1, '2024-09-21 12:11:20', '2024-09-21 12:11:20'),
(20, 'นิตยสาร', 'img_66eeba0d121011c963b23bb8037fc85811726921229.png', 'นิตยสาร', 1, '2024-09-21 12:20:29', '2024-09-21 12:20:29'),
(21, 'เรื่องสั้น', 'img_66eebac61a4122d5f06ff74aa8c7ce1a51726921414.png', 'เรื่องสั้น', 1, '2024-09-21 12:23:34', '2024-09-21 12:23:34'),
(22, 'บ้านและสวน', 'img_66eec0191ec280c718adc484d7645eac41726922777.png', 'บ้านและสวน', 1, '2024-09-21 12:46:17', '2024-09-21 12:46:17');

-- --------------------------------------------------------

--
-- Table structure for table `bs_products_views`
--

CREATE TABLE `bs_products_views` (
  `prv_id` int(11) NOT NULL COMMENT 'รหัสรายการ',
  `prd_id` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `pty_id` int(11) NOT NULL COMMENT 'รหัสประเภทสินค้า',
  `prv_view` int(11) NOT NULL DEFAULT 0 COMMENT 'ยอดเข้าชม',
  `prv_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลา เข้าชม'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ยอดเข้าชมสถานที่ท่องเที่ยว';

--
-- Dumping data for table `bs_products_views`
--

INSERT INTO `bs_products_views` (`prv_id`, `prd_id`, `pty_id`, `prv_view`, `prv_time`) VALUES
(149, 121, 17, 0, '2024-09-21 11:48:35'),
(150, 122, 17, 0, '2024-09-21 11:53:21'),
(151, 123, 16, 0, '2024-09-21 11:57:19'),
(152, 124, 16, 0, '2024-09-21 12:01:06'),
(153, 125, 18, 0, '2024-09-21 12:07:12'),
(154, 125, 18, 1, '2024-09-21 12:07:26'),
(155, 122, 17, 1, '2024-09-21 12:08:01'),
(156, 125, 18, 1, '2024-09-21 12:08:09'),
(157, 126, 19, 0, '2024-09-21 12:13:47'),
(158, 126, 19, 1, '2024-09-21 12:18:46'),
(159, 126, 19, 1, '2024-09-21 12:18:51'),
(160, 127, 20, 0, '2024-09-21 12:21:54'),
(161, 128, 21, 0, '2024-09-21 12:29:39'),
(162, 128, 21, 1, '2024-09-21 12:31:59'),
(163, 129, 21, 0, '2024-09-21 12:40:44'),
(164, 130, 19, 0, '2024-09-21 12:43:03'),
(165, 131, 22, 0, '2024-09-21 12:48:11'),
(166, 131, 22, 1, '2024-09-21 12:48:54'),
(167, 132, 22, 0, '2024-09-21 12:51:26'),
(168, 133, 18, 0, '2024-09-21 12:55:55'),
(169, 130, 19, 1, '2024-09-21 15:02:02'),
(170, 124, 16, 1, '2024-09-21 15:02:05'),
(171, 121, 17, 1, '2024-09-21 15:02:20'),
(172, 122, 17, 1, '2024-09-21 15:07:09'),
(173, 122, 17, 1, '2024-09-21 15:07:28'),
(174, 126, 19, 1, '2024-09-21 15:07:37'),
(175, 126, 19, 1, '2024-09-21 15:07:39'),
(176, 122, 17, 1, '2024-09-22 03:50:57'),
(177, 133, 18, 1, '2024-09-22 04:23:30'),
(178, 133, 18, 1, '2024-09-22 04:23:32'),
(179, 125, 18, 1, '2024-09-22 04:23:40'),
(180, 125, 18, 1, '2024-09-22 04:23:42'),
(181, 126, 19, 1, '2024-09-22 06:11:06'),
(182, 126, 19, 1, '2024-09-22 06:16:50'),
(183, 122, 17, 1, '2024-09-22 06:17:11'),
(184, 129, 21, 1, '2024-09-22 10:40:25'),
(185, 121, 17, 1, '2024-09-22 10:40:29'),
(186, 121, 17, 1, '2024-09-22 10:40:33'),
(187, 129, 21, 1, '2024-09-22 10:48:05'),
(188, 131, 22, 1, '2024-09-22 10:48:07'),
(189, 124, 16, 1, '2024-09-22 10:48:08'),
(190, 128, 21, 1, '2024-09-22 10:48:19'),
(191, 123, 16, 1, '2024-09-22 10:48:24'),
(192, 127, 20, 1, '2024-09-22 10:48:26');

-- --------------------------------------------------------

--
-- Table structure for table `bs_promotions`
--

CREATE TABLE `bs_promotions` (
  `pro_id` int(11) NOT NULL COMMENT 'รหัสโปรโมชั่น',
  `pro_img` varchar(100) NOT NULL COMMENT 'รูปโปรโมชั่น',
  `pro_name` varchar(100) NOT NULL COMMENT 'ชื่อโปรโมชั่น',
  `pro_percent_discount` int(3) NOT NULL COMMENT 'เปอร์เซ็นโปรโมชั่น',
  `pro_detail` varchar(255) NOT NULL COMMENT 'รายละเอียดโปรโมชั่น',
  `pro_time_start` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันเริ่มโปรโมชั่น',
  `pro_time_end` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันสิ้นสุดโปรโมชั่น',
  `pro_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'สถานะ 1 = แสดง, 0 = ไม่แสดง',
  `pro_time_create` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วัน เวลา สร้าง',
  `pro_time_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลาแก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ข้อมูลโปรโมชั่น';

--
-- Dumping data for table `bs_promotions`
--

INSERT INTO `bs_promotions` (`pro_id`, `pro_img`, `pro_name`, `pro_percent_discount`, `pro_detail`, `pro_time_start`, `pro_time_end`, `pro_status`, `pro_time_create`, `pro_time_update`) VALUES
(5, 'img_66ef94945321d82affd76792b07ce95761726977172.jpg', 'ลดฤดูร้อน', 5, 'ลดฤดูร้อน', '2024-09-01 03:51:00', '2024-09-30 03:51:00', 1, '2024-09-22 03:51:52', '2024-09-22 03:52:52'),
(6, 'img_66ef94deca51f0f0060c0051297c213621726977246.jpg', 'ลดฤดูหนาว', 3, 'ลดฤดูหนาว', '2024-09-04 03:53:00', '2024-09-16 03:53:00', 1, '2024-09-22 03:53:40', '2024-09-22 03:54:06');

-- --------------------------------------------------------

--
-- Table structure for table `bs_publishers`
--

CREATE TABLE `bs_publishers` (
  `pub_id` int(11) NOT NULL COMMENT 'รหัสสำนักพิมพิ์',
  `pub_name` varchar(100) NOT NULL COMMENT 'ชื่อสำนักพิมพิ์',
  `pub_img` varchar(100) NOT NULL COMMENT 'รูปสำนักพิมพิ์',
  `pub_detail` varchar(255) NOT NULL COMMENT 'รายละเอียดสำนักพิมพิ์',
  `pub_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'สถานะ 1 = แสดง, 0 = ไม่แสดง',
  `pub_time_create` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วัน เวลา สร้าง',
  `pub_time_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลา แก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ข้อมูลสำนักพิมพ์';

--
-- Dumping data for table `bs_publishers`
--

INSERT INTO `bs_publishers` (`pub_id`, `pub_name`, `pub_img`, `pub_detail`, `pub_status`, `pub_time_create`, `pub_time_update`) VALUES
(10, 'นานมีบุ๊คส์', 'img_66ee909e8b340a83ff9201749b20cafd31726910622.png', 'นานมีบุ๊คส์', 1, '2024-09-21 09:23:42', '2024-09-21 11:47:15'),
(11, 'เอ็นเธอร์บุ๊คส์', 'img_66eeb465b7ce8f348066c592b629a115d1726919781.png', 'เอ็นเธอร์บุ๊คส์', 1, '2024-09-21 11:56:21', '2024-09-21 11:56:21'),
(12, 'เอิร์นเนส พับลิชชิ่ง', 'img_66eeb54f59f1ee9ea4d7d80c0fbe062631726920015.png', 'เอิร์นเนส พับลิชชิ่ง', 1, '2024-09-21 12:00:15', '2024-09-21 12:00:15'),
(13, 'GEMINI', 'img_66eeb66de8332d7009affb3c254ff4c0d1726920301.png', 'GEMINI', 1, '2024-09-21 12:05:01', '2024-09-21 12:05:01'),
(14, 'เดอะไลบรารี่เลิร์น', 'img_66eeb832b04cacd80dafc0afdaafb6b381726920754.png', 'เดอะไลบรารี่เลิร์น', 1, '2024-09-21 12:12:34', '2024-09-21 12:12:34'),
(15, 'สุดสัปดาห์สำนักพิมพ์', 'img_66eeba32f4222582040cd27683cfeb9471726921266.png', 'สุดสัปดาห์สำนักพิมพ์', 1, '2024-09-21 12:21:07', '2024-09-21 12:21:07'),
(16, 'Piccolo', 'img_66eebad7f0b51390be4440e451550fba31726921431.png', 'Piccolo', 1, '2024-09-21 12:23:51', '2024-09-21 12:23:51'),
(17, 'เป็ดเต่าควาย PTK', 'img_66eebe97149caf09097b30988f97d44031726922391.png', 'เป็ดเต่าควาย PTK', 1, '2024-09-21 12:39:51', '2024-09-21 12:39:51'),
(18, 'บ้านและสวน', 'img_66eec041f2a388a2dff1bb519978d7cf91726922817.png', 'บ้านและสวน', 1, '2024-09-21 12:46:57', '2024-09-21 12:46:57'),
(19, 'สยามอินเตอร์คอมิกส์/Siam Inter Comics', 'img_66eec221555c452558267c56dee16daa71726923297.png', 'สยามอินเตอร์คอมิกส์/Siam Inter Comics', 1, '2024-09-21 12:54:57', '2024-09-21 12:54:57');

-- --------------------------------------------------------

--
-- Table structure for table `bs_settings_website`
--

CREATE TABLE `bs_settings_website` (
  `st_id` int(11) NOT NULL COMMENT 'รหัสรายการตั้งค่า',
  `st_name` varchar(100) NOT NULL COMMENT 'รายการตั้งค่า',
  `st_detail` text DEFAULT NULL COMMENT 'รายละเอียดการตั้งค่า',
  `st_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'สถานะ 1 = แสดง, 0 = ไม่แสดง',
  `st_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลา'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ข้อมูลตั้งค่าเว็บไซต์';

--
-- Dumping data for table `bs_settings_website`
--

INSERT INTO `bs_settings_website` (`st_id`, `st_name`, `st_detail`, `st_status`, `st_time`) VALUES
(1, 'ชื่อเว็บไซต์ (Website Name)', 'BookShop', 1, '2024-09-21 11:36:05'),
(2, 'Favicon (Icon บน Tab)', 'img_66ee726c90d9f7710390bb9be5e10cc451726902892.png', 1, '2024-09-21 07:14:52'),
(3, 'Logo Website (สัญลักษณ์เว็บไซต์)', 'img_66ee711a6c802394b6c6f23c3823fd6b51726902554.jpg', 1, '2024-09-21 07:09:14'),
(4, 'Products Number Low (แจ้งเตือนเมื่อสินค้าเหลือน้อยกว่า)', '20', 1, '2024-09-22 07:55:20'),
(5, 'products Promotion (เมนูโปรโมชั่นที่ลดเยอะ)', '50', 1, '2024-07-20 13:48:45');

-- --------------------------------------------------------

--
-- Table structure for table `bs_shipping`
--

CREATE TABLE `bs_shipping` (
  `shp_id` int(11) NOT NULL COMMENT 'รหัสช่องทางจัดส่ง',
  `shp_name` varchar(100) NOT NULL COMMENT 'ชื่อช่องทางจัดส่ง',
  `shp_logo` varchar(100) NOT NULL COMMENT 'โลโก้ช่องทางจัดส่ง',
  `shp_price` decimal(10,2) NOT NULL COMMENT 'ราคาจัดส่ง',
  `shp_detail` varchar(255) NOT NULL COMMENT 'รายละเอียดช่องทางจัดส่ง',
  `shp_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'สถานะ 1 = แสดง, 0 = ไม่แสดง',
  `shp_time_create` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วัน เวลาสร้าง',
  `shp_time_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วัน เวลาแก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ข้อมูลช่องทางจัดส่ง';

--
-- Dumping data for table `bs_shipping`
--

INSERT INTO `bs_shipping` (`shp_id`, `shp_name`, `shp_logo`, `shp_price`, `shp_detail`, `shp_status`, `shp_time_create`, `shp_time_update`) VALUES
(11, 'Kerry', 'img_66ef92a02b058007884aaa9c0ab4a7b191726976672.png', 50.00, 'Kerry', 1, '2024-09-21 15:15:00', '2024-09-22 03:44:32'),
(12, 'ไปรษณีย์ไทย', 'img_66ef92d892d78cc7d3c7d05e0ed7873581726976728.png', 0.00, 'ไปรษณีย์ไทย', 1, '2024-09-22 03:45:00', '2024-09-22 03:47:09'),
(13, 'Flash Express', 'img_66ef933593607e884cb633b5488e8ea151726976821.png', 30.00, 'Flash Express', 1, '2024-09-22 03:46:31', '2024-09-22 03:47:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bs_authors`
--
ALTER TABLE `bs_authors`
  ADD PRIMARY KEY (`auth_id`);

--
-- Indexes for table `bs_banners`
--
ALTER TABLE `bs_banners`
  ADD PRIMARY KEY (`bn_id`);

--
-- Indexes for table `bs_cart`
--
ALTER TABLE `bs_cart`
  ADD PRIMARY KEY (`crt_id`),
  ADD KEY `mem_id` (`mem_id`),
  ADD KEY `prd_id` (`prd_id`);

--
-- Indexes for table `bs_contacts`
--
ALTER TABLE `bs_contacts`
  ADD PRIMARY KEY (`ct_id`);

--
-- Indexes for table `bs_employees`
--
ALTER TABLE `bs_employees`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `bs_employees_authority`
--
ALTER TABLE `bs_employees_authority`
  ADD PRIMARY KEY (`ea_id`),
  ADD KEY `bs_employees_authority_ibfk_1` (`emp_id`),
  ADD KEY `bs_employees_authority_ibfk_2` (`eat_id`);

--
-- Indexes for table `bs_employees_authority_type`
--
ALTER TABLE `bs_employees_authority_type`
  ADD PRIMARY KEY (`eat_id`);

--
-- Indexes for table `bs_members`
--
ALTER TABLE `bs_members`
  ADD PRIMARY KEY (`mem_id`);

--
-- Indexes for table `bs_members_address`
--
ALTER TABLE `bs_members_address`
  ADD PRIMARY KEY (`addr_id`),
  ADD KEY `mem_id` (`mem_id`);

--
-- Indexes for table `bs_members_history_coins`
--
ALTER TABLE `bs_members_history_coins`
  ADD PRIMARY KEY (`mhc_id`),
  ADD KEY `bs_members_history_coins_ibfk_1` (`mhc_from_mem_id`),
  ADD KEY `bs_members_history_coins_ibfk_2` (`mhc_to_mem_id`),
  ADD KEY `ord_id` (`ord_id`);

--
-- Indexes for table `bs_members_wishlist`
--
ALTER TABLE `bs_members_wishlist`
  ADD PRIMARY KEY (`mwl_id`),
  ADD KEY `mem_id` (`mem_id`),
  ADD KEY `prd_id` (`prd_id`);

--
-- Indexes for table `bs_orders`
--
ALTER TABLE `bs_orders`
  ADD PRIMARY KEY (`ord_id`),
  ADD KEY `mem_id` (`mem_id`);

--
-- Indexes for table `bs_orders_address`
--
ALTER TABLE `bs_orders_address`
  ADD PRIMARY KEY (`oad_id`),
  ADD KEY `ord_id` (`ord_id`),
  ADD KEY `addr_id` (`addr_id`);

--
-- Indexes for table `bs_orders_items`
--
ALTER TABLE `bs_orders_items`
  ADD PRIMARY KEY (`oit_id`),
  ADD KEY `prd_id` (`prd_id`),
  ADD KEY `bs_orders_items_ibfk_1` (`ord_id`);

--
-- Indexes for table `bs_orders_payments`
--
ALTER TABLE `bs_orders_payments`
  ADD PRIMARY KEY (`opm_id`),
  ADD KEY `ord_id` (`ord_id`),
  ADD KEY `pmt_id` (`pmt_id`);

--
-- Indexes for table `bs_orders_promotions`
--
ALTER TABLE `bs_orders_promotions`
  ADD PRIMARY KEY (`opm_id`),
  ADD KEY `ord_id` (`ord_id`),
  ADD KEY `bs_orders_promotions_ibfk_2` (`pro_id`);

--
-- Indexes for table `bs_orders_shippings`
--
ALTER TABLE `bs_orders_shippings`
  ADD PRIMARY KEY (`osp_id`),
  ADD KEY `ord_id` (`ord_id`),
  ADD KEY `shp_id` (`shp_id`);

--
-- Indexes for table `bs_orders_slips`
--
ALTER TABLE `bs_orders_slips`
  ADD PRIMARY KEY (`osl_id`),
  ADD KEY `ord_id` (`ord_id`),
  ADD KEY `mem_id` (`mem_id`);

--
-- Indexes for table `bs_payments`
--
ALTER TABLE `bs_payments`
  ADD PRIMARY KEY (`pmt_id`);

--
-- Indexes for table `bs_products`
--
ALTER TABLE `bs_products`
  ADD PRIMARY KEY (`prd_id`);

--
-- Indexes for table `bs_products_request`
--
ALTER TABLE `bs_products_request`
  ADD PRIMARY KEY (`prq_id`),
  ADD KEY `bs_products_request_ibfk_1` (`mem_id`);

--
-- Indexes for table `bs_products_response`
--
ALTER TABLE `bs_products_response`
  ADD PRIMARY KEY (`prp_id`),
  ADD KEY `bs_products_response_ibfk_1` (`prq_id`),
  ADD KEY `bs_products_response_ibfk_2` (`emp_id`);

--
-- Indexes for table `bs_products_reviews`
--
ALTER TABLE `bs_products_reviews`
  ADD PRIMARY KEY (`prv_id`),
  ADD KEY `prd_id` (`prd_id`),
  ADD KEY `ord_id` (`ord_id`);

--
-- Indexes for table `bs_products_type`
--
ALTER TABLE `bs_products_type`
  ADD PRIMARY KEY (`pty_id`);

--
-- Indexes for table `bs_products_views`
--
ALTER TABLE `bs_products_views`
  ADD PRIMARY KEY (`prv_id`),
  ADD KEY `prd_id` (`prd_id`),
  ADD KEY `pty_id` (`pty_id`);

--
-- Indexes for table `bs_promotions`
--
ALTER TABLE `bs_promotions`
  ADD PRIMARY KEY (`pro_id`);

--
-- Indexes for table `bs_publishers`
--
ALTER TABLE `bs_publishers`
  ADD PRIMARY KEY (`pub_id`);

--
-- Indexes for table `bs_settings_website`
--
ALTER TABLE `bs_settings_website`
  ADD PRIMARY KEY (`st_id`);

--
-- Indexes for table `bs_shipping`
--
ALTER TABLE `bs_shipping`
  ADD PRIMARY KEY (`shp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bs_authors`
--
ALTER TABLE `bs_authors`
  MODIFY `auth_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสผู้แต่ง', AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `bs_banners`
--
ALTER TABLE `bs_banners`
  MODIFY `bn_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสแบนเนอร์', AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `bs_cart`
--
ALTER TABLE `bs_cart`
  MODIFY `crt_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสตะกร้า', AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `bs_contacts`
--
ALTER TABLE `bs_contacts`
  MODIFY `ct_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสช่องทางติดต่อ', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `bs_employees`
--
ALTER TABLE `bs_employees`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสพนักงาน', AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `bs_employees_authority`
--
ALTER TABLE `bs_employees_authority`
  MODIFY `ea_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายการ', AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT for table `bs_employees_authority_type`
--
ALTER TABLE `bs_employees_authority_type`
  MODIFY `eat_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสสิทธิ์', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bs_members`
--
ALTER TABLE `bs_members`
  MODIFY `mem_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสสมาชิก', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `bs_members_address`
--
ALTER TABLE `bs_members_address`
  MODIFY `addr_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายการ', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `bs_members_history_coins`
--
ALTER TABLE `bs_members_history_coins`
  MODIFY `mhc_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายการ', AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `bs_members_wishlist`
--
ALTER TABLE `bs_members_wishlist`
  MODIFY `mwl_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายการ', AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `bs_orders`
--
ALTER TABLE `bs_orders`
  MODIFY `ord_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายการสั่งซื้อ', AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `bs_orders_address`
--
ALTER TABLE `bs_orders_address`
  MODIFY `oad_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสที่อยู่จัดส่ง', AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `bs_orders_items`
--
ALTER TABLE `bs_orders_items`
  MODIFY `oit_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสสินค้าในรายการสั่งซื้อ', AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `bs_orders_payments`
--
ALTER TABLE `bs_orders_payments`
  MODIFY `opm_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายการ', AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `bs_orders_promotions`
--
ALTER TABLE `bs_orders_promotions`
  MODIFY `opm_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายการโปรโมชั่นที่ใช้', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `bs_orders_shippings`
--
ALTER TABLE `bs_orders_shippings`
  MODIFY `osp_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายการ', AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `bs_orders_slips`
--
ALTER TABLE `bs_orders_slips`
  MODIFY `osl_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายการ', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `bs_payments`
--
ALTER TABLE `bs_payments`
  MODIFY `pmt_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายการ', AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `bs_products`
--
ALTER TABLE `bs_products`
  MODIFY `prd_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสสินค้า', AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT for table `bs_products_request`
--
ALTER TABLE `bs_products_request`
  MODIFY `prq_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายการ', AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `bs_products_response`
--
ALTER TABLE `bs_products_response`
  MODIFY `prp_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายการ', AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `bs_products_reviews`
--
ALTER TABLE `bs_products_reviews`
  MODIFY `prv_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายการรีวิว', AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `bs_products_type`
--
ALTER TABLE `bs_products_type`
  MODIFY `pty_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสประเภทสินค้า', AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `bs_products_views`
--
ALTER TABLE `bs_products_views`
  MODIFY `prv_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายการ', AUTO_INCREMENT=193;

--
-- AUTO_INCREMENT for table `bs_promotions`
--
ALTER TABLE `bs_promotions`
  MODIFY `pro_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสโปรโมชั่น', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bs_publishers`
--
ALTER TABLE `bs_publishers`
  MODIFY `pub_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสสำนักพิมพิ์', AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `bs_settings_website`
--
ALTER TABLE `bs_settings_website`
  MODIFY `st_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายการตั้งค่า', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bs_shipping`
--
ALTER TABLE `bs_shipping`
  MODIFY `shp_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสช่องทางจัดส่ง', AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bs_cart`
--
ALTER TABLE `bs_cart`
  ADD CONSTRAINT `bs_cart_ibfk_1` FOREIGN KEY (`mem_id`) REFERENCES `bs_members` (`mem_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bs_cart_ibfk_2` FOREIGN KEY (`prd_id`) REFERENCES `bs_products` (`prd_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bs_employees_authority`
--
ALTER TABLE `bs_employees_authority`
  ADD CONSTRAINT `bs_employees_authority_ibfk_1` FOREIGN KEY (`emp_id`) REFERENCES `bs_employees` (`emp_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bs_employees_authority_ibfk_2` FOREIGN KEY (`eat_id`) REFERENCES `bs_employees_authority_type` (`eat_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bs_members_address`
--
ALTER TABLE `bs_members_address`
  ADD CONSTRAINT `bs_members_address_ibfk_1` FOREIGN KEY (`mem_id`) REFERENCES `bs_members` (`mem_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bs_members_history_coins`
--
ALTER TABLE `bs_members_history_coins`
  ADD CONSTRAINT `bs_members_history_coins_ibfk_1` FOREIGN KEY (`mhc_from_mem_id`) REFERENCES `bs_members` (`mem_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bs_members_history_coins_ibfk_2` FOREIGN KEY (`mhc_to_mem_id`) REFERENCES `bs_members` (`mem_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bs_members_history_coins_ibfk_3` FOREIGN KEY (`ord_id`) REFERENCES `bs_orders` (`ord_id`) ON DELETE SET NULL;

--
-- Constraints for table `bs_members_wishlist`
--
ALTER TABLE `bs_members_wishlist`
  ADD CONSTRAINT `bs_members_wishlist_ibfk_1` FOREIGN KEY (`mem_id`) REFERENCES `bs_members` (`mem_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bs_members_wishlist_ibfk_2` FOREIGN KEY (`prd_id`) REFERENCES `bs_products` (`prd_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bs_orders`
--
ALTER TABLE `bs_orders`
  ADD CONSTRAINT `bs_orders_ibfk_1` FOREIGN KEY (`mem_id`) REFERENCES `bs_members` (`mem_id`) ON DELETE SET NULL;

--
-- Constraints for table `bs_orders_address`
--
ALTER TABLE `bs_orders_address`
  ADD CONSTRAINT `bs_orders_address_ibfk_1` FOREIGN KEY (`ord_id`) REFERENCES `bs_orders` (`ord_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bs_orders_address_ibfk_2` FOREIGN KEY (`addr_id`) REFERENCES `bs_members_address` (`addr_id`) ON DELETE SET NULL;

--
-- Constraints for table `bs_orders_items`
--
ALTER TABLE `bs_orders_items`
  ADD CONSTRAINT `bs_orders_items_ibfk_1` FOREIGN KEY (`ord_id`) REFERENCES `bs_orders` (`ord_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bs_orders_items_ibfk_2` FOREIGN KEY (`prd_id`) REFERENCES `bs_products` (`prd_id`) ON DELETE SET NULL;

--
-- Constraints for table `bs_orders_payments`
--
ALTER TABLE `bs_orders_payments`
  ADD CONSTRAINT `bs_orders_payments_ibfk_1` FOREIGN KEY (`ord_id`) REFERENCES `bs_orders` (`ord_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bs_orders_payments_ibfk_2` FOREIGN KEY (`pmt_id`) REFERENCES `bs_payments` (`pmt_id`) ON DELETE SET NULL;

--
-- Constraints for table `bs_orders_promotions`
--
ALTER TABLE `bs_orders_promotions`
  ADD CONSTRAINT `bs_orders_promotions_ibfk_1` FOREIGN KEY (`ord_id`) REFERENCES `bs_orders` (`ord_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bs_orders_promotions_ibfk_2` FOREIGN KEY (`pro_id`) REFERENCES `bs_promotions` (`pro_id`) ON DELETE SET NULL;

--
-- Constraints for table `bs_orders_shippings`
--
ALTER TABLE `bs_orders_shippings`
  ADD CONSTRAINT `bs_orders_shippings_ibfk_1` FOREIGN KEY (`ord_id`) REFERENCES `bs_orders` (`ord_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bs_orders_shippings_ibfk_2` FOREIGN KEY (`shp_id`) REFERENCES `bs_shipping` (`shp_id`) ON DELETE SET NULL;

--
-- Constraints for table `bs_orders_slips`
--
ALTER TABLE `bs_orders_slips`
  ADD CONSTRAINT `bs_orders_slips_ibfk_1` FOREIGN KEY (`ord_id`) REFERENCES `bs_orders` (`ord_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bs_orders_slips_ibfk_2` FOREIGN KEY (`mem_id`) REFERENCES `bs_members` (`mem_id`) ON DELETE SET NULL;

--
-- Constraints for table `bs_products_request`
--
ALTER TABLE `bs_products_request`
  ADD CONSTRAINT `bs_products_request_ibfk_1` FOREIGN KEY (`mem_id`) REFERENCES `bs_members` (`mem_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bs_products_response`
--
ALTER TABLE `bs_products_response`
  ADD CONSTRAINT `bs_products_response_ibfk_1` FOREIGN KEY (`prq_id`) REFERENCES `bs_products_request` (`prq_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bs_products_response_ibfk_2` FOREIGN KEY (`emp_id`) REFERENCES `bs_employees` (`emp_id`);

--
-- Constraints for table `bs_products_reviews`
--
ALTER TABLE `bs_products_reviews`
  ADD CONSTRAINT `bs_products_reviews_ibfk_1` FOREIGN KEY (`prd_id`) REFERENCES `bs_products` (`prd_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bs_products_reviews_ibfk_2` FOREIGN KEY (`ord_id`) REFERENCES `bs_orders` (`ord_id`) ON DELETE SET NULL;

--
-- Constraints for table `bs_products_views`
--
ALTER TABLE `bs_products_views`
  ADD CONSTRAINT `bs_products_views_ibfk_1` FOREIGN KEY (`prd_id`) REFERENCES `bs_products` (`prd_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bs_products_views_ibfk_2` FOREIGN KEY (`pty_id`) REFERENCES `bs_products_type` (`pty_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
