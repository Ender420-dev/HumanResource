<?php
// Ensure required connections are established
if (!isset($conn_hr1) || !$conn_hr1) {
    die("<div class='alert alert-danger'>HR1 Database connection failed. Cannot fetch employee data.</div>");
}
if (!isset($conn_hr3) || !$conn_hr3) {
    die("<div class='alert alert-danger'>HR3 Database connection failed. Cannot manage schedules.</div>");
}

// --- PHP Logic for Handling POST Requests (formerly process_shift_action.php) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'add_schedule_entry':
            $employeeName = $_POST['employee_name'] ?? '';
            $shift = $_POST['shift'] ?? '';
            $mon = $_POST['mon'] ?? '';
            $tue = $_POST['tue'] ?? '';
            $wed = $_POST['wed'] ?? '';
            $thu = $_POST['thu'] ?? '';
            $fri = $_POST['fri'] ?? '';
            $sat = $_POST['sat'] ?? '';
            $sun = $_POST['sun'] ?? '';

            if (empty($employeeName) || empty($shift) || empty($mon) || empty($tue) || empty($wed) || empty($thu) || empty($fri) || empty($sat) || empty($sun)) {
                $_SESSION['message'] = ['type' => 'danger', 'text' => 'All fields for adding a schedule entry are required.'];
                break;
            }

            try {
                $stmt = $conn_hr3->prepare("SELECT COUNT(*) FROM employee_schedule WHERE employee_name = ? AND shift = ?");
                $stmt->execute([$employeeName, $shift]);
                if ($stmt->fetchColumn() > 0) {
                    $_SESSION['message'] = ['type' => 'warning', 'text' => 'A schedule entry for this employee and shift type already exists. Please edit it instead.'];
                    break;
                }

                $stmt = $conn_hr3->prepare("INSERT INTO employee_schedule (employee_name, shift, mon, tue, wed, thu, fri, sat, sun) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$employeeName, $shift, $mon, $tue, $wed, $thu, $fri, $sat, $sun]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Schedule entry added successfully!'];
            } catch (PDOException $e) {
                $_SESSION['message'] = ['type' => 'danger', 'text' => 'Error adding schedule entry: ' . $e->getMessage()];
                error_log("Error adding schedule entry: " . $e->getMessage());
            }
            break;

        case 'update_schedule_entry':
            $schedId = $_POST['sched_id'] ?? '';
            $employeeName = $_POST['employee_name'] ?? '';
            $shift = $_POST['shift'] ?? '';
            $mon = $_POST['mon'] ?? '';
            $tue = $_POST['tue'] ?? '';
            $wed = $_POST['wed'] ?? '';
            $thu = $_POST['thu'] ?? '';
            $fri = $_POST['fri'] ?? '';
            $sat = $_POST['sat'] ?? '';
            $sun = $_POST['sun'] ?? '';

            if (empty($schedId) || empty($employeeName) || empty($shift) || empty($mon) || empty($tue) || empty($wed) || empty($thu) || empty($fri) || empty($sat) || empty($sun)) {
                $_SESSION['message'] = ['type' => 'danger', 'text' => 'All fields for updating a schedule entry are required.'];
                break;
            }

            try {
                $stmt = $conn_hr3->prepare("UPDATE employee_schedule SET employee_name = ?, shift = ?, mon = ?, tue = ?, wed = ?, thu = ?, fri = ?, sat = ?, sun = ? WHERE sched_id = ?");
                $stmt->execute([$employeeName, $shift, $mon, $tue, $wed, $thu, $fri, $sat, $sun, $schedId]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Schedule entry updated successfully!'];
            } catch (PDOException $e) {
                $_SESSION['message'] = ['type' => 'danger', 'text' => 'Error updating schedule entry: ' . $e->getMessage()];
                error_log("Error updating schedule entry: " . $e->getMessage());
            }
            break;

        case 'delete_schedule_entry':
            $schedId = $_POST['sched_id'] ?? '';

            if (empty($schedId)) {
                $_SESSION['message'] = ['type' => 'danger', 'text' => 'Schedule ID is required for deletion.'];
                break;
            }

            try {
                $stmt = $conn_hr3->prepare("DELETE FROM employee_schedule WHERE sched_id = ?");
                $stmt->execute([$schedId]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Schedule entry deleted successfully!'];
            } catch (PDOException $e) {
                $_SESSION['message'] = ['type' => 'danger', 'text' => 'Error deleting schedule entry: ' . $e->getMessage()];
                error_log("Error deleting schedule entry: " . $e->getMessage());
            }
            break;

        case 'publish_schedule':
            // This is conceptual as your current hr3.employee_schedule table doesn't have a 'published' flag.
            // You might need to add a column like `is_published BOOLEAN DEFAULT FALSE`, and a `published_date`
            // Then update all relevant rows for the current week/filters.
            $_SESSION['message'] = ['type' => 'info', 'text' => 'Schedule "published" (action placeholder). Implement database update and notification logic here.'];
            break;

        case 'copy_week':
            $targetWeekStart = $_POST['target_week_start'] ?? '';
            // Your hr3.employee_schedule stores static weekly patterns, not specific dates.
            // This button would typically be used if hr3.employee_schedule had a `week_start_date` column.
            $_SESSION['message'] = ['type' => 'warning', 'text' => 'Copy Week (action placeholder). This feature needs a more date-aware schedule table design.'];
            break;

        default:
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Invalid action specified.'];
            break;
    }
    // Redirect to prevent form resubmission on refresh
    header('Location: shifting-schedule.php');
    exit;
}

// --- PHP Logic for Data Fetching (runs after potential POST actions) ---
// Filtering logic
$selectedDepartment = $_GET['department'] ?? '';
$selectedShiftType = $_GET['shift'] ?? '';

// Fetch Departments for Dropdown (Assuming hr3.departments table exists)
$departments = [];
try {
    // Assuming hr3.departments table as per previous discussions
    $stmt = $conn_hr3->query("SELECT department_id, department_name FROM departments ORDER BY department_name");
    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching departments from hr3: " . $e->getMessage());
}

// Fetch Shift Types for Dropdown (from distinct values in hr3.employee_schedule.shift)
$shiftTypes = [];
try {
    $stmt = $conn_hr3->query("SELECT DISTINCT shift FROM employee_schedule ORDER BY shift");
    $rawShiftTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($rawShiftTypes as $st) {
        $shiftTypes[] = ['shift_name' => $st['shift'], 'shift_value' => $st['shift']];
    }
} catch (PDOException $e) {
    error_log("Error fetching shift types from hr3: " . $e->getMessage());
}

// Fetch Employee List for Modals (from hr1.employee_profile_setup)
$employeeList = [];
try {
    $stmt = $conn_hr1->query("SELECT employee_id, full_name FROM employee_profile_setup ORDER BY full_name");
    $employeeList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching employee list from hr1: " . $e->getMessage());
}

// Fetch Schedule Data from hr3.employee_schedule
$scheduleData = [];
try {
    $scheduleQuery = "SELECT sched_id, employee_name, shift, mon, tue, wed, thu, fri, sat, sun
                      FROM employee_schedule WHERE 1=1";

    $params = [];
    if (!empty($selectedShiftType)) {
        $scheduleQuery .= " AND shift = :shift_type";
        $params[':shift_type'] = $selectedShiftType;
    }

    // Department filtering logic would go here if implemented, e.g.:
    /*
    if (!empty($selectedDepartment)) {
        $scheduleQuery .= " AND employee_name IN (SELECT full_name FROM hr1.employee_profile_setup WHERE department_id = :department_id)";
        $params[':department_id'] = $selectedDepartment;
    }
    */

    $scheduleQuery .= " ORDER BY employee_name, shift";

    $stmt = $conn_hr3->prepare($scheduleQuery);
    $stmt->execute($params);
    $scheduleData = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Error fetching schedule data from hr3: " . $e->getMessage());
}

// Displaying session messages
if (isset($_SESSION['message'])) {
    $messageType = $_SESSION['message']['type'];
    $messageText = $_SESSION['message']['text'];
    echo '<div class="alert alert-' . htmlspecialchars($messageType) . ' alert-dismissible fade show" role="alert">';
    echo htmlspecialchars($messageText);
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
    unset($_SESSION['message']); // Clear the message after displaying
}

?>