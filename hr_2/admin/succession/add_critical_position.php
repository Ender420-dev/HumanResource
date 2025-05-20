<?php
// Database connection
$connection = new mysqli("localhost", "root", "", "your_database_name");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $position_title = $_POST['position_title'];
    $department = $_POST['department'];
    $incumbert = $_POST['incumbert'];
    $successors = $_POST['successors'];
    $risklevel = $_POST['risklevel'];
    $status = $_POST['status'];

    $sql = "INSERT INTO critical_position (POSITION_TITLE, DEPARTMENT, INCUMBERT, SUCCESSORS, RISKLEVEL, STATUS)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ssssss", $position_title, $department, $incumbert, $successors, $risklevel, $status);

    if ($stmt->execute()) {
        echo "<div style='color: green;'>New critical position added successfully.</div>";
    } else {
        echo "<div style='color: red;'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

$connection->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Critical Position</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

<h2 class="mb-4">Add Critical Position</h2>

<form method="POST" action="">
    <div class="mb-3">
        <label class="form-label">Position Title</label>
        <input type="text" name="position_title" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Department</label>
        <select name="department" class="form-select" required>
            <option value="">Select Department</option>
            <option value="Executive">Executive</option>
            <option value="IT">IT</option>
            <option value="Operations">Operations</option>
            <option value="Finance">Finance</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Incumbent</label>
        <input type="text" name="incumbert" class="form-control" placeholder="Name or 'Vacant'" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Successors</label>
        <input type="text" name="successors" class="form-control" placeholder="e.g., 2 Identified or None" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Risk Level</label>
        <select name="risklevel" class="form-select" required>
            <option value="">Select Risk Level</option>
            <option value="High">High</option>
            <option value="Medium">Medium</option>
            <option value="Low">Low</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            <option value="">Select Status</option>
            <option value="Occupied">Occupied</option>
            <option value="Vacant">Vacant</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Add Position</button>
</form>

</body>
</html>
