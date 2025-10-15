-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2025 at 05:44 AM
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
-- Database: `pos3`
--

-- --------------------------------------------------------

--
-- Table structure for table `menu_tbl`
--

CREATE TABLE `menu_tbl` (
  `menu_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category` enum('Waffles','Cakes','Beverages') DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('Available','Unavailable') DEFAULT 'Available',
  `image` varchar(255) DEFAULT NULL,
  `date_created` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_tbl`
--

INSERT INTO `menu_tbl` (`menu_id`, `item_name`, `description`, `category`, `price`, `status`, `image`, `date_created`) VALUES
(16, 'scsc123', 'scscdv', 'Cakes', 12135.00, 'Available', 'uploads/menu_images/d221c31d211a230e62700029218d84eb.jpg', '2025-10-13 14:07:21'),
(17, 'scvscs', 'vdvswfcwascfsac', 'Waffles', 123467.00, 'Available', 'uploads/menu_images/e807e49f9ec0e819c6547aaf3aa9648a.jpg', '2025-10-13 14:11:49'),
(18, 'dvewfve', 'svcswvswv', 'Beverages', 1267.00, 'Available', 'uploads/menu_images/4cb9b6d7c59c82c01a8aa876566862ec.jpg', '2025-10-13 14:15:28'),
(19, 'vdsv sv', 'dvdvdvvd', 'Cakes', 125.00, 'Available', 'uploads/menu_images/e5abcf475ef9800ea78e71b0b6770f3c.jpg', '2025-10-13 14:19:18'),
(20, 'master12345', 'vedvede', 'Beverages', 235.00, 'Available', 'uploads/menu_images/4b08ce7e03fa55f9f1f41b1a4ff0d9a1.jpg', '2025-10-13 15:21:33'),
(21, 'master2345', 'evrgghb', 'Beverages', 234.00, 'Available', 'uploads/menu_images/db110efc571cdac57ce7417b96c5651c.jpg', '2025-10-13 19:25:09');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`version`) VALUES
(0);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) UNSIGNED NOT NULL,
  `signup_id` int(11) NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `signup_id`, `token_hash`, `expires_at`, `used`, `created_at`) VALUES
(1, 9, '$2y$10$k8g3c0Lbiy1AlS.avo6DUOaJmlMuV0offYbp8/G1MZJOXCPpejT1C', '2025-10-15 09:33:10', 0, '2025-10-14 09:33:10'),
(2, 10, '$2y$10$8tj4Zeq8SWkw3SlB2sgGG.vq.u4fVjIPNjXS.Mf1hvYVSch4xJway', '2025-10-15 09:35:46', 0, '2025-10-14 09:35:46'),
(3, 11, '$2y$10$s2ea2FMv8fmq6TL1mMK7AO33Ltss2EEuFHYiynhePdSWLZLS.cW3i', '2025-10-15 09:36:48', 0, '2025-10-14 09:36:48'),
(4, 12, '$2y$10$lrq0DyT07l0xN9X4RRFOSOKBHrBG1vlXWRdxvosnC3cF3abuHA8NG', '2025-10-16 02:36:39', 0, '2025-10-15 02:36:39'),
(5, 13, '$2y$10$ah3xSE/dyfVdZRRyVAOSm.48lSpdKCkPHlWo.MB4lqUoYhevDvqPO', '2025-10-16 03:50:49', 0, '2025-10-15 03:50:49'),
(6, 14, '$2y$10$ACkri0Boq1L.ck6qbttjtOpV97o8viGTRiC0xhFVRhFKhk7/zeM.e', '2025-10-16 03:54:32', 0, '2025-10-15 03:54:32');

-- --------------------------------------------------------

--
-- Table structure for table `roles_tbl`
--

CREATE TABLE `roles_tbl` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles_tbl`
--

INSERT INTO `roles_tbl` (`role_id`, `role_name`, `description`) VALUES
(1, 'Owner', 'System Owner with full privileges'),
(2, 'Admin', 'Can manage staff and view reports'),
(3, 'Staff', 'Can perform assigned operational tasks');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_inventory_details`
--

CREATE TABLE `tbl_inventory_details` (
  `inventory_details_id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_inventory_details`
--

INSERT INTO `tbl_inventory_details` (`inventory_details_id`, `item_id`, `quantity`, `action`, `created_at`) VALUES
(1, 2, 23, 'in', '2025-10-13 17:03:57'),
(2, 4, 1, 'out', '2025-10-13 17:59:36'),
(3, 2, 1, 'out', '2025-10-13 17:59:36'),
(4, 1, 3, 'out', '2025-10-13 17:59:36'),
(5, 1, 5, 'out', '2025-10-13 18:03:20'),
(6, 1, 7, 'in', '2025-10-13 18:03:48'),
(7, 1, 3, 'in', '2025-10-13 18:04:04'),
(8, 4, 3, 'out', '2025-10-13 18:08:21'),
(9, 1, 3, 'out', '2025-10-13 18:08:21'),
(10, 6, 100, 'in', '2025-10-13 19:26:37'),
(11, 6, 20, 'in', '2025-10-13 19:31:41'),
(12, 6, 1, 'out', '2025-10-13 20:46:41'),
(13, 4, 1, 'out', '2025-10-13 20:46:41'),
(14, 2, 1, 'out', '2025-10-13 20:46:41'),
(15, 6, 1, 'out', '2025-10-13 20:48:40'),
(16, 4, 1, 'out', '2025-10-13 20:48:40'),
(17, 1, 3, 'in', '2025-10-14 04:33:18'),
(18, 1, 3, 'out', '2025-10-14 04:34:10'),
(19, 5, 5, 'in', '2025-10-14 04:41:34'),
(20, 3, 4, 'in', '2025-10-14 04:44:18'),
(21, 6, 3, 'out', '2025-10-14 07:17:32'),
(22, 5, 2, 'out', '2025-10-14 07:17:32'),
(23, 4, 2, 'out', '2025-10-14 07:17:32'),
(24, 2, 2, 'out', '2025-10-14 07:17:32'),
(25, 1, 2, 'out', '2025-10-14 07:21:38'),
(26, 1, 5, 'in', '2025-10-14 07:22:20');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menu_items`
--

CREATE TABLE `tbl_menu_items` (
  `item_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` tinyint(4) NOT NULL,
  `is_active` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_menu_items`
--

INSERT INTO `tbl_menu_items` (`item_id`, `menu_id`, `item_name`, `description`, `price`, `stock_quantity`, `is_active`) VALUES
(1, 16, 'scsc123', 'scscdv', 12135.00, 50, 1),
(2, 17, 'scvscs', 'vdvswfcwascfsac', 123467.00, 19, 1),
(3, 18, 'dvewfve', 'svcswvswv', 1267.00, 4, 1),
(4, 19, 'vdsv sv', 'dvdvdvvd', 125.00, 17, 1),
(5, 20, 'master12345', 'vedvede', 235.00, 3, 1),
(6, 21, 'master2345', 'evrgghb', 234.00, 115, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orders`
--

CREATE TABLE `tbl_orders` (
  `order_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `order_items` text DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('New','Processing','Done') DEFAULT 'New',
  `image` varchar(255) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_orders`
--

INSERT INTO `tbl_orders` (`order_id`, `staff_id`, `customer_name`, `order_items`, `total_amount`, `status`, `image`, `order_date`, `updated_at`, `created_at`) VALUES
(2, 6, 'dvsdd', '2x Fries, 3x Burger', 6170.00, 'Done', NULL, '2025-10-13 10:56:35', '2025-10-13 11:01:25', '2025-10-13 16:56:35'),
(3, 6, 'rhdgdsvc', '1x swvsv, 1x swvsv, 1x master', 2660.00, 'Done', NULL, '2025-10-13 12:09:38', '2025-10-13 12:11:13', '2025-10-13 18:09:38'),
(4, 2, 'Ryan Reyes', '3x swvsv, 3x swvsv, 5x master', 8448.00, 'Done', NULL, '2025-10-13 12:13:20', '2025-10-13 12:24:33', '2025-10-13 18:13:20'),
(5, 6, 'ryabrge', '1x swvsv, 1x master', 1447.00, 'Done', NULL, '2025-10-13 12:24:54', '2025-10-13 12:26:43', '2025-10-13 18:24:54'),
(6, 6, 'vwss', '1x swvsv, 1x swvsv, 1x master', 2660.00, 'Done', NULL, '2025-10-13 12:28:09', '2025-10-13 12:28:16', '2025-10-13 18:28:09'),
(7, 6, 'vwss', '1x swvsv, 1x swvsv, 1x master', 2660.00, 'Done', NULL, '2025-10-13 12:30:32', '2025-10-13 12:38:50', '2025-10-13 18:30:32'),
(8, 6, 'svsvs', '3x swvsv, 3x swvsv, 1x master', 7512.00, 'Done', NULL, '2025-10-13 12:39:08', '2025-10-13 12:39:16', '2025-10-13 18:39:08'),
(9, 2, 'rgegegws', '2x swvsv, 1x master, 1x swvsv', 3873.00, 'Done', NULL, '2025-10-13 12:43:28', '2025-10-13 12:43:35', '2025-10-13 18:43:28'),
(10, 2, 'scsc1234', '1x swvsv, 1x swvsv, 1x master', 2660.00, 'Done', NULL, '2025-10-13 12:58:20', '2025-10-13 13:05:20', '2025-10-13 18:58:20'),
(11, 2, 'scsc1234', '1x master12345, 1x vdsv sv, 1x dvewfve, 1x scvscs, 1x scsc', 139340.00, 'Processing', NULL, '2025-10-13 16:17:24', '2025-10-13 17:48:21', '2025-10-13 22:17:24'),
(12, 2, 'rgegegws', '4x vdsv sv', 504.00, 'Done', NULL, '2025-10-13 17:47:29', '2025-10-13 17:52:00', '2025-10-13 23:47:29'),
(13, 2, 'reyes ryan', '3x vdsv sv, 3x scvscs', 370779.00, 'Processing', NULL, '2025-10-13 17:52:56', '2025-10-13 20:06:14', '2025-10-13 23:52:56'),
(14, 2, 'jay bar', '3x vdsv sv, 3x scvscs, 3x scsc', 407184.00, 'Processing', NULL, '2025-10-13 17:56:25', '2025-10-13 18:37:11', '2025-10-13 23:56:25'),
(15, 6, 'cefrgrvv', '1x vdsv sv, 1x scvscs, 1x scsc', 135728.00, 'Done', NULL, '2025-10-13 17:58:48', '2025-10-13 18:50:16', '2025-10-13 23:58:48'),
(16, 2, 'asia shandara', '1x vdsv sv, 1x scvscs, 3x scsc', 159998.00, 'Done', NULL, '2025-10-13 17:59:36', '2025-10-13 18:36:53', '2025-10-13 23:59:36'),
(17, 6, 'Shandy valmorida', '5x scsc', 60675.00, 'Done', NULL, '2025-10-13 18:03:20', '2025-10-13 18:36:29', '2025-10-14 00:03:20'),
(18, 6, 'carloboy', '3x vdsv sv, 3x scsc123', 36783.00, 'Done', NULL, '2025-10-13 18:08:21', '2025-10-13 18:36:15', '2025-10-14 00:08:21'),
(19, 6, 'acsaca', '1x master, 1x vdsv sv, 1x scvscs', 123615.00, 'New', NULL, '2025-10-13 20:46:41', '2025-10-14 02:46:41', '2025-10-14 02:46:41'),
(20, 6, 'scsc1234', '1x master, 1x vdsv sv, 1x master2345', 382.00, 'New', NULL, '2025-10-15 04:48:00', '2025-10-15 03:33:04', '2025-10-14 02:48:39'),
(21, 2, 'carloboy', '3x scsc123, 2x dvewfve, 1x scvscs', 162406.00, 'New', NULL, '2025-10-14 04:34:10', '2025-10-15 05:43:40', '2025-10-14 10:34:10'),
(22, 7, 'Asia', '3x master2345, 2x master12345, 2x vdsv sv, 2x scvscs', 248356.00, 'Done', NULL, '2025-10-14 07:17:32', '2025-10-14 07:19:47', '2025-10-14 13:17:32');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_details`
--

CREATE TABLE `tbl_order_details` (
  `detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `item_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `subtotal` decimal(10,2) NOT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_order_details`
--

INSERT INTO `tbl_order_details` (`detail_id`, `order_id`, `menu_id`, `item_name`, `price`, `quantity`, `subtotal`, `notes`, `created_at`) VALUES
(3, 2, NULL, 'Fries', 1234.00, 2, 2468.00, NULL, '2025-10-13 10:56:35'),
(4, 2, NULL, 'Burger', 1234.00, 3, 3702.00, NULL, '2025-10-13 10:56:35'),
(5, 3, NULL, 'swvsv', 1213.00, 1, 1213.00, NULL, '2025-10-13 12:09:38'),
(6, 3, NULL, 'swvsv', 1213.00, 1, 1213.00, NULL, '2025-10-13 12:09:38'),
(7, 3, NULL, 'master', 234.00, 1, 234.00, NULL, '2025-10-13 12:09:38'),
(8, 4, NULL, 'swvsv', 1213.00, 3, 3639.00, NULL, '2025-10-13 12:13:20'),
(9, 4, NULL, 'swvsv', 1213.00, 3, 3639.00, NULL, '2025-10-13 12:13:20'),
(10, 4, NULL, 'master', 234.00, 5, 1170.00, NULL, '2025-10-13 12:13:20'),
(11, 5, NULL, 'swvsv', 1213.00, 1, 1213.00, NULL, '2025-10-13 12:24:54'),
(12, 5, NULL, 'master', 234.00, 1, 234.00, NULL, '2025-10-13 12:24:54'),
(13, 6, NULL, 'swvsv', 1213.00, 1, 1213.00, NULL, '2025-10-13 12:28:09'),
(14, 6, NULL, 'swvsv', 1213.00, 1, 1213.00, NULL, '2025-10-13 12:28:09'),
(15, 6, NULL, 'master', 234.00, 1, 234.00, NULL, '2025-10-13 12:28:09'),
(16, 7, NULL, 'swvsv', 1213.00, 1, 1213.00, NULL, '2025-10-13 12:30:32'),
(17, 7, NULL, 'swvsv', 1213.00, 1, 1213.00, NULL, '2025-10-13 12:30:32'),
(18, 7, NULL, 'master', 234.00, 1, 234.00, NULL, '2025-10-13 12:30:32'),
(19, 8, NULL, 'swvsv', 1213.00, 3, 3639.00, NULL, '2025-10-13 12:39:08'),
(20, 8, NULL, 'swvsv', 1213.00, 3, 3639.00, NULL, '2025-10-13 12:39:08'),
(21, 8, NULL, 'master', 234.00, 1, 234.00, NULL, '2025-10-13 12:39:08'),
(22, 16, 19, 'vdsv sv', 126.00, 1, 126.00, NULL, '2025-10-13 17:59:36'),
(23, 16, 17, 'scvscs', 123467.00, 1, 123467.00, NULL, '2025-10-13 17:59:36'),
(24, 16, 16, 'scsc', 12135.00, 3, 36405.00, NULL, '2025-10-13 17:59:36'),
(25, 17, 16, 'scsc', 12135.00, 5, 60675.00, NULL, '2025-10-13 18:03:20'),
(26, 18, 19, 'vdsv sv', 126.00, 3, 378.00, NULL, '2025-10-13 18:08:21'),
(27, 18, 16, 'scsc123', 12135.00, 3, 36405.00, NULL, '2025-10-13 18:08:21'),
(28, 19, 21, 'master', 23.00, 1, 23.00, NULL, '2025-10-13 20:46:41'),
(29, 19, 19, 'vdsv sv', 125.00, 1, 125.00, NULL, '2025-10-13 20:46:41'),
(30, 19, 17, 'scvscs', 123467.00, 1, 123467.00, NULL, '2025-10-13 20:46:41'),
(34, 22, 21, 'master2345', 234.00, 3, 702.00, NULL, '2025-10-14 07:17:32'),
(35, 22, 20, 'master12345', 235.00, 2, 470.00, NULL, '2025-10-14 07:17:32'),
(36, 22, 19, 'vdsv sv', 125.00, 2, 250.00, NULL, '2025-10-14 07:17:32'),
(37, 22, 17, 'scvscs', 123467.00, 2, 246934.00, NULL, '2025-10-14 07:17:32'),
(41, 20, NULL, 'master', 23.00, 1, 23.00, NULL, '2025-10-15 03:33:04'),
(42, 20, 19, 'vdsv sv', 125.00, 1, 125.00, NULL, '2025-10-15 03:33:04'),
(43, 20, 21, 'master2345', 234.00, 1, 234.00, NULL, '2025-10-15 03:33:04'),
(53, 21, 16, 'scsc123', 12135.00, 3, 36405.00, NULL, '2025-10-15 05:43:40'),
(54, 21, 18, 'dvewfve', 1267.00, 2, 2534.00, NULL, '2025-10-15 05:43:40'),
(55, 21, 17, 'scvscs', 123467.00, 1, 123467.00, NULL, '2025-10-15 05:43:40');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_signup`
--

CREATE TABLE `tbl_signup` (
  `signup_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `age` int(3) NOT NULL,
  `sex` enum('Male','Female') NOT NULL,
  `birthday` date NOT NULL,
  `role` enum('owner','staff') NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_signup`
--

INSERT INTO `tbl_signup` (`signup_id`, `fullname`, `username`, `age`, `sex`, `birthday`, `role`, `phone_number`, `email`, `profile_image`, `password`, `created_at`) VALUES
(2, 'Ryan Jay Tagolimot Reyes', 'ryanreye', 21, 'Male', '2025-10-18', 'owner', '09358554398', 'ryanjaytagolimotreyes@gmail.com', '843a76e10d259ae42c5b65d3581a381a.png', '$2y$10$ZG7Mc4D2NrWefadD3gV9a.4h0lmogA8ydr940yEBZLYLFSiVV9lgq', '2025-10-11 15:58:26'),
(3, 'Ryan Jay Tagolimot Reyes', 'ryan', 35, 'Male', '2025-09-30', 'staff', '09358554398', 'ryanjaytagolimotreyes123@gmail.com', NULL, '$2y$10$GtHeEonNjTO18D8/M02NEurjXSSVQudW7bvOs7WsSMV9Wst5qwEra', '2025-10-11 16:05:14'),
(4, 'Ryan Jay Tagolimot Reyes', 'nayr', 35, 'Male', '2025-09-30', 'owner', '09358554398', 'ryanjay123@gmail.com', NULL, '$2y$10$6eO9y0BVgWl5XvzCUhAPwONj0zeSU2IRygOACA.gx0T8E.xJaF1wy', '2025-10-11 16:06:19'),
(5, 'ewe', 'efwf', 23, 'Male', '2025-10-13', 'owner', '09358554398', 'ryan@gmail.com', '2ad664a59996186c4c9284df8a1749ce.jpg', '$2y$10$JDBocXPU6yv3FaXqvFKPIOovS3gWNVCsmctRHYckRvoj5ff8G0Wum', '2025-10-13 03:42:14'),
(6, 'rwgsevwe', 'wfwf', 25, 'Male', '2025-10-13', 'staff', '09358554398', 'ryanjay12345@gmail.com', NULL, '$2y$10$Q7LbkL2yy.mm/j1lVtN3.e2pRcW18GjWn7Pa67begKP7Zmff5P5Zq', '2025-10-13 03:56:57'),
(7, 'Asia Shandara Valmorida', 'asia', 22, 'Female', '2025-10-14', 'owner', '09358554398', 'asia@gmail.com', NULL, '$2y$10$xCTgo3PqO4MNf1npg3zUUOj4R9fMPskPCq6Bt8g5gbLkbrj0uBPkS', '2025-10-14 07:15:31'),
(12, 'test name', 'test123', 25, 'Male', '2025-10-15', 'staff', '09358554398', 'test@gmail.com', '4e1680ba08cc10fcd07846b48bff3f85.jpg', '$2y$10$xnuZ1C4mdlZ5HCFVAzawseVxFu77efIu6OSUnZeg846ZZEG94jyQS', '2025-10-15 02:36:39'),
(13, 'Ruby', 'ruby123', 24, 'Female', '2025-10-15', 'staff', '09358554398', 'ruby@gmail.com', '1bba73286e1b891b5891041fbf279b34.jpg', '$2y$10$.IMJNDAIBAqbmwHgRqjaG.bP3if9vwT.9RevNYZgeGpyMRLQCtqIy', '2025-10-15 03:50:49'),
(14, 'Ashlee Nicole Mabayo', 'ashlee123', 24, 'Female', '2025-10-15', 'staff', '09358554398', 'ashlee@gmail.com', 'cfde1af6650a5c399e06a18a26993b84.jpg', '$2y$10$BlOMJ8bMKSRI0zCp4Qki5.mfnqWDA.7SRqR6RVJC5YArFh.U6Vo4u', '2025-10-15 03:54:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu_tbl`
--
ALTER TABLE `menu_tbl`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles_tbl`
--
ALTER TABLE `roles_tbl`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `tbl_inventory_details`
--
ALTER TABLE `tbl_inventory_details`
  ADD PRIMARY KEY (`inventory_details_id`),
  ADD KEY `idx_item_id` (`item_id`);

--
-- Indexes for table `tbl_menu_items`
--
ALTER TABLE `tbl_menu_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `tbl_order_details`
--
ALTER TABLE `tbl_order_details`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `tbl_signup`
--
ALTER TABLE `tbl_signup`
  ADD PRIMARY KEY (`signup_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu_tbl`
--
ALTER TABLE `menu_tbl`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `roles_tbl`
--
ALTER TABLE `roles_tbl`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_inventory_details`
--
ALTER TABLE `tbl_inventory_details`
  MODIFY `inventory_details_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tbl_menu_items`
--
ALTER TABLE `tbl_menu_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tbl_order_details`
--
ALTER TABLE `tbl_order_details`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `tbl_signup`
--
ALTER TABLE `tbl_signup`
  MODIFY `signup_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_menu_items`
--
ALTER TABLE `tbl_menu_items`
  ADD CONSTRAINT `tbl_menu_items_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu_tbl` (`menu_id`);

--
-- Constraints for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD CONSTRAINT `tbl_orders_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `tbl_signup` (`signup_id`);

--
-- Constraints for table `tbl_order_details`
--
ALTER TABLE `tbl_order_details`
  ADD CONSTRAINT `tbl_order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `tbl_orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_order_details_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu_tbl` (`menu_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
