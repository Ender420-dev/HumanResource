<?php
include '../Database/db.php';

if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Get input
$employee_id = $_POST['employee_id'];
$shift_date  = $_POST['shift_date'];
$time_in     = $_POST['time_in'];
$time_out    = $_POST['time_out'];

// Convert time to DateTime objects
$in  = new DateTime($time_in);
$out = new DateTime($time_out);

// Handle shifts that pass midnight
if ($out < $in) {
    $out->modify('+1 day');
}

// Calculate total hours
$interval     = $in->diff($out);
$total_hours  = $interval->h + ($interval->i / 60); // decimal hours
$overtime     = $total_hours > 8 ? $total_hours - 8 : 0;

// Optional: round to 2 decimals
$total_hours = round($total_hours, 2);
$overtime    = round($overtime, 2);

// Insert into DB
$stmt = $conn->prepare("
    INSERT INTO shift_logs (employee_id, shift_date, time_in, time_out, total_hours, overtime)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("isssdd", $employee_id, $shift_date, $time_in, $time_out, $total_hours, $overtime);

if ($stmt->execute()) {
    echo "Shift log added successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
