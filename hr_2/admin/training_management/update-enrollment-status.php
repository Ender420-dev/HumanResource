<?php
include '../../../phpcon/conn.php';

$enrollmentId = $_POST['enrollment_id'];
$status = $_POST['status'];

$query = "UPDATE trainee_enrollment_approval SET STATUS = ? WHERE ENROLLMENT_ID = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param('si', $status, $enrollmentId);

if ($stmt->execute()) {
  echo "Enrollment ID $enrollmentId successfully updated to $status.";
} else {
  echo "Error updating status.";
}
?>