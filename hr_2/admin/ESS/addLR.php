<?php
// Connect to your database
include '../../../phpcon/conn.php';


// Check connection
if ($connection_hr3->connect_error) {
    die("Connection failed: " . $connection_hr3->connect_error);
}

// Sanitize and retrieve POST data
$employee_id = $_POST['employee_id'] ?? '';
$leave_type_id = $_POST['leave_type_id'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';
$total_days = $_POST['total_days'] ?? 0;
$reason = $_POST['reason'] ?? '';
$status = $_POST['status'] ?? 'Pending';

// Validate required fields
if (empty($employee_id) || empty($leave_type_id) || empty($start_date) || empty($end_date) || empty($reason)) {
    die("Please fill in all required fields.");
}

// Prepare SQL query
$stmt = $connection_hr3->prepare("INSERT INTO hr3.leaverequests (employee_id, leave_type_id, start_date, end_date, total_days, reason, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iissdss", $employee_id, $leave_type_id, $start_date, $end_date, $total_days, $reason, $status);

// Execute the query
if ($stmt->execute()) {
    echo "<script>alert('Leave request submitted successfully.'); window.location.href='ess.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$connection->close();
?>
