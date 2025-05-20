-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: May 20, 2025 at 02:07 PM
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
-- Database: `hr1`
--

-- --------------------------------------------------------

--
-- Table structure for table `applicants`
--

CREATE TABLE `applicants` (
  `applicant_id` int(11) NOT NULL,
  `tracking_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `contact_info` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` varchar(100) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applicants`
--

INSERT INTO `applicants` (`applicant_id`, `tracking_id`, `name`, `contact_info`, `email`, `status`) VALUES
(5, 'APP-A638ED', 'Jazz', 'jazz@gmail.com', NULL, 'Hired'),
(6, 'APP-8FAA68', 'Nelle', 'nelle@gmail.com', NULL, 'Interviewing'),
(7, 'APP-63806D', 'Vince', 'vince@gmail.com', NULL, 'Screening'),
(11, 'APP-83C2AA', 'Baliw', 'baliw@gmail.com', NULL, 'Rejected');

-- --------------------------------------------------------

--
-- Table structure for table `contractpolicyacknowledgement`
--

CREATE TABLE `contractpolicyacknowledgement` (
  `AcknowledgementID` int(11) NOT NULL,
  `EmployeeID` int(11) DEFAULT NULL,
  `PolicyID` int(11) DEFAULT NULL,
  `PolicyName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departmentroleassignment`
--

CREATE TABLE `departmentroleassignment` (
  `AssignmentID` int(11) NOT NULL,
  `DepartmentID` int(11) DEFAULT NULL,
  `EmployeeID` int(11) DEFAULT NULL,
  `JobRole` varchar(100) DEFAULT NULL,
  `SystemName` varchar(100) DEFAULT NULL,
  `DepartmentName` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `document_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `upload_status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`document_id`, `applicant_id`, `file_name`, `file_path`, `upload_date`, `upload_status`) VALUES
(5, 5, 'SMSpre.docx', 'uploads/1747232851_SMSpre.docx', '2025-05-14 14:27:31', 'Uploaded'),
(6, 6, 'Page21PMP.docx', 'uploads/1747232858_Page21PMP.docx', '2025-05-14 14:27:38', 'Uploaded'),
(7, 7, 'page21only.docx', 'uploads/1747232865_page21only.docx', '2025-05-14 14:27:45', 'Uploaded'),
(10, 11, 'Technical Writing.docx', 'uploads/1747240137_Technical_Writing.docx', '2025-05-14 16:28:57', 'Uploaded');

-- --------------------------------------------------------

--
-- Table structure for table `employeeprofilesetup`
--

CREATE TABLE `employeeprofilesetup` (
  `EmployeeID` int(11) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `EmploymentType` varchar(100) DEFAULT NULL,
  `StartDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employeeprofilesetup`
--

INSERT INTO `employeeprofilesetup` (`EmployeeID`, `FullName`, `EmploymentType`, `StartDate`) VALUES
(1, 'john dale', 'Nurse', '2025-05-22');

-- --------------------------------------------------------

--
-- Table structure for table `interviews`
--

CREATE TABLE `interviews` (
  `interview_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `interview_date` datetime DEFAULT NULL,
  `interview_status` varchar(100) DEFAULT 'Scheduled',
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `interviews`
--

INSERT INTO `interviews` (`interview_id`, `applicant_id`, `interview_date`, `interview_status`, `notes`) VALUES
(4, 7, '2025-05-15 12:30:00', 'Scheduled', 'Be on time.'),
(5, 5, '2025-05-14 22:28:00', 'Completed', 'Congratulations!'),
(7, 11, '2025-05-15 00:29:00', 'Cancelled', 'Sorry.');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `message_type` varchar(100) DEFAULT NULL,
  `message_content` text NOT NULL,
  `sent_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `onboardingtrainingorientation`
--

CREATE TABLE `onboardingtrainingorientation` (
  `TrainingID` int(11) NOT NULL,
  `TrainingName` varchar(255) NOT NULL,
  `Status` varchar(50) DEFAULT NULL,
  `EmployeeID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applicants`
--
ALTER TABLE `applicants`
  ADD PRIMARY KEY (`applicant_id`),
  ADD UNIQUE KEY `tracking_id` (`tracking_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `contractpolicyacknowledgement`
--
ALTER TABLE `contractpolicyacknowledgement`
  ADD PRIMARY KEY (`AcknowledgementID`),
  ADD KEY `EmployeeID` (`EmployeeID`);

--
-- Indexes for table `departmentroleassignment`
--
ALTER TABLE `departmentroleassignment`
  ADD PRIMARY KEY (`AssignmentID`),
  ADD KEY `EmployeeID` (`EmployeeID`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `applicant_id` (`applicant_id`);

--
-- Indexes for table `employeeprofilesetup`
--
ALTER TABLE `employeeprofilesetup`
  ADD PRIMARY KEY (`EmployeeID`);

--
-- Indexes for table `interviews`
--
ALTER TABLE `interviews`
  ADD PRIMARY KEY (`interview_id`),
  ADD KEY `applicant_id` (`applicant_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `applicant_id` (`applicant_id`);

--
-- Indexes for table `onboardingtrainingorientation`
--
ALTER TABLE `onboardingtrainingorientation`
  ADD PRIMARY KEY (`TrainingID`),
  ADD KEY `EmployeeID` (`EmployeeID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applicants`
--
ALTER TABLE `applicants`
  MODIFY `applicant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `contractpolicyacknowledgement`
--
ALTER TABLE `contractpolicyacknowledgement`
  MODIFY `AcknowledgementID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departmentroleassignment`
--
ALTER TABLE `departmentroleassignment`
  MODIFY `AssignmentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `employeeprofilesetup`
--
ALTER TABLE `employeeprofilesetup`
  MODIFY `EmployeeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `interviews`
--
ALTER TABLE `interviews`
  MODIFY `interview_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `onboardingtrainingorientation`
--
ALTER TABLE `onboardingtrainingorientation`
  MODIFY `TrainingID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contractpolicyacknowledgement`
--
ALTER TABLE `contractpolicyacknowledgement`
  ADD CONSTRAINT `contractpolicyacknowledgement_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employeeprofilesetup` (`EmployeeID`) ON DELETE CASCADE;

--
-- Constraints for table `departmentroleassignment`
--
ALTER TABLE `departmentroleassignment`
  ADD CONSTRAINT `departmentroleassignment_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employeeprofilesetup` (`EmployeeID`) ON DELETE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`) ON DELETE CASCADE;

--
-- Constraints for table `interviews`
--
ALTER TABLE `interviews`
  ADD CONSTRAINT `interviews_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`) ON DELETE CASCADE;

--
-- Constraints for table `onboardingtrainingorientation`
--
ALTER TABLE `onboardingtrainingorientation`
  ADD CONSTRAINT `onboardingtrainingorientation_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employeeprofilesetup` (`EmployeeID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
