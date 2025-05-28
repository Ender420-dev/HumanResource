-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2025 at 12:17 PM
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
-- Database: `hr2`
--

-- --------------------------------------------------------

--
-- Table structure for table `competancy_development`
--

CREATE TABLE `competancy_development` (
  `PLAN_ID` int(11) NOT NULL,
  `EMPLOYEE_ID` varchar(45) DEFAULT NULL,
  `GOAL_DESCRIPTION` varchar(45) DEFAULT NULL,
  `ASSIGNED_TRAINING` int(45) DEFAULT NULL,
  `MILESTONE_START` date DEFAULT NULL,
  `MILESTONE_END` date NOT NULL,
  `STATUS` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `competancy_development`
--

INSERT INTO `competancy_development` (`PLAN_ID`, `EMPLOYEE_ID`, `GOAL_DESCRIPTION`, `ASSIGNED_TRAINING`, `MILESTONE_START`, `MILESTONE_END`, `STATUS`) VALUES
(2, '23123', 'asdasd', 5, '2025-05-31', '2025-07-09', 'In Progress');

-- --------------------------------------------------------

--
-- Table structure for table `competency_assessment`
--

CREATE TABLE `competency_assessment` (
  `ASSESSMENT_ID` int(11) NOT NULL,
  `EMPLOYEE` varchar(45) DEFAULT NULL,
  `ASSESSMENT_TYPE` varchar(45) DEFAULT NULL,
  `SCORE` int(11) DEFAULT NULL,
  `DATE` date DEFAULT NULL,
  `COMPETENCY` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `competency_assessment`
--

INSERT INTO `competency_assessment` (`ASSESSMENT_ID`, `EMPLOYEE`, `ASSESSMENT_TYPE`, `SCORE`, `DATE`, `COMPETENCY`) VALUES
(2, 'asdas123', 'Self', 51, '2025-05-15', 'Technical Skills');

-- --------------------------------------------------------

--
-- Table structure for table `competency_framework`
--

CREATE TABLE `competency_framework` (
  `COMPETENCY_ID` int(11) NOT NULL,
  `NAME` varbinary(45) DEFAULT NULL,
  `ROLE` varbinary(45) DEFAULT NULL,
  `DEPARTMENT` varbinary(45) DEFAULT NULL,
  `LASTUPDATE` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `DESCRIPTION` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=binary;

--
-- Dumping data for table `competency_framework`
--

INSERT INTO `competency_framework` (`COMPETENCY_ID`, `NAME`, `ROLE`, `DEPARTMENT`, `LASTUPDATE`, `DESCRIPTION`) VALUES
(2, 0x74657374313233, 0x495420537570706f7274, 0x4954, '2025-05-15 00:08:58', NULL);

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
-- Table structure for table `course_management`
--

CREATE TABLE `course_management` (
  `COURSE_ID` int(11) NOT NULL,
  `TRAINER` int(11) NOT NULL,
  `CALENDAR` int(11) NOT NULL,
  `STATUS_ID` varchar(255) DEFAULT NULL,
  `COURSE_TITLE` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `critical_position`
--

CREATE TABLE `critical_position` (
  `CRITICAL_ID` int(11) NOT NULL,
  `POSITION_TITLE` varchar(45) DEFAULT NULL,
  `DEPARTMENT` varchar(45) DEFAULT NULL,
  `INCUMBERT` varchar(45) DEFAULT NULL,
  `SUCCESSORS` varchar(45) DEFAULT NULL,
  `RISKLEVEL` varchar(45) DEFAULT NULL,
  `STATUS` varchar(45) DEFAULT NULL
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
-- Table structure for table `employeeprofilesetup`
--

CREATE TABLE `employeeprofilesetup` (
  `EmployeeID` int(11) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `EmploymentType` varchar(100) DEFAULT NULL,
  `StartDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_competency`
--

CREATE TABLE `employee_competency` (
  `PROFILE_ID` int(11) NOT NULL,
  `EMPLOYEE_ID` varchar(45) DEFAULT NULL,
  `COMPETECY` varchar(45) DEFAULT NULL,
  `PROFICIENCY` varchar(45) DEFAULT NULL,
  `CERTIFICATION` varchar(45) DEFAULT NULL,
  `LASTUPDATED` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_competency`
--

INSERT INTO `employee_competency` (`PROFILE_ID`, `EMPLOYEE_ID`, `COMPETECY`, `PROFICIENCY`, `CERTIFICATION`, `LASTUPDATED`) VALUES
(2, '225', 'test', 'testing', 'Certified', '2025-05-20');

-- --------------------------------------------------------

--
-- Table structure for table `employe_table`
--

CREATE TABLE `employe_table` (
  `EMPLOYEE_ID` int(11) NOT NULL,
  `FULLNAME` varchar(45) DEFAULT NULL,
  `GENDER` varchar(45) DEFAULT NULL,
  `POSITION` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employe_table`
--

INSERT INTO `employe_table` (`EMPLOYEE_ID`, `FULLNAME`, `GENDER`, `POSITION`) VALUES
(1, 'mary jane', 'femail', 'employee');

-- --------------------------------------------------------

--
-- Table structure for table `ess_attendance`
--

CREATE TABLE `ess_attendance` (
  `LEAVE_ID` int(11) NOT NULL,
  `DATE` date DEFAULT NULL,
  `EMPLOYEE_ID` varchar(45) DEFAULT NULL,
  `STATUS` varchar(45) DEFAULT NULL,
  `TIME_IN` time DEFAULT NULL,
  `TIME_OUT` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ess_leave`
--

CREATE TABLE `ess_leave` (
  `LEAVE_ID` int(11) NOT NULL,
  `EMPLOYEE_ID` varchar(45) DEFAULT NULL,
  `TYPE` varchar(45) DEFAULT NULL,
  `START_DATE` date DEFAULT NULL,
  `END_DATE` date DEFAULT NULL,
  `STATUS` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `learning_content`
--

CREATE TABLE `learning_content` (
  `LEARNING_ID` int(11) NOT NULL,
  `TRAINER` int(11) NOT NULL,
  `CALENDAR` int(11) DEFAULT NULL,
  `STATUS` varchar(255) DEFAULT NULL,
  `COURSE` int(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learning_content`
--

INSERT INTO `learning_content` (`LEARNING_ID`, `TRAINER`, `CALENDAR`, `STATUS`, `COURSE`) VALUES
(1, 7, 4, 'Ongoing', 4),
(2, 6, 4, 'Pending', 4);

-- --------------------------------------------------------

--
-- Table structure for table `learning_content_essay_submission`
--

CREATE TABLE `learning_content_essay_submission` (
  `ESSAY_ID` int(255) NOT NULL,
  `EMPLOYEE_ID` int(11) DEFAULT NULL,
  `PROGRAM_ID` int(11) DEFAULT NULL,
  `ESSAY` text DEFAULT NULL,
  `SUBMITTED_AT` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `GRADE` int(11) DEFAULT NULL,
  `TRAINER` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `learning_materials`
--

CREATE TABLE `learning_materials` (
  `LM_ID` int(11) NOT NULL,
  `PROGRAM_ID` int(11) DEFAULT NULL,
  `TEXT_BOOK` text DEFAULT NULL,
  `UPDATE_AT` timestamp NULL DEFAULT current_timestamp(),
  `TRAINER` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `learning_progress`
--

CREATE TABLE `learning_progress` (
  `LP_ID` int(255) NOT NULL,
  `EMPLOYEE_ID` int(255) DEFAULT NULL,
  `PROGRESS` int(3) DEFAULT NULL,
  `START` date DEFAULT NULL,
  `END` date DEFAULT NULL,
  `STATUS` varchar(255) DEFAULT NULL,
  `COURSE` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learning_progress`
--

INSERT INTO `learning_progress` (`LP_ID`, `EMPLOYEE_ID`, `PROGRESS`, `START`, `END`, `STATUS`, `COURSE`) VALUES
(1, 1, 50, '2025-05-28', '2025-06-05', 'Complete', 4),
(2, 1, 30, '2025-05-07', '2025-05-30', 'Complete', 5);

-- --------------------------------------------------------

--
-- Table structure for table `login_tbl`
--

CREATE TABLE `login_tbl` (
  `ID` int(11) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `user_role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_tbl`
--

INSERT INTO `login_tbl` (`ID`, `Email`, `Password`, `user_role`) VALUES
(9, 'hikaru', '123', 'admin'),
(10, 'admin', '123', 'admin'),
(11, 'employee', '123', 'user'),
(12, 'trainer', '123', 'trainer'),
(13, 'manager', '123', 'manager');

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

-- --------------------------------------------------------

--
-- Table structure for table `payroll_salary`
--

CREATE TABLE `payroll_salary` (
  `PAYROLL_ID` int(11) NOT NULL,
  `EMPLOYEE_ID` varchar(45) DEFAULT NULL,
  `LASTPAY_DATE` varchar(45) DEFAULT NULL,
  `TOTAL_SALARY` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `performance_review`
--

CREATE TABLE `performance_review` (
  `REVIEW_ID` int(11) NOT NULL,
  `EMPLOYEE_ID` varchar(45) DEFAULT NULL,
  `REVIEW_DATE` date DEFAULT NULL,
  `RATING` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skill-gap_analysis`
--

CREATE TABLE `skill-gap_analysis` (
  `GAP_ID` int(11) NOT NULL,
  `EMPLOYEE_ID` varchar(45) DEFAULT NULL,
  `COMPETENCY` varchar(45) DEFAULT NULL,
  `GAP_LEVEL` varchar(45) DEFAULT NULL,
  `RECOMMENDED_TRAINING` varchar(45) DEFAULT NULL,
  `TRAINING_DEADLINE` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skill_qualification`
--

CREATE TABLE `skill_qualification` (
  `SKILL_ID` int(255) NOT NULL,
  `EMPLOYEE_ID` int(11) DEFAULT NULL,
  `SKILL` varchar(255) DEFAULT NULL,
  `SKILL_LEVEL` varchar(255) DEFAULT NULL,
  `RECOMMENDED_TRAINING` varchar(255) DEFAULT NULL,
  `DEADLINE` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skill_qualification`
--

INSERT INTO `skill_qualification` (`SKILL_ID`, `EMPLOYEE_ID`, `SKILL`, `SKILL_LEVEL`, `RECOMMENDED_TRAINING`, `DEADLINE`) VALUES
(2, 22, 'IT', 'Normal', 'test', '2025-05-30');

-- --------------------------------------------------------

--
-- Table structure for table `succession_plan_dev`
--

CREATE TABLE `succession_plan_dev` (
  `SUCCESSION_ID` int(11) NOT NULL,
  `SUCCESION_NAME` varchar(45) DEFAULT NULL,
  `CURRENT_ROLE` varchar(45) DEFAULT NULL,
  `READINESS_LEVEL` varchar(45) DEFAULT NULL,
  `DEVELOPMENT_ACTIONS` varchar(45) DEFAULT NULL,
  `TARGET_READINESS_DATE` date DEFAULT NULL,
  `MENTOR_ASSIGNED` varchar(45) DEFAULT NULL,
  `PROGRESS` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `succession_plan_monitor`
--

CREATE TABLE `succession_plan_monitor` (
  `EVAL_ID` int(11) NOT NULL,
  `PLAN_ID` varchar(45) DEFAULT NULL,
  `EVALUATION_DATE` date DEFAULT NULL,
  `ADJUSTMENT` varchar(45) DEFAULT NULL,
  `EFFECTIVENESS_RATE` varchar(45) DEFAULT NULL,
  `NEXT_REVIEW` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `successor_identification`
--

CREATE TABLE `successor_identification` (
  `SUCCESSOR_ID` int(11) NOT NULL,
  `CANDIDATE_NAME` varchar(45) DEFAULT NULL,
  `CURRENT_POSITION` varchar(45) DEFAULT NULL,
  `PERFOMANCE` varchar(45) DEFAULT NULL,
  `POTENTIAL` varchar(45) DEFAULT NULL,
  `READINESS` varchar(45) DEFAULT NULL,
  `MANAGER_RECOM` varchar(45) DEFAULT NULL,
  `STATUS` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `talent_identification`
--

CREATE TABLE `talent_identification` (
  `TALENT_ID` int(11) NOT NULL,
  `EMPLOYEE` varchar(45) DEFAULT NULL,
  `DEPARTMENT` varchar(45) DEFAULT NULL,
  `CURRENT_ROLE` varchar(45) DEFAULT NULL,
  `SUCCESSOR` varchar(45) DEFAULT NULL,
  `READINESS` varchar(45) DEFAULT NULL,
  `POTENTIAL` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talent_identification`
--

INSERT INTO `talent_identification` (`TALENT_ID`, `EMPLOYEE`, `DEPARTMENT`, `CURRENT_ROLE`, `SUCCESSOR`, `READINESS`, `POTENTIAL`) VALUES
(3, 'asdas123', 'IT Department', 'IT', 'CTO', 'High', 'Exellent');

-- --------------------------------------------------------

--
-- Table structure for table `trainee_enrollment_approval`
--

CREATE TABLE `trainee_enrollment_approval` (
  `ENROLLMENT_ID` int(11) NOT NULL,
  `TRAINEE_ID` int(11) NOT NULL,
  `EMPLOYEE_ID` int(11) NOT NULL,
  `COURSE_PROGRAM` int(11) NOT NULL,
  `TRAINER` int(11) NOT NULL,
  `STATUS` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainee_enrollment_approval`
--

INSERT INTO `trainee_enrollment_approval` (`ENROLLMENT_ID`, `TRAINEE_ID`, `EMPLOYEE_ID`, `COURSE_PROGRAM`, `TRAINER`, `STATUS`) VALUES
(2, 1, 1, 4, 6, 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `trainee_table`
--

CREATE TABLE `trainee_table` (
  `TRAINEE_ID` int(11) NOT NULL,
  `FULLNAME` varchar(45) NOT NULL,
  `GENDER` varchar(45) NOT NULL,
  `POSITION` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainee_table`
--

INSERT INTO `trainee_table` (`TRAINEE_ID`, `FULLNAME`, `GENDER`, `POSITION`) VALUES
(1, 'John Cardolay', 'male', 'nurse');

-- --------------------------------------------------------

--
-- Table structure for table `trainer_faculty`
--

CREATE TABLE `trainer_faculty` (
  `TRAINER_ID` int(11) NOT NULL,
  `FULLNAME` varchar(45) DEFAULT NULL,
  `course` int(11) DEFAULT NULL,
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `create_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainer_faculty`
--

INSERT INTO `trainer_faculty` (`TRAINER_ID`, `FULLNAME`, `course`, `update_at`, `create_at`) VALUES
(3, 'john doeq', 4, '2025-05-14 11:01:18', '2025-05-14 10:31:09'),
(6, 'John cardo dalosay', 4, '2025-05-14 11:01:18', '2025-05-14 10:37:05'),
(7, 'john doeq', 4, '2025-05-14 11:01:18', '2025-05-14 10:31:09'),
(9, 'John cardo dalosay', 4, '2025-05-14 11:01:18', '2025-05-14 10:37:05');

-- --------------------------------------------------------

--
-- Table structure for table `training_calendar`
--

CREATE TABLE `training_calendar` (
  `CALENDAR_ID` int(11) NOT NULL,
  `PROGRAM` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `training_program`
--

CREATE TABLE `training_program` (
  `PROGRAM_ID` int(11) NOT NULL,
  `PROGRAM_TYPE` varchar(50) DEFAULT NULL,
  `PROGRAM_NAME` varchar(50) DEFAULT NULL,
  `TRAINER` int(11) NOT NULL,
  `START` date DEFAULT NULL,
  `END` date DEFAULT NULL,
  `STATUS` varchar(45) DEFAULT NULL,
  `DESCRIPTION_PROGRAM` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `training_program`
--

INSERT INTO `training_program` (`PROGRAM_ID`, `PROGRAM_TYPE`, `PROGRAM_NAME`, `TRAINER`, `START`, `END`, `STATUS`, `DESCRIPTION_PROGRAM`) VALUES
(4, 'emp101', 'employee 101', 6, '2025-05-22', '2025-05-28', 'Ongoing', ''),
(5, 'LTP101', 'Leadership Training Program', 6, '2025-05-03', '2025-05-31', 'Ongoing', '');

-- --------------------------------------------------------

--
-- Table structure for table `training_records`
--

CREATE TABLE `training_records` (
  `ID` int(11) NOT NULL,
  `TRAINER` int(11) NOT NULL,
  `PROGRAM_NAME` int(11) NOT NULL,
  `PROGRESS` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `competancy_development`
--
ALTER TABLE `competancy_development`
  ADD PRIMARY KEY (`PLAN_ID`),
  ADD KEY `training joint` (`ASSIGNED_TRAINING`);

--
-- Indexes for table `competency_assessment`
--
ALTER TABLE `competency_assessment`
  ADD PRIMARY KEY (`ASSESSMENT_ID`);

--
-- Indexes for table `competency_framework`
--
ALTER TABLE `competency_framework`
  ADD PRIMARY KEY (`COMPETENCY_ID`);

--
-- Indexes for table `contractpolicyacknowledgement`
--
ALTER TABLE `contractpolicyacknowledgement`
  ADD PRIMARY KEY (`AcknowledgementID`),
  ADD KEY `EmployeeID` (`EmployeeID`);

--
-- Indexes for table `course_management`
--
ALTER TABLE `course_management`
  ADD PRIMARY KEY (`COURSE_ID`),
  ADD KEY `FK_TRAINER3_idx` (`TRAINER`),
  ADD KEY `FK_CALENDAR2_idx` (`CALENDAR`);

--
-- Indexes for table `critical_position`
--
ALTER TABLE `critical_position`
  ADD PRIMARY KEY (`CRITICAL_ID`);

--
-- Indexes for table `departmentroleassignment`
--
ALTER TABLE `departmentroleassignment`
  ADD PRIMARY KEY (`AssignmentID`),
  ADD KEY `EmployeeID` (`EmployeeID`);

--
-- Indexes for table `employeeprofilesetup`
--
ALTER TABLE `employeeprofilesetup`
  ADD PRIMARY KEY (`EmployeeID`);

--
-- Indexes for table `employee_competency`
--
ALTER TABLE `employee_competency`
  ADD PRIMARY KEY (`PROFILE_ID`);

--
-- Indexes for table `employe_table`
--
ALTER TABLE `employe_table`
  ADD PRIMARY KEY (`EMPLOYEE_ID`);

--
-- Indexes for table `ess_attendance`
--
ALTER TABLE `ess_attendance`
  ADD PRIMARY KEY (`LEAVE_ID`);

--
-- Indexes for table `ess_leave`
--
ALTER TABLE `ess_leave`
  ADD PRIMARY KEY (`LEAVE_ID`);

--
-- Indexes for table `learning_content`
--
ALTER TABLE `learning_content`
  ADD PRIMARY KEY (`LEARNING_ID`),
  ADD KEY `FK_TRAINER2_idx` (`TRAINER`),
  ADD KEY `FK_CALENDAR1_idx` (`CALENDAR`),
  ADD KEY `COURSE` (`COURSE`);

--
-- Indexes for table `learning_content_essay_submission`
--
ALTER TABLE `learning_content_essay_submission`
  ADD PRIMARY KEY (`ESSAY_ID`),
  ADD KEY `PROGRAM_ID` (`PROGRAM_ID`),
  ADD KEY `TRAINER` (`TRAINER`);

--
-- Indexes for table `learning_materials`
--
ALTER TABLE `learning_materials`
  ADD PRIMARY KEY (`LM_ID`),
  ADD KEY `TRAINER` (`TRAINER`);

--
-- Indexes for table `learning_progress`
--
ALTER TABLE `learning_progress`
  ADD PRIMARY KEY (`LP_ID`),
  ADD KEY `COURSE` (`COURSE`),
  ADD KEY `EMPLOYEE_ID` (`EMPLOYEE_ID`);

--
-- Indexes for table `login_tbl`
--
ALTER TABLE `login_tbl`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `onboardingtrainingorientation`
--
ALTER TABLE `onboardingtrainingorientation`
  ADD PRIMARY KEY (`TrainingID`),
  ADD KEY `EmployeeID` (`EmployeeID`);

--
-- Indexes for table `payroll_salary`
--
ALTER TABLE `payroll_salary`
  ADD PRIMARY KEY (`PAYROLL_ID`);

--
-- Indexes for table `performance_review`
--
ALTER TABLE `performance_review`
  ADD PRIMARY KEY (`REVIEW_ID`);

--
-- Indexes for table `skill-gap_analysis`
--
ALTER TABLE `skill-gap_analysis`
  ADD PRIMARY KEY (`GAP_ID`);

--
-- Indexes for table `skill_qualification`
--
ALTER TABLE `skill_qualification`
  ADD PRIMARY KEY (`SKILL_ID`);

--
-- Indexes for table `succession_plan_dev`
--
ALTER TABLE `succession_plan_dev`
  ADD PRIMARY KEY (`SUCCESSION_ID`);

--
-- Indexes for table `succession_plan_monitor`
--
ALTER TABLE `succession_plan_monitor`
  ADD PRIMARY KEY (`EVAL_ID`);

--
-- Indexes for table `successor_identification`
--
ALTER TABLE `successor_identification`
  ADD PRIMARY KEY (`SUCCESSOR_ID`);

--
-- Indexes for table `talent_identification`
--
ALTER TABLE `talent_identification`
  ADD PRIMARY KEY (`TALENT_ID`);

--
-- Indexes for table `trainee_enrollment_approval`
--
ALTER TABLE `trainee_enrollment_approval`
  ADD PRIMARY KEY (`ENROLLMENT_ID`),
  ADD KEY `FK_EMPLOYEE_idx` (`EMPLOYEE_ID`),
  ADD KEY `FK_COURSE_PROGRAM1_idx` (`COURSE_PROGRAM`),
  ADD KEY `FK_TRAINER_1_idx` (`TRAINER`);

--
-- Indexes for table `trainee_table`
--
ALTER TABLE `trainee_table`
  ADD PRIMARY KEY (`TRAINEE_ID`);

--
-- Indexes for table `trainer_faculty`
--
ALTER TABLE `trainer_faculty`
  ADD PRIMARY KEY (`TRAINER_ID`),
  ADD KEY `trainer_faculty_ibfk_1` (`course`);

--
-- Indexes for table `training_calendar`
--
ALTER TABLE `training_calendar`
  ADD PRIMARY KEY (`CALENDAR_ID`),
  ADD KEY `FK_PROGRAM1` (`PROGRAM`);

--
-- Indexes for table `training_program`
--
ALTER TABLE `training_program`
  ADD PRIMARY KEY (`PROGRAM_ID`),
  ADD KEY `TRAINER` (`TRAINER`);

--
-- Indexes for table `training_records`
--
ALTER TABLE `training_records`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_TRAINER_idx` (`TRAINER`),
  ADD KEY `FK_PROGRAM_NAME_idx` (`PROGRAM_NAME`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `competancy_development`
--
ALTER TABLE `competancy_development`
  MODIFY `PLAN_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `competency_assessment`
--
ALTER TABLE `competency_assessment`
  MODIFY `ASSESSMENT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `competency_framework`
--
ALTER TABLE `competency_framework`
  MODIFY `COMPETENCY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contractpolicyacknowledgement`
--
ALTER TABLE `contractpolicyacknowledgement`
  MODIFY `AcknowledgementID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course_management`
--
ALTER TABLE `course_management`
  MODIFY `COURSE_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `critical_position`
--
ALTER TABLE `critical_position`
  MODIFY `CRITICAL_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departmentroleassignment`
--
ALTER TABLE `departmentroleassignment`
  MODIFY `AssignmentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employeeprofilesetup`
--
ALTER TABLE `employeeprofilesetup`
  MODIFY `EmployeeID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_competency`
--
ALTER TABLE `employee_competency`
  MODIFY `PROFILE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `employe_table`
--
ALTER TABLE `employe_table`
  MODIFY `EMPLOYEE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ess_attendance`
--
ALTER TABLE `ess_attendance`
  MODIFY `LEAVE_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ess_leave`
--
ALTER TABLE `ess_leave`
  MODIFY `LEAVE_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learning_content`
--
ALTER TABLE `learning_content`
  MODIFY `LEARNING_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `learning_content_essay_submission`
--
ALTER TABLE `learning_content_essay_submission`
  MODIFY `ESSAY_ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learning_materials`
--
ALTER TABLE `learning_materials`
  MODIFY `LM_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learning_progress`
--
ALTER TABLE `learning_progress`
  MODIFY `LP_ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login_tbl`
--
ALTER TABLE `login_tbl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `onboardingtrainingorientation`
--
ALTER TABLE `onboardingtrainingorientation`
  MODIFY `TrainingID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll_salary`
--
ALTER TABLE `payroll_salary`
  MODIFY `PAYROLL_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `performance_review`
--
ALTER TABLE `performance_review`
  MODIFY `REVIEW_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `skill-gap_analysis`
--
ALTER TABLE `skill-gap_analysis`
  MODIFY `GAP_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `skill_qualification`
--
ALTER TABLE `skill_qualification`
  MODIFY `SKILL_ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `succession_plan_dev`
--
ALTER TABLE `succession_plan_dev`
  MODIFY `SUCCESSION_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `succession_plan_monitor`
--
ALTER TABLE `succession_plan_monitor`
  MODIFY `EVAL_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `talent_identification`
--
ALTER TABLE `talent_identification`
  MODIFY `TALENT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `trainee_enrollment_approval`
--
ALTER TABLE `trainee_enrollment_approval`
  MODIFY `ENROLLMENT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `trainee_table`
--
ALTER TABLE `trainee_table`
  MODIFY `TRAINEE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `trainer_faculty`
--
ALTER TABLE `trainer_faculty`
  MODIFY `TRAINER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `training_calendar`
--
ALTER TABLE `training_calendar`
  MODIFY `CALENDAR_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training_program`
--
ALTER TABLE `training_program`
  MODIFY `PROGRAM_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `competancy_development`
--
ALTER TABLE `competancy_development`
  ADD CONSTRAINT `training joint` FOREIGN KEY (`ASSIGNED_TRAINING`) REFERENCES `training_program` (`PROGRAM_ID`);

--
-- Constraints for table `contractpolicyacknowledgement`
--
ALTER TABLE `contractpolicyacknowledgement`
  ADD CONSTRAINT `contractpolicyacknowledgement_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employeeprofilesetup` (`EmployeeID`) ON DELETE CASCADE;

--
-- Constraints for table `course_management`
--
ALTER TABLE `course_management`
  ADD CONSTRAINT `FK_CALENDAR2` FOREIGN KEY (`CALENDAR`) REFERENCES `training_calendar` (`CALENDAR_ID`),
  ADD CONSTRAINT `FK_TRAINER3` FOREIGN KEY (`TRAINER`) REFERENCES `trainer_faculty` (`TRAINER_ID`);

--
-- Constraints for table `departmentroleassignment`
--
ALTER TABLE `departmentroleassignment`
  ADD CONSTRAINT `departmentroleassignment_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employeeprofilesetup` (`EmployeeID`) ON DELETE CASCADE;

--
-- Constraints for table `learning_content`
--
ALTER TABLE `learning_content`
  ADD CONSTRAINT `FK_CALENDAR1` FOREIGN KEY (`CALENDAR`) REFERENCES `training_program` (`PROGRAM_ID`),
  ADD CONSTRAINT `FK_TRAINER2` FOREIGN KEY (`TRAINER`) REFERENCES `trainer_faculty` (`TRAINER_ID`),
  ADD CONSTRAINT `learning_content_ibfk_1` FOREIGN KEY (`COURSE`) REFERENCES `training_program` (`PROGRAM_ID`);

--
-- Constraints for table `learning_content_essay_submission`
--
ALTER TABLE `learning_content_essay_submission`
  ADD CONSTRAINT `learning_content_essay_submission_ibfk_1` FOREIGN KEY (`PROGRAM_ID`) REFERENCES `training_program` (`PROGRAM_ID`),
  ADD CONSTRAINT `learning_content_essay_submission_ibfk_2` FOREIGN KEY (`TRAINER`) REFERENCES `trainer_faculty` (`TRAINER_ID`);

--
-- Constraints for table `learning_materials`
--
ALTER TABLE `learning_materials`
  ADD CONSTRAINT `learning_materials_ibfk_1` FOREIGN KEY (`TRAINER`) REFERENCES `trainer_faculty` (`TRAINER_ID`);

--
-- Constraints for table `learning_progress`
--
ALTER TABLE `learning_progress`
  ADD CONSTRAINT `learning_progress_ibfk_1` FOREIGN KEY (`COURSE`) REFERENCES `training_program` (`PROGRAM_ID`),
  ADD CONSTRAINT `learning_progress_ibfk_2` FOREIGN KEY (`EMPLOYEE_ID`) REFERENCES `trainee_table` (`TRAINEE_ID`);

--
-- Constraints for table `onboardingtrainingorientation`
--
ALTER TABLE `onboardingtrainingorientation`
  ADD CONSTRAINT `onboardingtrainingorientation_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employeeprofilesetup` (`EmployeeID`) ON DELETE CASCADE;

--
-- Constraints for table `trainee_enrollment_approval`
--
ALTER TABLE `trainee_enrollment_approval`
  ADD CONSTRAINT `FK_COURSE_PROGRAM1` FOREIGN KEY (`COURSE_PROGRAM`) REFERENCES `training_program` (`PROGRAM_ID`),
  ADD CONSTRAINT `FK_EMPLOYEE` FOREIGN KEY (`EMPLOYEE_ID`) REFERENCES `employe_table` (`EMPLOYEE_ID`),
  ADD CONSTRAINT `FK_TRAINER_1` FOREIGN KEY (`TRAINER`) REFERENCES `trainer_faculty` (`TRAINER_ID`);

--
-- Constraints for table `trainer_faculty`
--
ALTER TABLE `trainer_faculty`
  ADD CONSTRAINT `trainer_faculty_ibfk_1` FOREIGN KEY (`course`) REFERENCES `training_program` (`PROGRAM_ID`) ON DELETE SET NULL;

--
-- Constraints for table `training_calendar`
--
ALTER TABLE `training_calendar`
  ADD CONSTRAINT `FK_PROGRAM1` FOREIGN KEY (`PROGRAM`) REFERENCES `training_program` (`PROGRAM_ID`);

--
-- Constraints for table `training_program`
--
ALTER TABLE `training_program`
  ADD CONSTRAINT `training_program_ibfk_1` FOREIGN KEY (`TRAINER`) REFERENCES `trainer_faculty` (`TRAINER_ID`);

--
-- Constraints for table `training_records`
--
ALTER TABLE `training_records`
  ADD CONSTRAINT `FK_PROGRAM_NAME` FOREIGN KEY (`PROGRAM_NAME`) REFERENCES `training_program` (`PROGRAM_ID`),
  ADD CONSTRAINT `FK_TRAINER` FOREIGN KEY (`TRAINER`) REFERENCES `trainer_faculty` (`TRAINER_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
