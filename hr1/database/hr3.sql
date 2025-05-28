-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: May 26, 2025 at 05:04 PM
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
-- Database: `hr3`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `record_time` datetime NOT NULL,
  `record_type` enum('Clock In','Clock Out','Break Start','Break End') NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `is_manual_entry` tinyint(1) DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `employee_id`, `record_time`, `record_type`, `location`, `is_manual_entry`, `notes`, `created_at`) VALUES
(1, 1, '2025-05-20 10:42:13', 'Clock In', 'dsf', 0, 'asds', '2025-05-20 08:43:22'),
(2, 2, '2025-05-20 10:42:13', 'Clock In', 'sdgd', 0, 'sdfsdf', '2025-05-20 08:43:22'),
(8, 1, '2025-05-22 07:25:52', 'Clock In', 'cdasc', 0, NULL, '2025-05-22 05:26:11');

-- --------------------------------------------------------

--
-- Table structure for table `dailyattendancesummary`
--

CREATE TABLE `dailyattendancesummary` (
  `summary_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `attendance_date` date NOT NULL,
  `first_clock_in` time DEFAULT NULL,
  `last_clock_out` time DEFAULT NULL,
  `total_hours` decimal(5,2) DEFAULT NULL,
  `regular_hours` decimal(5,2) DEFAULT NULL,
  `overtime_hours` decimal(5,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT 0,
  `approval_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dailyattendancesummary`
--

INSERT INTO `dailyattendancesummary` (`summary_id`, `employee_id`, `attendance_date`, `first_clock_in`, `last_clock_out`, `total_hours`, `regular_hours`, `overtime_hours`, `status`, `is_approved`, `approval_notes`) VALUES
(1, 1, '2025-05-02', '21:01:05', '21:01:05', 8.00, 6.00, 2.00, 'pending', 0, NULL),
(4, 2, '2025-05-02', '13:57:16', '13:57:16', 8.00, 6.00, 2.00, 'Active', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `department_type` enum('Clinical','Administrative','Support','Research') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `description`, `department_type`) VALUES
(1, 'Emergency Room', 'dsdsfd', 'Clinical'),
(2, 'Lab', 'fsdfd', 'Research');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `employee_code` varchar(20) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `job_title` varchar(100) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `first_name`, `last_name`, `employee_code`, `department_id`, `job_title`, `hire_date`, `status`) VALUES
(1, 'andrei', 'san roque', 'dsaa', 1, 'it', '2025-05-20', 'Active'),
(2, 'andre', 'san roqu', 'das', 2, 'IT', '2025-05-19', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `employee_schedules`
--

CREATE TABLE `employee_schedules` (
  `schedule_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `shift_id` int(11) NOT NULL,
  `schedule_date` date NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_schedules`
--

INSERT INTO `employee_schedules` (`schedule_id`, `employee_id`, `shift_id`, `schedule_date`, `department_id`, `is_published`, `created_at`, `updated_at`) VALUES
(8, 1, 2, '2025-05-26', 1, 0, '2025-05-26 04:11:20', '2025-05-26 04:11:20'),
(10, 2, 2, '2025-05-26', 2, 0, '2025-05-26 04:13:12', '2025-05-26 04:13:12'),
(11, 2, 2, '2025-05-27', 2, 0, '2025-05-26 04:16:32', '2025-05-26 04:16:32'),
(12, 1, 3, '2025-05-30', 1, 0, '2025-05-26 04:18:23', '2025-05-26 04:18:23'),
(13, 1, 4, '2025-06-01', NULL, 0, '2025-05-26 04:19:12', '2025-05-26 04:19:12'),
(14, 1, 1, '2025-05-31', 2, 0, '2025-05-26 04:19:30', '2025-05-26 04:19:30'),
(15, 2, 4, '2025-05-28', NULL, 0, '2025-05-26 04:19:53', '2025-05-26 04:19:53');

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `holiday_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `type` enum('Public','Private','Company') NOT NULL,
  `applies_to` varchar(255) DEFAULT 'All Employees',
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `holidays`
--

INSERT INTO `holidays` (`holiday_id`, `name`, `date`, `type`, `applies_to`, `description`, `created_at`) VALUES
(1, 'dasdsa', '2025-12-31', 'Private', 'Specific Departments', 'sdsadad', '2025-05-22 03:33:24'),
(2, 'fewfwfe', '2025-05-22', 'Public', 'All Employees', 'eewfwef', '2025-05-22 05:17:35');

-- --------------------------------------------------------

--
-- Table structure for table `leavebalances`
--

CREATE TABLE `leavebalances` (
  `balance_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `leave_type_id` int(11) DEFAULT NULL,
  `fiscal_year` int(11) DEFAULT NULL,
  `accrued_days` decimal(5,2) DEFAULT 0.00,
  `used_days` decimal(5,2) DEFAULT 0.00,
  `carried_over_days` decimal(5,2) DEFAULT 0.00,
  `remaining_days` decimal(5,2) GENERATED ALWAYS AS (`accrued_days` - `used_days` + `carried_over_days`) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leaverequests`
--

CREATE TABLE `leaverequests` (
  `request_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `leave_type_id` int(11) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_days` decimal(5,2) NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Cancelled','Revisions Requested') DEFAULT 'Pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approver_comments` text DEFAULT NULL,
  `document_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leaverequests`
--

INSERT INTO `leaverequests` (`request_id`, `employee_id`, `leave_type_id`, `start_date`, `end_date`, `total_days`, `reason`, `status`, `requested_at`, `approved_by`, `approved_at`, `approver_comments`, `document_path`) VALUES
(1, 1, 1, '2025-05-05', '2025-05-22', 17.00, 'sdasd', 'Pending', '2025-05-21 17:38:51', NULL, '2025-05-21 17:38:51', NULL, NULL),
(2, 2, 1, '2025-05-22', '2025-05-24', 2.00, 'fdsfsdfdf', 'Pending', '2025-05-21 17:44:06', NULL, '2025-05-21 17:44:06', NULL, NULL),
(3, 1, 3, '2025-05-22', '2025-05-28', 6.00, 'natatae', 'Pending', '2025-05-21 17:54:56', NULL, '2025-05-21 17:54:56', NULL, NULL),
(5, 4, 3, '2025-05-22', '2025-05-24', 3.00, 'safsdf', 'Pending', '2025-05-21 18:11:12', NULL, '2025-05-21 18:11:12', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `leavetypes`
--

CREATE TABLE `leavetypes` (
  `leave_type_id` int(11) NOT NULL,
  `leave_type_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `accrual_rate` decimal(5,2) DEFAULT NULL,
  `max_carry_over` decimal(5,2) DEFAULT NULL,
  `requires_document` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leavetypes`
--

INSERT INTO `leavetypes` (`leave_type_id`, `leave_type_name`, `description`, `accrual_rate`, `max_carry_over`, `requires_document`) VALUES
(1, 'sick', 'fsfsdfsfsd', NULL, NULL, 0),
(2, 'marital', NULL, NULL, NULL, 0),
(3, 'secret', NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `Account_Type` enum('user','admin','manager','') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `full_name`, `email`, `password`, `Account_Type`, `created_at`) VALUES
(1, 'admin', 'admin@admin.com', 'admin', 'admin', '2025-04-04 01:33:28'),
(2, 'user', 'user@user.com', 'user', 'user', '2025-04-04 01:33:28'),
(5, 'andrei', 'user2@user.com', 'user', 'user', '2025-04-23 04:59:05');

-- --------------------------------------------------------

--
-- Table structure for table `manualadjustments`
--

CREATE TABLE `manualadjustments` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `request_date` date NOT NULL,
  `adjustment_type` enum('Clock In','Clock Out','Break Time') NOT NULL,
  `old_time` time NOT NULL,
  `new_time` time NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manualadjustments`
--

INSERT INTO `manualadjustments` (`id`, `employee_id`, `request_date`, `adjustment_type`, `old_time`, `new_time`, `status`, `created_at`) VALUES
(1, 1, '2025-05-21', 'Clock In', '15:12:15', '15:30:15', 'Pending', '2025-05-21 07:13:58');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('Active','On Hold','Completed','Cancelled') DEFAULT 'Active',
  `priority` enum('Low','Medium','High','Critical') DEFAULT 'Medium',
  `department_id` int(11) DEFAULT NULL,
  `project_manager_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`project_id`, `project_name`, `description`, `start_date`, `end_date`, `status`, `priority`, `department_id`, `project_manager_id`, `created_at`, `updated_at`) VALUES
(1, 'dadsa', 'dsfdfd', '2025-05-21', '2025-06-04', 'On Hold', 'Medium', 2, NULL, '2025-05-21 12:18:16', '2025-05-21 12:27:19'),
(2, 'dasdsa', 'sfsdfds', '2025-05-22', '2025-05-29', 'Completed', 'Medium', NULL, NULL, '2025-05-21 16:40:45', '2025-05-21 16:40:45');

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `shift_id` int(11) NOT NULL,
  `shift_name` varchar(50) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`shift_id`, `shift_name`, `start_time`, `end_time`, `description`, `created_at`) VALUES
(1, 'Morning', '06:00:00', '14:00:00', 'Standard morning shift', '2025-05-25 16:59:19'),
(2, 'Day', '09:00:00', '17:00:00', 'Standard day shift', '2025-05-25 16:59:19'),
(3, 'Night', '22:00:00', '06:00:00', 'Standard night shift', '2025-05-25 16:59:19'),
(4, 'On Call', '00:00:00', '23:59:59', 'On-call duty for 24 hours', '2025-05-25 16:59:19');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `assigned_to_employee_id` int(11) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('To Do','In Progress','Awaiting Review','Completed','Blocked') DEFAULT 'To Do',
  `priority` enum('Low','Medium','High') DEFAULT 'Medium',
  `estimated_hours` decimal(5,2) DEFAULT NULL,
  `is_billable` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `task_name`, `description`, `project_id`, `assigned_to_employee_id`, `due_date`, `status`, `priority`, `estimated_hours`, `is_billable`, `created_at`, `updated_at`) VALUES
(2, 'sasds', 'adasd', 1, 1, '2025-05-21', 'To Do', 'Medium', NULL, 1, '2025-05-21 12:27:43', '2025-05-21 12:27:43'),
(3, 'asad', 'sdfwe', 1, 1, '2025-05-28', 'In Progress', 'Medium', NULL, 0, '2025-05-21 12:28:08', '2025-05-21 12:28:08');

-- --------------------------------------------------------

--
-- Table structure for table `timesheetapprovalhistory`
--

CREATE TABLE `timesheetapprovalhistory` (
  `history_id` int(11) NOT NULL,
  `timesheet_id` int(11) NOT NULL,
  `approver_id` int(11) NOT NULL,
  `action` enum('Submitted','Approved','Rejected','Revisions Requested','Recalled','Forwarded') NOT NULL,
  `comments` text DEFAULT NULL,
  `action_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timesheetapprovalhistory`
--

INSERT INTO `timesheetapprovalhistory` (`history_id`, `timesheet_id`, `approver_id`, `action`, `comments`, `action_date`) VALUES
(1, 1, 1, '', 'Timesheet approved.', '2025-05-21 20:04:31');

-- --------------------------------------------------------

--
-- Table structure for table `timesheets`
--

CREATE TABLE `timesheets` (
  `timesheet_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_hours_submitted` decimal(6,2) DEFAULT NULL,
  `status` enum('Draft','Submitted','Pending Approval','Approved','Rejected','Revisions Requested') NOT NULL DEFAULT 'Draft',
  `submission_date` datetime DEFAULT NULL,
  `submitted_by` int(11) DEFAULT NULL,
  `current_approver_id` int(11) DEFAULT NULL,
  `approved_by_id` int(11) DEFAULT NULL,
  `approved_date` datetime DEFAULT NULL,
  `employee_notes` text DEFAULT NULL,
  `manager_comments` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timesheets`
--

INSERT INTO `timesheets` (`timesheet_id`, `employee_id`, `start_date`, `end_date`, `total_hours_submitted`, `status`, `submission_date`, `submitted_by`, `current_approver_id`, `approved_by_id`, `approved_date`, `employee_notes`, `manager_comments`, `created_at`, `updated_at`) VALUES
(1, 2, '2025-05-21', '2025-05-21', 12.00, 'Approved', '2025-05-21 19:28:52', NULL, NULL, 1, '2025-05-21 20:04:31', NULL, 'Timesheet approved.', '2025-05-21 11:28:52', '2025-05-21 12:04:31'),
(2, 1, '2025-05-21', '2025-05-22', 24.00, 'Rejected', '2025-05-21 20:01:18', NULL, NULL, NULL, NULL, NULL, '', '2025-05-21 12:01:18', '2025-05-21 12:01:26'),
(5, 1, '2025-05-22', '2025-05-22', 168.00, 'Approved', '2025-05-22 13:39:11', NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-22 05:39:11', '2025-05-22 05:39:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `dailyattendancesummary`
--
ALTER TABLE `dailyattendancesummary`
  ADD PRIMARY KEY (`summary_id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`,`attendance_date`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD UNIQUE KEY `department_name` (`department_name`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `employee_code` (`employee_code`);

--
-- Indexes for table `employee_schedules`
--
ALTER TABLE `employee_schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`,`schedule_date`),
  ADD KEY `fk_shift` (`shift_id`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`holiday_id`);

--
-- Indexes for table `leavebalances`
--
ALTER TABLE `leavebalances`
  ADD PRIMARY KEY (`balance_id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`,`leave_type_id`,`fiscal_year`),
  ADD KEY `leave_type_id` (`leave_type_id`);

--
-- Indexes for table `leaverequests`
--
ALTER TABLE `leaverequests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `leave_type_id` (`leave_type_id`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Indexes for table `leavetypes`
--
ALTER TABLE `leavetypes`
  ADD PRIMARY KEY (`leave_type_id`),
  ADD UNIQUE KEY `leave_type_name` (`leave_type_name`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manualadjustments`
--
ALTER TABLE `manualadjustments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `project_manager_id` (`project_manager_id`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`shift_id`),
  ADD UNIQUE KEY `shift_name` (`shift_name`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `assigned_to_employee_id` (`assigned_to_employee_id`);

--
-- Indexes for table `timesheetapprovalhistory`
--
ALTER TABLE `timesheetapprovalhistory`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `timesheet_id` (`timesheet_id`),
  ADD KEY `approver_id` (`approver_id`);

--
-- Indexes for table `timesheets`
--
ALTER TABLE `timesheets`
  ADD PRIMARY KEY (`timesheet_id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`,`start_date`,`end_date`),
  ADD KEY `submitted_by` (`submitted_by`),
  ADD KEY `current_approver_id` (`current_approver_id`),
  ADD KEY `approved_by_id` (`approved_by_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `dailyattendancesummary`
--
ALTER TABLE `dailyattendancesummary`
  MODIFY `summary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `employee_schedules`
--
ALTER TABLE `employee_schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `holiday_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `leavebalances`
--
ALTER TABLE `leavebalances`
  MODIFY `balance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leaverequests`
--
ALTER TABLE `leaverequests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `leavetypes`
--
ALTER TABLE `leavetypes`
  MODIFY `leave_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `manualadjustments`
--
ALTER TABLE `manualadjustments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `shift_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `timesheetapprovalhistory`
--
ALTER TABLE `timesheetapprovalhistory`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `timesheets`
--
ALTER TABLE `timesheets`
  MODIFY `timesheet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `dailyattendancesummary`
--
ALTER TABLE `dailyattendancesummary`
  ADD CONSTRAINT `dailyattendancesummary_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `employee_schedules`
--
ALTER TABLE `employee_schedules`
  ADD CONSTRAINT `fk_employee` FOREIGN KEY (`employee_id`) REFERENCES `hr1`.`employee_profile_setup` (`employee_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_shift` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`shift_id`);

--
-- Constraints for table `leavebalances`
--
ALTER TABLE `leavebalances`
  ADD CONSTRAINT `leavebalances_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  ADD CONSTRAINT `leavebalances_ibfk_2` FOREIGN KEY (`leave_type_id`) REFERENCES `leavetypes` (`leave_type_id`);

--
-- Constraints for table `leaverequests`
--
ALTER TABLE `leaverequests`
  ADD CONSTRAINT `leaverequests_ibfk_2` FOREIGN KEY (`leave_type_id`) REFERENCES `leavetypes` (`leave_type_id`),
  ADD CONSTRAINT `leaverequests_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
  ADD CONSTRAINT `projects_ibfk_2` FOREIGN KEY (`project_manager_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`),
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`assigned_to_employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `timesheetapprovalhistory`
--
ALTER TABLE `timesheetapprovalhistory`
  ADD CONSTRAINT `timesheetapprovalhistory_ibfk_1` FOREIGN KEY (`timesheet_id`) REFERENCES `timesheets` (`timesheet_id`),
  ADD CONSTRAINT `timesheetapprovalhistory_ibfk_2` FOREIGN KEY (`approver_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `timesheets`
--
ALTER TABLE `timesheets`
  ADD CONSTRAINT `timesheets_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  ADD CONSTRAINT `timesheets_ibfk_2` FOREIGN KEY (`submitted_by`) REFERENCES `employees` (`employee_id`),
  ADD CONSTRAINT `timesheets_ibfk_3` FOREIGN KEY (`current_approver_id`) REFERENCES `employees` (`employee_id`),
  ADD CONSTRAINT `timesheets_ibfk_4` FOREIGN KEY (`approved_by_id`) REFERENCES `employees` (`employee_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
