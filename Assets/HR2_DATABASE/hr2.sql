-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: hr2
-- ------------------------------------------------------
-- Server version	8.0.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `competancy_development`
--

DROP TABLE IF EXISTS `competancy_development`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `competancy_development` (
  `PLAN_ID` int NOT NULL,
  `EMPLOYEE_ID` varchar(45) DEFAULT NULL,
  `GOAL_DESCRIPTION` varchar(45) DEFAULT NULL,
  `ASSIGNED_TRAINING` varchar(45) DEFAULT NULL,
  `MILESTONE_DATE` date DEFAULT NULL,
  `STATUS` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`PLAN_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `competancy_development`
--

LOCK TABLES `competancy_development` WRITE;
/*!40000 ALTER TABLE `competancy_development` DISABLE KEYS */;
/*!40000 ALTER TABLE `competancy_development` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `competency_assessment`
--

DROP TABLE IF EXISTS `competency_assessment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `competency_assessment` (
  `ASSESSMENT_ID` int NOT NULL AUTO_INCREMENT,
  `EMPLOYEE` varchar(45) DEFAULT NULL,
  `ASSESSMENT_TYPE` varchar(45) DEFAULT NULL,
  `SCORE` int DEFAULT NULL,
  `DATE` date DEFAULT NULL,
  `COMPETENCY` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`ASSESSMENT_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `competency_assessment`
--

LOCK TABLES `competency_assessment` WRITE;
/*!40000 ALTER TABLE `competency_assessment` DISABLE KEYS */;
/*!40000 ALTER TABLE `competency_assessment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `competency_framework`
--

DROP TABLE IF EXISTS `competency_framework`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `competency_framework` (
  `COMPETENCY_ID` int NOT NULL AUTO_INCREMENT,
  `NAME` int NOT NULL,
  `ROLE` varbinary(45) DEFAULT NULL,
  `DEPARTMENT` varbinary(45) DEFAULT NULL,
  `LASTUPDATE` date DEFAULT NULL,
  `DESCRIPTION` blob,
  PRIMARY KEY (`COMPETENCY_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=binary;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `competency_framework`
--

LOCK TABLES `competency_framework` WRITE;
/*!40000 ALTER TABLE `competency_framework` DISABLE KEYS */;
/*!40000 ALTER TABLE `competency_framework` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course_management`
--

DROP TABLE IF EXISTS `course_management`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course_management` (
  `COURSE_ID` int NOT NULL AUTO_INCREMENT,
  `TRAINER` int NOT NULL,
  `CALENDAR` int NOT NULL,
  `STATUS_ID` varchar(255) DEFAULT NULL,
  `COURSE_TITLE` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`COURSE_ID`),
  KEY `FK_TRAINER3_idx` (`TRAINER`),
  KEY `FK_CALENDAR2_idx` (`CALENDAR`),
  CONSTRAINT `FK_CALENDAR2` FOREIGN KEY (`CALENDAR`) REFERENCES `training_calendar` (`CALENDAR_ID`),
  CONSTRAINT `FK_TRAINER3` FOREIGN KEY (`TRAINER`) REFERENCES `trainer_faculty` (`TRAINER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_management`
--

LOCK TABLES `course_management` WRITE;
/*!40000 ALTER TABLE `course_management` DISABLE KEYS */;
/*!40000 ALTER TABLE `course_management` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `critical_position`
--

DROP TABLE IF EXISTS `critical_position`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `critical_position` (
  `CRITICAL_ID` int NOT NULL AUTO_INCREMENT,
  `POSITION_TITLE` varchar(45) DEFAULT NULL,
  `DEPARTMENT` varchar(45) DEFAULT NULL,
  `INCUMBERT` varchar(45) DEFAULT NULL,
  `SUCCESSORS` varchar(45) DEFAULT NULL,
  `RISKLEVEL` varchar(45) DEFAULT NULL,
  `STATUS` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`CRITICAL_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `critical_position`
--

LOCK TABLES `critical_position` WRITE;
/*!40000 ALTER TABLE `critical_position` DISABLE KEYS */;
/*!40000 ALTER TABLE `critical_position` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_competency`
--

DROP TABLE IF EXISTS `employee_competency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee_competency` (
  `PROFILE_ID` int NOT NULL AUTO_INCREMENT,
  `EMPLOYEE_ID` varchar(45) DEFAULT NULL,
  `COMPETECY` varchar(45) DEFAULT NULL,
  `PROFICIENCY` varchar(45) DEFAULT NULL,
  `CERTIFICATION` varchar(45) DEFAULT NULL,
  `LASTUPDATED` date DEFAULT NULL,
  PRIMARY KEY (`PROFILE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_competency`
--

LOCK TABLES `employee_competency` WRITE;
/*!40000 ALTER TABLE `employee_competency` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_competency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ess_attendance`
--

DROP TABLE IF EXISTS `ess_attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ess_attendance` (
  `LEAVE_ID` int NOT NULL AUTO_INCREMENT,
  `DATE` date DEFAULT NULL,
  `EMPLOYEE_ID` varchar(45) DEFAULT NULL,
  `STATUS` varchar(45) DEFAULT NULL,
  `TIME_IN` time DEFAULT NULL,
  `TIME_OUT` time DEFAULT NULL,
  PRIMARY KEY (`LEAVE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ess_attendance`
--

LOCK TABLES `ess_attendance` WRITE;
/*!40000 ALTER TABLE `ess_attendance` DISABLE KEYS */;
/*!40000 ALTER TABLE `ess_attendance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ess_leave`
--

DROP TABLE IF EXISTS `ess_leave`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ess_leave` (
  `LEAVE_ID` int NOT NULL AUTO_INCREMENT,
  `EMPLOYEE_ID` varchar(45) DEFAULT NULL,
  `TYPE` varchar(45) DEFAULT NULL,
  `START_DATE` date DEFAULT NULL,
  `END_DATE` date DEFAULT NULL,
  `STATUS` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`LEAVE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ess_leave`
--

LOCK TABLES `ess_leave` WRITE;
/*!40000 ALTER TABLE `ess_leave` DISABLE KEYS */;
/*!40000 ALTER TABLE `ess_leave` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `learning_content`
--

DROP TABLE IF EXISTS `learning_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `learning_content` (
  `LEARNING_ID` int NOT NULL,
  `TRAINER` int NOT NULL,
  `CALENDAR` int NOT NULL,
  `STATUS` int DEFAULT NULL,
  `COURSE` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`LEARNING_ID`),
  KEY `FK_TRAINER2_idx` (`TRAINER`),
  KEY `FK_CALENDAR1_idx` (`CALENDAR`),
  CONSTRAINT `FK_CALENDAR1` FOREIGN KEY (`CALENDAR`) REFERENCES `training_calendar` (`CALENDAR_ID`),
  CONSTRAINT `FK_TRAINER2` FOREIGN KEY (`TRAINER`) REFERENCES `trainer_faculty` (`TRAINER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `learning_content`
--

LOCK TABLES `learning_content` WRITE;
/*!40000 ALTER TABLE `learning_content` DISABLE KEYS */;
/*!40000 ALTER TABLE `learning_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_salary`
--

DROP TABLE IF EXISTS `payroll_salary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payroll_salary` (
  `PAYROLL_ID` int NOT NULL AUTO_INCREMENT,
  `EMPLOYEE_ID` varchar(45) DEFAULT NULL,
  `LASTPAY_DATE` varchar(45) DEFAULT NULL,
  `TOTAL_SALARY` int DEFAULT NULL,
  PRIMARY KEY (`PAYROLL_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_salary`
--

LOCK TABLES `payroll_salary` WRITE;
/*!40000 ALTER TABLE `payroll_salary` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_salary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `performance_review`
--

DROP TABLE IF EXISTS `performance_review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `performance_review` (
  `REVIEW_ID` int NOT NULL AUTO_INCREMENT,
  `EMPLOYEE_ID` varchar(45) DEFAULT NULL,
  `REVIEW_DATE` date DEFAULT NULL,
  `RATING` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`REVIEW_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `performance_review`
--

LOCK TABLES `performance_review` WRITE;
/*!40000 ALTER TABLE `performance_review` DISABLE KEYS */;
/*!40000 ALTER TABLE `performance_review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `skill-gap_analysis`
--

DROP TABLE IF EXISTS `skill-gap_analysis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `skill-gap_analysis` (
  `GAP_ID` int NOT NULL AUTO_INCREMENT,
  `EMPLOYEE_ID` varchar(45) DEFAULT NULL,
  `COMPETENCY` varchar(45) DEFAULT NULL,
  `GAP_LEVEL` varchar(45) DEFAULT NULL,
  `RECOMMENDED_TRAINING` varchar(45) DEFAULT NULL,
  `TRAINING_DEADLINE` date DEFAULT NULL,
  PRIMARY KEY (`GAP_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `skill-gap_analysis`
--

LOCK TABLES `skill-gap_analysis` WRITE;
/*!40000 ALTER TABLE `skill-gap_analysis` DISABLE KEYS */;
/*!40000 ALTER TABLE `skill-gap_analysis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `succession_plan_dev`
--

DROP TABLE IF EXISTS `succession_plan_dev`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `succession_plan_dev` (
  `SUCCESSION_ID` int NOT NULL AUTO_INCREMENT,
  `SUCCESION_NAME` varchar(45) DEFAULT NULL,
  `CURRENT_ROLE` varchar(45) DEFAULT NULL,
  `READINESS_LEVEL` varchar(45) DEFAULT NULL,
  `DEVELOPMENT_ACTIONS` varchar(45) DEFAULT NULL,
  `TARGET_READINESS_DATE` date DEFAULT NULL,
  `MENTOR_ASSIGNED` varchar(45) DEFAULT NULL,
  `PROGRESS` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`SUCCESSION_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `succession_plan_dev`
--

LOCK TABLES `succession_plan_dev` WRITE;
/*!40000 ALTER TABLE `succession_plan_dev` DISABLE KEYS */;
/*!40000 ALTER TABLE `succession_plan_dev` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `succession_plan_monitor`
--

DROP TABLE IF EXISTS `succession_plan_monitor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `succession_plan_monitor` (
  `EVAL_ID` int NOT NULL AUTO_INCREMENT,
  `PLAN_ID` varchar(45) DEFAULT NULL,
  `EVALUATION_DATE` date DEFAULT NULL,
  `ADJUSTMENT` varchar(45) DEFAULT NULL,
  `EFFECTIVENESS_RATE` varchar(45) DEFAULT NULL,
  `NEXT_REVIEW` date DEFAULT NULL,
  PRIMARY KEY (`EVAL_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `succession_plan_monitor`
--

LOCK TABLES `succession_plan_monitor` WRITE;
/*!40000 ALTER TABLE `succession_plan_monitor` DISABLE KEYS */;
/*!40000 ALTER TABLE `succession_plan_monitor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `successor_identification`
--

DROP TABLE IF EXISTS `successor_identification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `successor_identification` (
  `SUCCESSOR_ID` int NOT NULL,
  `CANDIDATE_NAME` varchar(45) DEFAULT NULL,
  `CURRENT_POSITION` varchar(45) DEFAULT NULL,
  `PERFOMANCE` varchar(45) DEFAULT NULL,
  `POTENTIAL` varchar(45) DEFAULT NULL,
  `READINESS` varchar(45) DEFAULT NULL,
  `MANAGER_RECOM` varchar(45) DEFAULT NULL,
  `STATUS` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`SUCCESSOR_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `successor_identification`
--

LOCK TABLES `successor_identification` WRITE;
/*!40000 ALTER TABLE `successor_identification` DISABLE KEYS */;
/*!40000 ALTER TABLE `successor_identification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `talent_identification`
--

DROP TABLE IF EXISTS `talent_identification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `talent_identification` (
  `TALENT_ID` int NOT NULL AUTO_INCREMENT,
  `EMPLOYEE` varchar(45) DEFAULT NULL,
  `DEPARTMENT` varchar(45) DEFAULT NULL,
  `CURRENT_ROLE` varchar(45) DEFAULT NULL,
  `SUCCESSOR` varchar(45) DEFAULT NULL,
  `READINESS` varchar(45) DEFAULT NULL,
  `POTENTIAL` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`TALENT_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `talent_identification`
--

LOCK TABLES `talent_identification` WRITE;
/*!40000 ALTER TABLE `talent_identification` DISABLE KEYS */;
/*!40000 ALTER TABLE `talent_identification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trainer_faculty`
--

DROP TABLE IF EXISTS `trainer_faculty`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trainer_faculty` (
  `TRAINER_ID` int NOT NULL AUTO_INCREMENT,
  `FULLNAME` varchar(45) DEFAULT NULL,
  `SUBJECT` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`TRAINER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trainer_faculty`
--

LOCK TABLES `trainer_faculty` WRITE;
/*!40000 ALTER TABLE `trainer_faculty` DISABLE KEYS */;
/*!40000 ALTER TABLE `trainer_faculty` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_calendar`
--

DROP TABLE IF EXISTS `training_calendar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `training_calendar` (
  `CALENDAR_ID` int NOT NULL,
  `START` date DEFAULT NULL,
  `END` date DEFAULT NULL,
  `TRAINER` int NOT NULL,
  PRIMARY KEY (`CALENDAR_ID`),
  KEY `FK_TRAINER_idx` (`TRAINER`),
  CONSTRAINT `FK_TRAINER1` FOREIGN KEY (`TRAINER`) REFERENCES `trainer_faculty` (`TRAINER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_calendar`
--

LOCK TABLES `training_calendar` WRITE;
/*!40000 ALTER TABLE `training_calendar` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_calendar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_program`
--

DROP TABLE IF EXISTS `training_program`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `training_program` (
  `PROGRAM_ID` int NOT NULL AUTO_INCREMENT,
  `PROGRAM_TYPE` varchar(50) DEFAULT NULL,
  `PROGRAM_NAME` varchar(50) DEFAULT NULL,
  `TRAINER` int NOT NULL,
  `STATUS` varchar(45) DEFAULT NULL,
  `CALENDAR` int NOT NULL,
  PRIMARY KEY (`PROGRAM_ID`),
  KEY `FK_CALENDAR_idx` (`CALENDAR`),
  CONSTRAINT `FK_CALENDAR` FOREIGN KEY (`CALENDAR`) REFERENCES `training_calendar` (`CALENDAR_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_program`
--

LOCK TABLES `training_program` WRITE;
/*!40000 ALTER TABLE `training_program` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_program` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_records`
--

DROP TABLE IF EXISTS `training_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `training_records` (
  `ID` int NOT NULL,
  `TRAINER` int NOT NULL,
  `PROGRAM_NAME` int NOT NULL,
  `PROGRESS` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_TRAINER_idx` (`TRAINER`),
  KEY `FK_PROGRAM_NAME_idx` (`PROGRAM_NAME`),
  CONSTRAINT `FK_PROGRAM_NAME` FOREIGN KEY (`PROGRAM_NAME`) REFERENCES `training_program` (`PROGRAM_ID`),
  CONSTRAINT `FK_TRAINER` FOREIGN KEY (`TRAINER`) REFERENCES `trainer_faculty` (`TRAINER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_records`
--

LOCK TABLES `training_records` WRITE;
/*!40000 ALTER TABLE `training_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_records` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-13 14:17:04
