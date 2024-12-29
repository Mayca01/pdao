-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 17, 2024 at 02:54 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pdao`
--

-- --------------------------------------------------------

--
-- Table structure for table `assistance`
--

CREATE TABLE `assistance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `assistance` varchar(255) NOT NULL,
  `applied_date` datetime DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `remarks` varchar(50) DEFAULT NULL,
  `reason` varchar(50) DEFAULT NULL,
  `approver_reason` varchar(100) DEFAULT NULL,
  `uploaded_requirements` text DEFAULT NULL,
  `claimed_date` datetime DEFAULT NULL,
  `is_claim` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assistance`
--

INSERT INTO `assistance` (`id`, `user_id`, `assistance`, `applied_date`, `status`, `remarks`, `reason`, `approver_reason`, `uploaded_requirements`, `claimed_date`, `is_claim`) VALUES
(23, 40, 'Food Assistance', NULL, 'Approved', 'Application is approved, Please bring your require', 'Need food assistance', NULL, '462930689_122144863634297447_3015813733421243057_n.jpg, 462941204_2024990747921148_6223950899342705910_n.jpg', '2024-12-02 16:10:27', 1),
(25, 40, 'Food Assistance', '2024-12-02 13:11:33', 'Pending', 'Application is pending, Please comply your other r', 'no food', 'kulang ug requirements', '439907356_385519131154878_9106002603555381544_n.jpg', NULL, 0),
(26, 40, 'Food Assistance', '2024-12-10 17:19:19', 'Pending', NULL, 'ttteesst', NULL, 'MedCert_67580797259b3.jpg, MedCert_6758079725db6.jpg, MedCert_6758079725fcc.jpg, MedCert_67580797261af.jpg', NULL, 0),
(27, 44, 'Cash Assistance', '2024-12-11 09:05:57', 'Disapproved', 'Application is disapproved, Please try again.', 'test', 'file not found', 'MedCert_6758e575e005e.PNG', NULL, 0),
(31, 44, 'Cash Assistance', '2024-12-11 09:20:15', 'Pending', NULL, 'test', NULL, 'MedCert_6758e8cf2f6db.png', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `disability_equipments`
--

CREATE TABLE `disability_equipments` (
  `id` int(11) NOT NULL,
  `equipment_id` int(11) NOT NULL,
  `disability` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `disability_equipments`
--

INSERT INTO `disability_equipments` (`id`, `equipment_id`, `disability`) VALUES
(18, 7, 'Hearing Impairment'),
(22, 7, 'Vision Impairment'),
(23, 5, 'Vision Impairment'),
(26, 7, 'Cerebral Palsy'),
(27, 5, 'Cerebral Palsy'),
(28, 7, 'Arthritis'),
(30, 7, 'Mental Illness'),
(31, 5, 'Mental Illness'),
(32, 9, 'Cerebral Palsy'),
(33, 9, 'Cerebral Palsy'),
(37, 9, 'testing'),
(38, 9, 'Blindness'),
(40, 9, 'stroke'),
(41, 7, 'nabuang');

-- --------------------------------------------------------

--
-- Table structure for table `equipments`
--

CREATE TABLE `equipments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `equipment` varchar(255) DEFAULT NULL,
  `claim_status` int(11) DEFAULT NULL,
  `date_issued` datetime DEFAULT NULL,
  `date_claimed` datetime DEFAULT NULL,
  `released_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipments`
--

INSERT INTO `equipments` (`id`, `user_id`, `equipment`, `claim_status`, `date_issued`, `date_claimed`, `released_by`) VALUES
(33, 40, '9', 2, '2024-12-16 10:16:29', '2024-12-17 08:51:42', 1),
(38, 44, NULL, 0, NULL, NULL, NULL),
(41, 40, '9', 1, '2024-12-16 10:16:29', NULL, 1),
(44, 47, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `informations`
--

CREATE TABLE `informations` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `description` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `informations`
--

INSERT INTO `informations` (`id`, `title`, `image`, `description`, `date`) VALUES
(10, 'test', '673d6d7080b8e4.97811144.jpg', 'test', '2024-11-20 05:02:40'),
(13, 'testing', '674d9246637751.72502518.jpg, 674d92466542c5.00976681.png, 674d92466571d4.70113209.png', 'testing', '2024-12-02 10:56:06');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `equipment_id` int(11) NOT NULL,
  `equipment_name` varchar(30) NOT NULL,
  `stocks` int(11) NOT NULL,
  `remarks` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`equipment_id`, `equipment_name`, `stocks`, `remarks`) VALUES
(5, 'white cane', 14, 0),
(7, 'Wheelchairs', 4, 0),
(9, 'Hearing Aids', 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `inv_stock_logs`
--

CREATE TABLE `inv_stock_logs` (
  `id` int(11) NOT NULL,
  `equipment_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `incharge_user` int(11) NOT NULL,
  `trans_type` varchar(20) NOT NULL,
  `transaction_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `new_equipment_order`
--

CREATE TABLE `new_equipment_order` (
  `id` int(11) NOT NULL,
  `equipment_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `order_date` datetime DEFAULT NULL,
  `expected_arrived_date` date DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `rcvd_date` datetime DEFAULT NULL,
  `rcvd_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `new_equipment_order`
--

INSERT INTO `new_equipment_order` (`id`, `equipment_id`, `qty`, `order_date`, `expected_arrived_date`, `status`, `rcvd_date`, `rcvd_by`) VALUES
(1, 5, 5, '2024-12-03 18:19:43', '2024-12-04', 1, '2024-12-04 18:26:27', 40),
(2, 7, 1, '2024-12-04 18:02:57', '2024-12-04', 1, '2024-12-04 18:25:45', 40),
(3, 5, 2, '2024-12-10 17:54:03', '2024-12-30', NULL, NULL, 40),
(4, 7, 1, '2024-12-11 08:57:38', '2024-12-12', NULL, NULL, NULL),
(5, 9, 5, '2024-12-16 10:10:46', '2024-12-16', 1, '2024-12-16 10:15:27', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_type` tinyint(1) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `age` int(11) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `occupation` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `disability` varchar(255) NOT NULL,
  `medical_information` text NOT NULL,
  `support_needs` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL,
  `user_validated` tinyint(4) DEFAULT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_type`, `first_name`, `last_name`, `age`, `barangay`, `address`, `occupation`, `contact_person`, `contact_number`, `email`, `disability`, `medical_information`, `support_needs`, `username`, `password`, `status`, `user_validated`, `reset_token`, `reset_token_expires`) VALUES
(1, 1, 'Admin', 'Admin', 0, '', '', '', '', '', '', '', '', '', 'admin', 'admin', '', NULL, NULL, NULL),
(40, 0, 'juan', 'dela cruz', 21, 'Barangay 1', 'test sitio', 'farmer', 'pedro dela cruz', '09123456789', 'rteves29@gmail.com', 'Blindness', './public/img/medical-informations/67443f43667ba0.74758598.jpg', '', 'jdelacruz', 'Password2026', 'Active', 1, '56d1adaf9854cd9d948a2a61b8043f22', '2024-11-25 18:22:19'),
(43, 1, 'admin1', 'admin1', 0, '', '', '', '', '', 'admin2@gmail.com', '', '', '', 'admin2', 'Administrator1', '', NULL, NULL, NULL),
(44, 0, 'test3', 'test3', 21, 'Barangay 1', 'dasdasd', 'adasdasd', 'adsadasd', '12312312312', 'sdada@gmail.com', 'Stroke', './public/img/medical-informations/675261cb286e50.92943025.jpg', '', 'test3', 'Password2026', 'Active', 1, NULL, NULL),
(47, 0, 'test5', 'test5', 21, 'Barangay 1', 'testttttt', 'none', 'test', '12345678901', 'test5@gmail.com', 'napi-ang', './public/img/medical-informations/Medcert_6756b07c1701d6.71610756.jpg, ./public/img/medical-informations/Medcert_6756b07c183069.26081109.jpg, ./public/img/medical-informations/Medcert_6756b07c1879e9.55274077.jpg, ./public/img/medical-informations/Medcert_6756b07c18c712.77505538.jpg, ./public/img/medical-informations/Medcert_6756b07c195f27.11567720.jpg, ./public/img/medical-informations/Medcert_6756b07c19b499.11167663.jpg', '', 'test5', 'Password2024', 'Active', 1, NULL, NULL),
(48, 0, 'test6', 'test6', 25, 'Barangay 1', 'testttt', 'none', 'test5', '12345678901', 'test6@gmail.com', 'nabuang', 'Medcert_6757e6fb98d495.70122292.jpg, Medcert_6757e6fb992024.29394300.jpg, Medcert_6757e6fb994870.40770977.jpg, Medcert_6757e6fb996899.14685620.jpg, Medcert_6757e6fb999145.08435381.jpg, Medcert_6757e6fb9a2ae6.62039881.jpg', '', 'test6', 'Password2024', 'Active', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assistance`
--
ALTER TABLE `assistance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disability_equipments`
--
ALTER TABLE `disability_equipments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `equipments`
--
ALTER TABLE `equipments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `informations`
--
ALTER TABLE `informations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`equipment_id`);

--
-- Indexes for table `inv_stock_logs`
--
ALTER TABLE `inv_stock_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `new_equipment_order`
--
ALTER TABLE `new_equipment_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assistance`
--
ALTER TABLE `assistance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `disability_equipments`
--
ALTER TABLE `disability_equipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `equipments`
--
ALTER TABLE `equipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `informations`
--
ALTER TABLE `informations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `equipment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `inv_stock_logs`
--
ALTER TABLE `inv_stock_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `new_equipment_order`
--
ALTER TABLE `new_equipment_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
