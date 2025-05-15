<?php
include '../../../phpcon/conn.php'; // Adjust the path if needed

if (isset($_POST['submit'])) {
    $fullName = trim($_POST['FULLNAME']);
    $course = trim($_POST['course']);

    if (empty($fullName) || empty($course)) {
        echo "Please fill in all fields.";
        exit();
    }

    $sql = "INSERT INTO trainer_faculty (FULLNAME, course, CREATE_AT) VALUES (?, ?, NOW())";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $fullName, $course);

    if ($stmt->execute()) {
        // Redirect with success message
        header("Location: learning.php?message=Trainer Added Successfully");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
} else {
    echo "Invalid request.";
}
?>
