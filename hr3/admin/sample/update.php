<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $leave_request_id = $_POST['leave_request_id'];
    $status = $_POST['status'];
    $remarks = $_POST['remarks'];
    $updated_by = $_POST['updated_by'];

    $sql = "INSERT INTO leave_status (leave_request_id, status, remarks, updated_by) VALUES (?, ?, ?, ?)";
    $stmt = $conn_hr2->prepare($sql);
    $stmt->bind_param("isss", $leave_request_id, $status, $remarks, $updated_by);
    $stmt->execute();

    $sql = "UPDATE leave_requests SET status = ? WHERE id = ?";
    $stmt = $conn_hr1->prepare($sql);
    $stmt->bind_param("si", $status, $leave_request_id);
    $stmt->execute();

    echo "Leave request status updated successfully.";
} else {
    if (isset($_GET['leave_request_id'])) {
        $leave_request_id = $_GET['leave_request_id'];
        $sql = "SELECT * FROM leave_requests WHERE id = ?";
        $stmt = $conn_hr1->prepare($sql);
        $stmt->bind_param("i", $leave_request_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $leave_request = $result->fetch_assoc();
    }
}

$conn_hr1->close();
$conn_hr2->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Leave Status</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Leave Request Details</h2>
        <?php if (isset($leave_request)) { ?>
            <p>Employee Name: <?php echo $leave_request['employee_name']; ?></p>
            <p>Leave Type: <?php echo $leave_request['leave_type']; ?></p>
            <p>Start Date: <?php echo $leave_request['start_date']; ?></p>
            <p>End Date: <?php echo $leave_request['end_date']; ?></p>
            <p>Reason: <?php echo $leave_request['reason']; ?></p>
            <p>Current Status: <?php echo $leave_request['status']; ?></p>
        <?php } ?>
        <h2>Update Leave Status</h2>
        <form action="update.php" method="post">
            <label>Leave Request ID:</label><br>
            <input type="number" name="leave_request_id" value="<?php echo isset($leave_request_id) ? $leave_request_id : ''; ?>" required><br>
            <label>Status:</label><br>
            <select name="status">
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
            </select><br>
            <label>Remarks:</label><br>
            <textarea name="remarks"></textarea><br>
            <label>Updated By:</label><br>
            <input type="text" name="updated_by" required><br>
            <input type="submit" value="Update">
        </form>
    </div>
</body>
</html>