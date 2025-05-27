<?php
include '../../../phpcon/conn.php';

$enrollmentId = $_POST['ENROLLMENT_ID'];
$status = $_POST['STATUS'];

$query = "UPDATE trainee_enrollment_approval SET STATUS = ? WHERE ENROLLMENT_ID = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param('si', $status, $enrollmentId);

if ($stmt->execute()) {
  echo "Enrollment ID $enrollmentId successfully updated to $status.";
} else {
  echo "Error updating status.";
}
?>
