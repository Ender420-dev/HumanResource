<?php
// Database connection
include '../../../phpcon/conn.php'; // Ensure this defines $connection

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and collect POST data
    $position_title = $_POST['POSITION_TITLE'] ?? '';
    $department     = $_POST['DEPARTMENT'] ?? '';
    $incumbent      = $_POST['INCUMBERT'] ?? '';
    $successors     = $_POST['SUCCESSORS'] ?? '';
    $risklevel      = $_POST['RISKLEVEL'] ?? '';
    $status         = $_POST['STATUS'] ?? '';

    // Validate required fields
    if (!empty($position_title) && !empty($department) && !empty($incumbent)) {
        $sql = "INSERT INTO critical_position (POSITION_TITLE, DEPARTMENT, INCUMBERT, SUCCESSORS, RISKLEVEL, STATUS)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ssssss", $position_title, $department, $incumbent, $successors, $risklevel, $status);

        if ($stmt->execute()) {
            header("Location: succession.php?success=1");
            exit;
        } else {
            echo "Error executing query: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Please fill in all required fields.";
    }
} else {
    echo "Invalid request method.";
}

$connection->close();
?>
