-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 21, 2024 at 05:57 PM
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
-- Database: `db_freelance`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`id`, `name`, `email`, `password`, `created_at`, `created_by`) VALUES
(1, 'mesum', 'masumbinshaukat@gmail.com', 'hello123', '2024-07-14 20:34:45', 'developer');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_banned_user`
--

CREATE TABLE `tbl_banned_user` (
  `id` int(11) NOT NULL,
  `bann_user_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `time_period` date NOT NULL,
  `banned_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bids`
--

CREATE TABLE `tbl_bids` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bid_letter` longtext NOT NULL,
  `bid_date` datetime NOT NULL DEFAULT current_timestamp(),
  `bid_price` int(11) NOT NULL,
  `project_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_completion`
--

CREATE TABLE `tbl_completion` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `final_cost` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_images`
--

CREATE TABLE `tbl_images` (
  `id` int(11) NOT NULL,
  `image` varchar(1000) NOT NULL,
  `user_id` int(11) NOT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_messages`
--

CREATE TABLE `tbl_messages` (
  `id` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `freelancer_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `attachments` varchar(1000) DEFAULT NULL,
  `date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_niche`
--

CREATE TABLE `tbl_niche` (
  `id` int(11) NOT NULL,
  `cat_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_niche`
--

INSERT INTO `tbl_niche` (`id`, `cat_name`) VALUES
(1, 'Copywriting'),
(2, 'Digital Marketing');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projects`
--

CREATE TABLE `tbl_projects` (
  `id` int(11) NOT NULL,
  `project_title` varchar(100) NOT NULL,
  `project_desc` longtext NOT NULL,
  `project_deadline` date NOT NULL,
  `project_fee` varchar(100) NOT NULL,
  `attachments` longtext NOT NULL,
  `cat_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(15) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_projects`
--

INSERT INTO `tbl_projects` (`id`, `project_title`, `project_desc`, `project_deadline`, `project_fee`, `attachments`, `cat_id`, `u_id`, `created_at`, `status`) VALUES
(3, 'Ecommerce Store Marketing', 'I need a digital marketing expert for my ecommerce online store.', '2024-07-31', '0.03', '669d279b26cd6_merged.pdf', 2, 2, '2024-07-21 20:22:03', 'Not Awarded'),
(4, 'Copywriting Expert Needed Urgent', 'I need a copywriter for my ecommerce site. 1 year experience is required.', '2024-08-30', '0.03', '669d2889a0791_readme.txt', 1, 2, '2024-07-21 20:26:01', 'Not Awarded');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_assigned`
--

CREATE TABLE `tbl_project_assigned` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `assigned_date` datetime NOT NULL DEFAULT current_timestamp(),
  `project_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `name`, `email`, `password`, `dob`, `created_at`) VALUES
(1, 'Tester', 'test@gmail.com', '$2y$10$UpFl6IdPtY3UjH5jRJJ3/OWphyuByJgwh3fPJ.heRdZgsBU0JRJgm', '2001-02-25', '2024-07-11 12:48:32'),
(2, 'Tester 2', 'test2@gmail.com', '$2y$10$7WimviBiyU.BQiPY/a/GQOzUSO/6D0oJdtMFrvV5B5bBxutqdTquq', '2005-08-25', '2024-07-21 18:35:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tbl_banned_user`
--
ALTER TABLE `tbl_banned_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_key_10` (`admin_id`),
  ADD KEY `fk_key_11` (`bann_user_id`);

--
-- Indexes for table `tbl_bids`
--
ALTER TABLE `tbl_bids`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_key_06` (`project_id`),
  ADD KEY `fk_key_07` (`user_id`);

--
-- Indexes for table `tbl_completion`
--
ALTER TABLE `tbl_completion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_key_12` (`project_id`),
  ADD KEY `fk_key_13` (`user_id`);

--
-- Indexes for table `tbl_images`
--
ALTER TABLE `tbl_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_key_01` (`user_id`);

--
-- Indexes for table `tbl_messages`
--
ALTER TABLE `tbl_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_key_08` (`client_id`),
  ADD KEY `fk_key_09` (`freelancer_id`);

--
-- Indexes for table `tbl_niche`
--
ALTER TABLE `tbl_niche`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cat_name` (`cat_name`);

--
-- Indexes for table `tbl_projects`
--
ALTER TABLE `tbl_projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_key_02` (`cat_id`),
  ADD KEY `fk_key_03` (`u_id`);

--
-- Indexes for table `tbl_project_assigned`
--
ALTER TABLE `tbl_project_assigned`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_key_04` (`project_id`),
  ADD KEY `fk_key_05` (`user_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_banned_user`
--
ALTER TABLE `tbl_banned_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_bids`
--
ALTER TABLE `tbl_bids`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_completion`
--
ALTER TABLE `tbl_completion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_images`
--
ALTER TABLE `tbl_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_messages`
--
ALTER TABLE `tbl_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_niche`
--
ALTER TABLE `tbl_niche`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_projects`
--
ALTER TABLE `tbl_projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_project_assigned`
--
ALTER TABLE `tbl_project_assigned`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_banned_user`
--
ALTER TABLE `tbl_banned_user`
  ADD CONSTRAINT `fk_key_10` FOREIGN KEY (`admin_id`) REFERENCES `tbl_admin` (`id`),
  ADD CONSTRAINT `fk_key_11` FOREIGN KEY (`bann_user_id`) REFERENCES `tbl_user` (`id`);

--
-- Constraints for table `tbl_bids`
--
ALTER TABLE `tbl_bids`
  ADD CONSTRAINT `fk_key_06` FOREIGN KEY (`project_id`) REFERENCES `tbl_projects` (`id`),
  ADD CONSTRAINT `fk_key_07` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`);

--
-- Constraints for table `tbl_completion`
--
ALTER TABLE `tbl_completion`
  ADD CONSTRAINT `fk_key_12` FOREIGN KEY (`project_id`) REFERENCES `tbl_projects` (`id`),
  ADD CONSTRAINT `fk_key_13` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`);

--
-- Constraints for table `tbl_images`
--
ALTER TABLE `tbl_images`
  ADD CONSTRAINT `fk_key_01` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`);

--
-- Constraints for table `tbl_messages`
--
ALTER TABLE `tbl_messages`
  ADD CONSTRAINT `fk_key_08` FOREIGN KEY (`client_id`) REFERENCES `tbl_user` (`id`),
  ADD CONSTRAINT `fk_key_09` FOREIGN KEY (`freelancer_id`) REFERENCES `tbl_user` (`id`);

--
-- Constraints for table `tbl_projects`
--
ALTER TABLE `tbl_projects`
  ADD CONSTRAINT `fk_key_02` FOREIGN KEY (`cat_id`) REFERENCES `tbl_niche` (`id`),
  ADD CONSTRAINT `fk_key_03` FOREIGN KEY (`u_id`) REFERENCES `tbl_user` (`id`);

--
-- Constraints for table `tbl_project_assigned`
--
ALTER TABLE `tbl_project_assigned`
  ADD CONSTRAINT `fk_key_04` FOREIGN KEY (`project_id`) REFERENCES `tbl_projects` (`id`),
  ADD CONSTRAINT `fk_key_05` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
