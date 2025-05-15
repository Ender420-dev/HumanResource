<?php
// DB Connection
include '../../../phpcon/conn.php'; // Adjust path if needed

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize & Retrieve POST values
    $name = trim($_POST['Name']);
    $role = trim($_POST['ROLE']);
    $department = trim($_POST['DEPARTMENT']);

    // Basic validation
    if (empty($name) || empty($role) || empty($department)) {
        die("Error: All fields are required.");
    }

    // Insert Query
    $sql = "INSERT INTO competency_framework (NAME, ROLE, DEPARTMENT) VALUES (?, ?, ?)";

    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $connection->error);
    }

    $stmt->bind_param("sss", $name, $role, $department);

    if ($stmt->execute()) {
        // Success - Redirect with success message
        header("Location: competency.php?success=1");
        exit();
    } else {
        // Error Handling
        echo "Error inserting record: " . $connection->error;
    }

    $stmt->close();
    $connection->close();
} else {
    echo "Invalid request method.";
}
?>
