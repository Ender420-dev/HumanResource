<?php
session_start(); // Start session to use $_SESSION messages
ob_start();      // Start output buffering

include_once '../connections.php'; // This should provide $conn_hr1 and $conn_hr3

// Ensure the HR3 connection is available
if (!isset($conn_hr3) || !$conn_hr3) {
    $_SESSION['message'] = "Database connection for HR3 is not available. Cannot assign staff.";
    $_SESSION['message_type'] = "danger";
    header("Location: ../admin/shifting schedule.php");
    ob_end_flush();
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $EmployeeID = $_POST['EmployeeID'] ?? null;
    $shift_id = $_POST['shift_id'] ?? null;
    $schedule_date = $_POST['schedule_date'] ?? null;
    $department_id = $_POST['department_id'] ?? null; // optional

    // Log received POST data for debugging
    error_log("assign_staff.php received POST: " . print_r($_POST, true));

    // Check for missing required data
    if (!$EmployeeID || !$shift_id || !$schedule_date) {
        $_SESSION['message'] = "Missing required data for assigning staff. Please ensure Employee, Shift, and Date are selected.";
        $_SESSION['message_type'] = "danger";
        // Redirect back, attempting to preserve the date context
        $redirect_date = $_POST['schedule_date'] ?? date('Y-m-d');
        header("Location: ../admin/shifting schedule.php?date_from=" . urlencode($redirect_date));
        ob_end_flush();
        exit();
    }

    // Convert empty department_id string to null for DB (if department wasn't selected)
    if ($department_id === '') {
        $department_id = null;
    }

    $sql = "INSERT INTO hr3.employee_schedules (EmployeeID, shift_id, schedule_date, department_id)
            VALUES (:EmployeeID, :shift_id, :schedule_date, :department_id)
            ON DUPLICATE KEY UPDATE shift_id = VALUES(shift_id), department_id = VALUES(department_id)";

    try {
        $stmt = $conn_hr3->prepare($sql); // Use $conn_hr3 for hr3 database operations

        $stmt->bindValue(':EmployeeID', $EmployeeID, PDO::PARAM_INT);
        $stmt->bindValue(':shift_id', $shift_id, PDO::PARAM_INT);
        $stmt->bindValue(':schedule_date', $schedule_date, PDO::PARAM_STR);

        // Bind department_id correctly (NULL for no specific department)
        if ($department_id === null) {
            $stmt->bindValue(':department_id', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':department_id', $department_id, PDO::PARAM_INT);
        }

        if ($stmt->execute()) {
            $_SESSION['message'] = "Shift assigned successfully!";
            $_SESSION['message_type'] = "success";
            header("Location: ../admin/shifting schedule.php?date_from=" . urlencode($schedule_date));
            ob_end_flush();
            exit();
        } else {
            // This part might catch errors not thrown as PDOExceptions (e.g., if prepare failed silently)
            $error_info = $stmt->errorInfo();
            $_SESSION['message'] = "Error assigning shift: " . htmlspecialchars($error_info[2]);
            $_SESSION['message_type'] = "danger";
            error_log("PDO Statement Error in assign_staff.php: " . $error_info[2]);
            header("Location: ../admin/shifting schedule.php?date_from=" . urlencode($schedule_date));
            ob_end_flush();
            exit();
        }
    } catch (PDOException $e) {
        // Catch database-related exceptions
        $_SESSION['message'] = "Database error: " . htmlspecialchars($e->getMessage());
        $_SESSION['message_type'] = "danger";
        error_log("PDO Exception in assign_staff.php: " . $e->getMessage());
        header("Location: ../admin/shifting schedule.php?date_from=" . urlencode($schedule_date));
        ob_end_flush();
        exit();
    }
}
?>