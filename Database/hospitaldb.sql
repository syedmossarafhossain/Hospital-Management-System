-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2026 at 11:54 AM
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
-- Database: `hospitaldb`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointmentId` varchar(20) NOT NULL,
  `patientName` varchar(100) NOT NULL,
  `doctor` varchar(100) NOT NULL,
  `department` enum('Cardiology','Neurology','Orthopedics','General Medicine') NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `type` enum('Consultation','Follow-up','Emergency') NOT NULL,
  `status` enum('Pending','Confirmed','Rejected') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointmentId`, `patientName`, `doctor`, `department`, `date`, `time`, `type`, `status`) VALUES
('A001', 'sahil', 'MD. WADUD', 'General Medicine', '2026-03-02', '15:46:00', 'Follow-up', 'Rejected'),
('A002', 'Rahul Sharma', 'Dr. John Smith', 'Cardiology', '2026-03-01', '10:30:00', 'Consultation', 'Pending'),
('A003', 'Priya Das', 'Dr. Emily Brown', 'Neurology', '2026-03-02', '12:00:00', 'Follow-up', 'Confirmed'),
('A004', 'Amit Roy', 'Dr. Michael Lee', 'Orthopedics', '2026-03-03', '09:15:00', 'Emergency', 'Rejected'),
('A005', 'Sneha Khan', 'Dr. Sarah Wilson', 'General Medicine', '2026-03-04', '14:00:00', 'Consultation', 'Confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `doctor_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('specialist','surgical') NOT NULL,
  `availability` enum('available','unavailable') NOT NULL,
  `image` varchar(255) DEFAULT 'images/doctor1.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doctor_id`, `name`, `type`, `availability`, `image`) VALUES
(1, 'S.M. ', 'specialist', 'available', 'images/doctor1.jpg'),
(2, 'John Smith', 'specialist', 'available', 'images/doctor1.jpg'),
(3, 'Emily Brown', 'surgical', 'available', 'images/doctor2.jpg'),
(4, 'Michael Lee', 'specialist', 'unavailable', 'images/doctor3.jpg'),
(5, 'Sarah Wilson', 'surgical', 'available', 'images/doctor4.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `laboratorys`
--

CREATE TABLE `laboratorys` (
  `testId` varchar(20) NOT NULL,
  `patientName` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `contact` varchar(15) NOT NULL,
  `testName` varchar(150) NOT NULL,
  `requestedBy` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `charges` decimal(10,2) NOT NULL,
  `status` enum('Pending','Completed','Analysis') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laboratorys`
--

INSERT INTO `laboratorys` (`testId`, `patientName`, `gender`, `contact`, `testName`, `requestedBy`, `date`, `charges`, `status`) VALUES
('T001', 'sahil', 'Female', '9907978608', 'X-Ray', 'DR. ND. Wadud', '2026-03-02', 1234.00, 'Analysis'),
('T002', 'Rahul Sharma', 'Male', '9876543210', 'Complete Blood Count (CBC)', 'Dr. John Smith', '2026-03-01', 500.00, 'Pending'),
('T003', 'Priya Das', 'Female', '9123456780', 'Lipid Profile', 'Dr. Emily Brown', '2026-03-02', 1200.00, 'Completed'),
('T004', 'Amit Roy', 'Male', '9988776655', 'Dengue Test', 'Dr. Michael Lee', '2026-03-03', 800.00, 'Analysis'),
('T005', 'Samim Khan', 'Female', '9090909090', 'MRI Scan', 'Dr. Sarah Wilson', '2026-03-04', 5000.00, 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `nurses`
--

CREATE TABLE `nurses` (
  `nurseId` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `contact` varchar(15) NOT NULL,
  `ward` enum('General','ICU') NOT NULL,
  `shift` enum('Day','Night') NOT NULL,
  `status` enum('On Duty','Off Duty') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nurses`
--

INSERT INTO `nurses` (`nurseId`, `name`, `gender`, `contact`, `ward`, `shift`, `status`) VALUES
('N001', 'Anita Sharma', 'Female', '9876543210', 'General', 'Day', 'On Duty'),
('N002', 'Rahul Das', 'Male', '9123456780', 'ICU', 'Night', 'Off Duty'),
('N003', 'Priya Singh', 'Female', '9988776655', 'ICU', 'Day', 'On Duty'),
('N004', 'Amit Roy', 'Male', '9090909090', 'General', 'Night', 'On Duty');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patientId` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date_in` date NOT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `contact` varchar(15) NOT NULL,
  `doctor` varchar(100) NOT NULL,
  `ward` enum('General','ICU') NOT NULL,
  `status` enum('Admitted','Discharged','Critical Cases') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patientId`, `name`, `date_in`, `age`, `gender`, `contact`, `doctor`, `ward`, `status`) VALUES
('P001', 'S.M. Hossain', '2026-03-01', 23, 'Female', '9907978965', 'MD. WADUD', 'General', 'Discharged'),
('P002', 'Amit Sharma', '2026-02-20', 35, 'Male', '9876543210', 'Dr. John Smith', 'General', 'Admitted'),
('P003', 'Priya Das', '2026-02-18', 28, 'Female', '9123456780', 'Dr. Emily Brown', 'ICU', 'Critical Cases'),
('P004', 'Rahul Roy', '2026-02-15', 45, 'Male', '9988776655', 'Dr. Michael Lee', 'General', 'Discharged'),
('P005', 'Sneha Khan', '2026-02-22', 32, 'Female', '9090909090', 'Dr. Sarah Wilson', 'ICU', 'Admitted');

-- --------------------------------------------------------

--
-- Table structure for table `staffs`
--

CREATE TABLE `staffs` (
  `staffId` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `contact` varchar(15) NOT NULL,
  `role` enum('Support Staff','Administrative Staff','Technical Staff') NOT NULL,
  `shift` enum('Day','Night') NOT NULL,
  `status` enum('Active','Inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staffs`
--

INSERT INTO `staffs` (`staffId`, `name`, `gender`, `contact`, `role`, `shift`, `status`) VALUES
('STF001', 'Rohit Kumar', 'Male', '9876543210', 'Support Staff', 'Day', 'Active'),
('STF002', 'Anjali Das', 'Female', '9123456780', 'Administrative Staff', 'Day', 'Active'),
('STF003', 'Suman Roy', 'Male', '9988776655', 'Technical Staff', 'Night', 'Inactive'),
('STF004', 'Pooja Sharma', 'Female', '9090909090', 'Support Staff', 'Night', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(1, 'Sahil', 'sahil@gmail.com', 'e10adc3949ba59abbe56e057f20f883e'),
(2, 'MD Wadud', 'mdwadud@gmail.com', '1dcfadfbc998b84077dee74952f47cd9'),
(3, 'Souvik', 'souvik@gmail.com', 'c33367701511b4f6020ec61ded352059');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointmentId`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`doctor_id`);

--
-- Indexes for table `laboratorys`
--
ALTER TABLE `laboratorys`
  ADD PRIMARY KEY (`testId`);

--
-- Indexes for table `nurses`
--
ALTER TABLE `nurses`
  ADD PRIMARY KEY (`nurseId`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patientId`);

--
-- Indexes for table `staffs`
--
ALTER TABLE `staffs`
  ADD PRIMARY KEY (`staffId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
