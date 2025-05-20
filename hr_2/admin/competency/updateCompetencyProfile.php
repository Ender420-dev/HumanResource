<?php
include '../../../phpcon/conn.php';// Make sure your DB connection is included

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profileId = $_POST['PROFILE_ID'];
    $employeeId = $_POST['EMPLOYEE_ID'];
    $competency = $_POST['COMPETECY'];
    $proficiency = $_POST['PROFICIENCY'];
    $certification = $_POST['CERTIFICATION'];
    $lastUpdated = date('Y-m-d');

    $stmt = $connection->prepare("UPDATE employee_competency SET EMPLOYEE_ID=?, COMPETECY=?, PROFICIENCY=?, CERTIFICATION=?, LASTUPDATED=? WHERE PROFILE_ID=?");
    $stmt->bind_param("sssssi", $employeeId, $competency, $proficiency, $certification, $lastUpdated, $profileId);

    if ($stmt->execute()) {
        header("Location: competency.php?status=updated");
    } else {
        echo "Error updating profile.";
    }
}
?>
