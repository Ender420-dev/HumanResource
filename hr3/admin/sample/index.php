<?php
require_once 'db.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_name = $_POST['employee_name'];
    $leave_type = $_POST['leave_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];

    $sql = "INSERT INTO leave_requests (employee_name, leave_type, start_date, end_date, reason) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn_hr1->prepare($sql);
    $stmt->bind_param("sssss", $employee_name, $leave_type, $start_date, $end_date, $reason);
    $stmt->execute();
    echo "Leave request submitted successfully.";
    $conn_hr1->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Leave Request</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Submit Leave Request</h2>
        <form action="index.php" method="post">
            <label>Employee Name:</label><br>
            <input type="text" name="employee_name" required><br>
            <label>Leave Type:</label><br>
            <input type="text" name="leave_type" required><br>
            <label>Start Date:</label><br>
            <input type="date" name="start_date" required><br>
            <label>End Date:</label><br>
            <input type="date" name="end_date" required><br>
            <label>Reason:</label><br>
            <textarea name="reason"></textarea><br>
            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>