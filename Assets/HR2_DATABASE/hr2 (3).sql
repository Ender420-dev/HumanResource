-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: May 13, 2025 at 07:42 PM
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
  `ASSIGNED_TRAINING` varchar(45) DEFAULT NULL,
  `MILESTONE_DATE` date DEFAULT NULL,
  `STATUS` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `competency_framework`
--

CREATE TABLE `competency_framework` (
  `COMPETENCY_ID` int(11) NOT NULL,
  `NAME` int(11) NOT NULL,
  `ROLE` varbinary(45) DEFAULT NULL,
  `DEPARTMENT` varbinary(45) DEFAULT NULL,
  `LASTUPDATE` date DEFAULT NULL,
  `DESCRIPTION` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=binary;

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
  `CALENDAR` int(11) NOT NULL,
  `STATUS` int(11) DEFAULT NULL,
  `COURSE` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `trainer_faculty`
--

CREATE TABLE `trainer_faculty` (
  `TRAINER_ID` int(11) NOT NULL,
  `FULLNAME` varchar(45) DEFAULT NULL,
  `SUBJECT` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `training_calendar`
--

CREATE TABLE `training_calendar` (
  `CALENDAR_ID` int(11) NOT NULL,
  `START` date DEFAULT NULL,
  `END` date DEFAULT NULL,
  `TRAINER` int(11) NOT NULL
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
  `STATUS` varchar(45) DEFAULT NULL,
  `CALENDAR` int(11) NOT NULL,
  `DESCRIPTION_PROGRAM` text DEFAULT NULL,
  `TRAINEE_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  ADD PRIMARY KEY (`PLAN_ID`);

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
  ADD KEY `FK_CALENDAR1_idx` (`CALENDAR`);

--
-- Indexes for table `login_tbl`
--
ALTER TABLE `login_tbl`
  ADD PRIMARY KEY (`ID`);

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
  ADD PRIMARY KEY (`TRAINER_ID`);

--
-- Indexes for table `training_calendar`
--
ALTER TABLE `training_calendar`
  ADD PRIMARY KEY (`CALENDAR_ID`),
  ADD KEY `FK_TRAINER_idx` (`TRAINER`);

--
-- Indexes for table `training_program`
--
ALTER TABLE `training_program`
  ADD PRIMARY KEY (`PROGRAM_ID`),
  ADD KEY `FK_CALENDAR_idx` (`CALENDAR`),
  ADD KEY `FK_TRAINEE_ID_idx` (`TRAINEE_ID`);

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
-- AUTO_INCREMENT for table `competency_assessment`
--
ALTER TABLE `competency_assessment`
  MODIFY `ASSESSMENT_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `competency_framework`
--
ALTER TABLE `competency_framework`
  MODIFY `COMPETENCY_ID` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `employee_competency`
--
ALTER TABLE `employee_competency`
  MODIFY `PROFILE_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employe_table`
--
ALTER TABLE `employe_table`
  MODIFY `EMPLOYEE_ID` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `login_tbl`
--
ALTER TABLE `login_tbl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
  MODIFY `TALENT_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trainee_enrollment_approval`
--
ALTER TABLE `trainee_enrollment_approval`
  MODIFY `ENROLLMENT_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trainee_table`
--
ALTER TABLE `trainee_table`
  MODIFY `TRAINEE_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trainer_faculty`
--
ALTER TABLE `trainer_faculty`
  MODIFY `TRAINER_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training_program`
--
ALTER TABLE `training_program`
  MODIFY `PROGRAM_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `course_management`
--
ALTER TABLE `course_management`
  ADD CONSTRAINT `FK_CALENDAR2` FOREIGN KEY (`CALENDAR`) REFERENCES `training_calendar` (`CALENDAR_ID`),
  ADD CONSTRAINT `FK_TRAINER3` FOREIGN KEY (`TRAINER`) REFERENCES `trainer_faculty` (`TRAINER_ID`);

--
-- Constraints for table `learning_content`
--
ALTER TABLE `learning_content`
  ADD CONSTRAINT `FK_CALENDAR1` FOREIGN KEY (`CALENDAR`) REFERENCES `training_calendar` (`CALENDAR_ID`),
  ADD CONSTRAINT `FK_TRAINER2` FOREIGN KEY (`TRAINER`) REFERENCES `trainer_faculty` (`TRAINER_ID`);

--
-- Constraints for table `trainee_enrollment_approval`
--
ALTER TABLE `trainee_enrollment_approval`
  ADD CONSTRAINT `FK_COURSE_PROGRAM1` FOREIGN KEY (`COURSE_PROGRAM`) REFERENCES `training_program` (`PROGRAM_ID`),
  ADD CONSTRAINT `FK_EMPLOYEE` FOREIGN KEY (`EMPLOYEE_ID`) REFERENCES `employe_table` (`EMPLOYEE_ID`),
  ADD CONSTRAINT `FK_TRAINER_1` FOREIGN KEY (`TRAINER`) REFERENCES `trainer_faculty` (`TRAINER_ID`);

--
-- Constraints for table `training_calendar`
--
ALTER TABLE `training_calendar`
  ADD CONSTRAINT `FK_TRAINER1` FOREIGN KEY (`TRAINER`) REFERENCES `trainer_faculty` (`TRAINER_ID`);

--
-- Constraints for table `training_program`
--
ALTER TABLE `training_program`
  ADD CONSTRAINT `FK_CALENDAR` FOREIGN KEY (`CALENDAR`) REFERENCES `training_calendar` (`CALENDAR_ID`),
  ADD CONSTRAINT `FK_TRAINEE_ID` FOREIGN KEY (`TRAINEE_ID`) REFERENCES `trainee_table` (`TRAINEE_ID`);

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
