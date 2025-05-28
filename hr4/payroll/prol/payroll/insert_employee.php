<?php
// $conn = new mysqli("localhost", "username", "password", "your_database");
include '../Database/db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$first_name     = $_POST['first_name'];
$last_name      = $_POST['last_name'];
$gender         = $_POST['gender'];
$date_of_birth  = $_POST['date_of_birth'];
$email          = $_POST['email'];
$position_id    = !empty($_POST['position_id']) ? $_POST['position_id'] : null;
$hire_date      = $_POST['hire_date'];
$status         = $_POST['status'];

// Insert employee
$stmt = $conn->prepare("INSERT INTO employees 
    (first_name, last_name, gender, date_of_birth, email, position_id, hire_date, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("ssssssss", 
    $first_name, $last_name, $gender, $date_of_birth, $email, $position_id, $hire_date, $status
);

if ($stmt->execute()) {
    echo "Employee added successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
