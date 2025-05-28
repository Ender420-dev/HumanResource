-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 27, 2025 at 02:01 PM
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
-- Table structure for table `applicant`
--

CREATE TABLE `applicant` (
  `applicantID` int(11) NOT NULL,
  `jobpostingID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contactnumber` varchar(30) DEFAULT NULL,
  `applied_at` datetime DEFAULT NULL,
  `age` varchar(30) DEFAULT NULL,
  `sex` enum('Male','Female','Other') NOT NULL,
  `address` text DEFAULT NULL,
  `application_status` varchar(30) DEFAULT 'Pending',
  `applicantcol` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applicant`
--

INSERT INTO `applicant` (`applicantID`, `jobpostingID`, `name`, `email`, `contactnumber`, `applied_at`, `age`, `sex`, `address`, `application_status`, `applicantcol`) VALUES
(1, 1, 'Maria Santos', 'maria.santos@example.com', '09171234567', '2025-05-02 10:00:00', '28', 'Female', '123 Mabini St, Manila', 'Screened', 'APP001'),
(2, 31, 'John Doe', 'j@gmail.com', '09123456789', '2025-05-25 12:52:00', '32', 'Male', NULL, 'Pending', NULL),
(9, 33, 'Frank Russel', 'frank@gmail.com', '09456789123', '2025-05-26 16:19:00', '22', 'Male', NULL, 'Pending', NULL),
(10, 31, 'Mark', 'mark@gmail.com', '09123456789', '2025-05-26 17:12:00', '21', 'Male', NULL, 'Pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `appraisals`
--

CREATE TABLE `appraisals` (
  `id` int(11) NOT NULL,
  `employee_name` varchar(255) DEFAULT NULL,
  `review_period` varchar(7) DEFAULT NULL,
  `performance_rating` varchar(50) DEFAULT NULL,
  `comments` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appraisals`
--

INSERT INTO `appraisals` (`id`, `employee_name`, `review_period`, `performance_rating`, `comments`) VALUES
(9, 'Christian Flores', '2025-05', '5 = Exceptional', 'Outstanding.'),
(10, 'John Doe', '2025-06', '3 = Developing', 'In progress work, so to be followed review.'),
(11, 'Mark', '2025-05', '4 = Proficient', 'Did what he needs to do.');

-- --------------------------------------------------------

--
-- Table structure for table `compliancedocument`
--

CREATE TABLE `compliancedocument` (
  `complianceID` int(11) NOT NULL,
  `applicantID` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `submissionDate` datetime DEFAULT current_timestamp(),
  `remarks` text DEFAULT NULL,
  `status` enum('Pending','Submitted','Rejected','Verified') DEFAULT 'Submitted',
  `document` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `compliancedocument`
--

INSERT INTO `compliancedocument` (`complianceID`, `applicantID`, `file_path`, `document_name`, `submissionDate`, `remarks`, `status`, `document`) VALUES
(1, 1, 'uploads/nbi-clearance.pdf', 'NBI Clearance', '2025-05-03 15:00:00', 'Verified by HR', '', 'NBI'),
(2, 2, 'https://drive.google.com/drive/folders/1DFvuC_VXkPXLese4X10lsvCSckG4elT4?usp=drive_link', 'Resume', '2025-05-25 18:53:15', NULL, 'Submitted', 'uploads/compliance/6832f69b459e6_Untitled design (1).png'),
(5, 9, 'https://drive.google.com/drive/folders/1DFvuC_VXkPXLese4X10lsvCSckG4elT4?usp=drive_link', 'NBI', '2025-05-26 22:33:01', NULL, 'Submitted', 'uploads/compliance/68347b9d3c018_Untitled design (1).png'),
(6, 10, 'https://drive.google.com/file/d/1qSf9WCnseisIUoRTc9jkpJDSqH7ZSsvJ/view?usp=drive_link', 'Resume', '2025-05-26 23:13:42', NULL, 'Submitted', 'uploads/compliance/68348526b8c8c_tournament.png');

-- --------------------------------------------------------

--
-- Table structure for table `employeeprofilesetup`
--

CREATE TABLE `employeeprofilesetup` (
  `EmployeeID` varchar(50) NOT NULL,
  `FullName` varchar(255) DEFAULT NULL,
  `Gender` enum('Male','Female','Other') DEFAULT NULL,
  `Position` varchar(255) DEFAULT NULL,
  `Birthday` date DEFAULT NULL,
  `ApplicationDate` date DEFAULT NULL,
  `DocumentSubmitted` varchar(255) DEFAULT NULL,
  `AcquiredSkillsOrQualifications` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employeeprofilesetup`
--

INSERT INTO `employeeprofilesetup` (`EmployeeID`, `FullName`, `Gender`, `Position`, `Birthday`, `ApplicationDate`, `DocumentSubmitted`, `AcquiredSkillsOrQualifications`) VALUES
('1', 'Christian Flores', 'Male', 'Nurse', '2002-05-28', '2025-05-25', '../../uploads/employee_documents/68335fd032bba_CHILD NUTRITION CASE STUDY.docx', NULL),
('10', 'Mark', NULL, 'Scrum Master', '2025-05-27', '2025-05-26', '../../uploads/employee_documents/68349d3742c85_Untitled design (1).png', ''),
('2', 'John Doe', NULL, 'Scrum Master', '2025-05-27', '2025-05-26', '../../uploads/employee_documents/68349fefe4cc3_Untitled design (1).png', ''),
('9', 'Fank Russel', 'Male', 'House Keeping', '2003-01-01', '2025-05-27', '../../uploads/employee_documents/68349a2cda03f_tournament.png', '');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `feedback_text` text NOT NULL,
  `rating` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `recognition_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `program_id`, `employee_id`, `feedback_text`, `rating`, `timestamp`, `recognition_id`) VALUES
(1, 1, 1, 'Strong sense of hardwork and dedication.', 5, '2025-05-25 16:00:00', 1),
(3, 0, 9, 'Exceptional employee.', 5, '2025-05-26 16:00:00', 2);

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

CREATE TABLE `goals` (
  `id` int(11) NOT NULL,
  `employee_name` varchar(255) DEFAULT NULL,
  `goal_description` text DEFAULT NULL,
  `kpi` varchar(255) DEFAULT NULL,
  `target_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goals`
--

INSERT INTO `goals` (`id`, `employee_name`, `goal_description`, `kpi`, `target_date`) VALUES
(25, 'John Doe', 'Patient Tracking System', 'Number of patients tracked per period', '2025-06-30'),
(26, 'Mark', 'Refer the Doctors', 'Target community provinces.', '2025-05-19'),
(27, 'Christian Flores', 'Patient Care Excellence', 'Reduce hospital-acquired infections (HAIs) by 25% year-over-year', '2030-05-27');

-- --------------------------------------------------------

--
-- Table structure for table `jobposting`
--

CREATE TABLE `jobposting` (
  `jobpostingID` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `postingdate` date NOT NULL,
  `jobtype` varchar(50) NOT NULL,
  `department` varchar(100) NOT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobposting`
--

INSERT INTO `jobposting` (`jobpostingID`, `title`, `description`, `postingdate`, `jobtype`, `department`, `status`) VALUES
(1, 'Registered Nurse', 'Provide care to patients in the hospital.', '2025-05-01', 'Full-Time', 'Medical', 'Open'),
(30, 'Nurse', 'care for patient', '2025-05-18', 'Full-time', 'ER', 'Open'),
(31, 'Scrum Master', 'Opening for: Scrum Master\r\nDepartment: It Department\r\nVacancies: 80', '2025-05-25', 'Full-Time', 'It Department', 'Open'),
(33, 'House Keeping', 'Opening for: House Keeping\r\nDepartment: Support Departments\r\nVacancies: 20\r\n(Source: HR4 Request ID: 1)', '2025-05-26', 'Full-Time', 'Support Departments', 'Open');

-- --------------------------------------------------------

--
-- Table structure for table `offerapproval`
--

CREATE TABLE `offerapproval` (
  `offerID` int(11) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `position` varchar(100) DEFAULT NULL,
  `applicantID` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  `jobpostingID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offerapproval`
--

INSERT INTO `offerapproval` (`offerID`, `salary`, `status`, `position`, `applicantID`, `created_at`, `updated_at`, `jobpostingID`) VALUES
(1, 30000.00, 'Approved', 'Registered Nurse', 1, '2025-05-03 09:00:00', '2025-05-05 14:30:00', 1),
(2, 0.00, 'Pending', 'Scrum Master', 2, '2025-05-26 12:37:40', '2025-05-26 12:37:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `performance_feedback`
--

CREATE TABLE `performance_feedback` (
  `id` int(11) NOT NULL,
  `employee_name` varchar(255) DEFAULT NULL,
  `feedback_date` date DEFAULT NULL,
  `feedback_text` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `performance_feedback`
--

INSERT INTO `performance_feedback` (`id`, `employee_name`, `feedback_date`, `feedback_text`) VALUES
(0, 'Frank Russel', '2025-05-27', 'This employee is hardworking.');

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE `program` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `reward_type` varchar(50) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `target_department` varchar(100) DEFAULT 'All Departments',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`id`, `name`, `description`, `reward_type`, `status`, `start_date`, `end_date`, `target_department`, `created_at`) VALUES
(17, 'Excellence in Service', 'Recognizes healthcare professionals (nurses, doctors, therapists, etc.) who demonstrate exceptional compassion, skill, and dedication in patient care, going above and beyond to improve patient outcomes and experience.', 'Certificate', 'active', '2025-05-27', '2025-01-27', 'All Departments', '2025-05-27 09:42:19'),
(18, 'Patient Safety Champion', 'Recognizes individuals or teams who proactively identify and mitigate risks, implement best practices in patient safety, or contribute to a culture of safety within their unit or department.', 'Special Badge', 'active', '2025-05-27', '2025-07-27', 'All clinical and patient-facing departments.', '2025-05-27 09:43:24'),
(19, 'Years of Service', 'Acknowledges and celebrates employees for their long-term commitment and dedication to the hospital, recognizing milestones such as 5, 10, 15, 20+ years of service.', 'Recognition via Annual Event', 'draft', '2025-05-27', '2030-05-27', 'All Departments', '2025-05-27 09:44:28');

-- --------------------------------------------------------

--
-- Table structure for table `recognitions`
--

CREATE TABLE `recognitions` (
  `id` int(11) NOT NULL,
  `employee_image_path` varchar(255) DEFAULT NULL,
  `employee_name` varchar(255) NOT NULL,
  `department` varchar(100) NOT NULL,
  `reward_type` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recognitions`
--

INSERT INTO `recognitions` (`id`, `employee_image_path`, `employee_name`, `department`, `reward_type`, `message`, `created_at`) VALUES
(1, '../../uploads/recognition_documents/68341465ea44f_488386177_686654603772621_2171940384841416790_n.jpg', 'Christian Flores', 'Nursing Department', 'Certificate', 'Congratulations', '2025-05-26 06:46:43'),
(2, '../../uploads/recognition_documents/6835345a9c121_488788573_627145576985123_7226698283956167584_n.jpg', 'Frank Russel', 'Nursing Department', 'Promotion', 'Congratulations', '2025-05-27 03:41:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applicant`
--
ALTER TABLE `applicant`
  ADD PRIMARY KEY (`applicantID`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD KEY `fk_candidate_jobposting_idx` (`jobpostingID`);

--
-- Indexes for table `appraisals`
--
ALTER TABLE `appraisals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `compliancedocument`
--
ALTER TABLE `compliancedocument`
  ADD PRIMARY KEY (`complianceID`),
  ADD KEY `fk_applicant_compliance_idx` (`applicantID`);

--
-- Indexes for table `employeeprofilesetup`
--
ALTER TABLE `employeeprofilesetup`
  ADD PRIMARY KEY (`EmployeeID`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobposting`
--
ALTER TABLE `jobposting`
  ADD PRIMARY KEY (`jobpostingID`);

--
-- Indexes for table `offerapproval`
--
ALTER TABLE `offerapproval`
  ADD PRIMARY KEY (`offerID`),
  ADD KEY `fk_applicant_offer_idx` (`applicantID`),
  ADD KEY `fk_jobposting_offer_idx` (`jobpostingID`);

--
-- Indexes for table `performance_feedback`
--
ALTER TABLE `performance_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recognitions`
--
ALTER TABLE `recognitions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applicant`
--
ALTER TABLE `applicant`
  MODIFY `applicantID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `appraisals`
--
ALTER TABLE `appraisals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `compliancedocument`
--
ALTER TABLE `compliancedocument`
  MODIFY `complianceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `jobposting`
--
ALTER TABLE `jobposting`
  MODIFY `jobpostingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `offerapproval`
--
ALTER TABLE `offerapproval`
  MODIFY `offerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `recognitions`
--
ALTER TABLE `recognitions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applicant`
--
ALTER TABLE `applicant`
  ADD CONSTRAINT `fk_applicant_jobposting` FOREIGN KEY (`jobpostingID`) REFERENCES `jobposting` (`jobpostingID`);

--
-- Constraints for table `compliancedocument`
--
ALTER TABLE `compliancedocument`
  ADD CONSTRAINT `fk_applicant_compliance` FOREIGN KEY (`applicantID`) REFERENCES `applicant` (`applicantID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
