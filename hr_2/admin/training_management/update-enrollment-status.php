<?php
include '../../../phpcon/conn.php';

$enrollmentId = $_POST['enrollment_id'];
$status = $_POST['status'];

$query = "UPDATE onboarding_training_orientation SET status = ? WHERE employee_id = ?";
$stmt = $connection_hr1->prepare($query);
$stmt->bind_param('si', $status, $enrollmentId);

if ($stmt->execute()) {
  echo "Enrollment ID $enrollmentId successfully updated to $status.";
} else {
  echo "Error updating status.";
}
?>
