<?php
ob_start(); // Start output buffering if not already done by main script

// Include database connection if not already included in the main page
if (!isset($conn)) {
    include_once '../connections.php'; 
}

// --- START DEBUGGING: Enable full error reporting ---
// This is crucial for seeing any PHP errors. REMOVE IN PRODUCTION!
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// --- END DEBUGGING ---

// Fetch employees from hr4.employees table
$employees = [];
try {
    $stmt = $conn->prepare("SELECT employee_id, first_name, last_name FROM hr4.employees ORDER BY first_name, last_name");
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching employees: " . $e->getMessage());
    $_SESSION['error_message'] = "Error loading employee list: " . $e->getMessage();
    $employees = [];
}

// Handle form submission for adding a new log
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_log_entry') {
    // --- START DEBUGGING: Check POST data ---
    echo "<h3>Debugging POST Data for Log Entry:</h3><pre>";
    var_dump($_POST);
    echo "</pre><hr>";
    // --- END DEBUGGING ---

    $employee_id = $_POST['employee_id'] ?? null;
    $attendance_date = $_POST['attendance_date'] ?? null;
    $time_in = $_POST['time_in'] ?? null;
    $time_out = $_POST['time_out'] ?? null;
    $break_start = $_POST['break_start'] ?? null;
    $break_end = $_POST['break_end'] ?? null;
    $status = $_POST['status'] ?? null;
    $notes = $_POST['notes'] ?? null;

    // Basic validation
    if ($employee_id && $attendance_date && $time_in) {
        try {
            $insertSql = "INSERT INTO hr3.attendance (employee_id, record_time, record_type, notes, is_manual_entry) VALUES (?, ?, ?, ?, 1)";
            $stmt = $conn->prepare($insertSql);

            // Insert Clock In
            $record_time_in = $attendance_date . ' ' . $time_in;
            // --- START DEBUGGING: Clock In Insert ---
            echo "Attempting to insert Clock In:\n";
            echo "SQL: " . htmlspecialchars($insertSql) . "\n";
            echo "Params: [" . htmlspecialchars($employee_id) . ", " . htmlspecialchars($record_time_in) . ", 'Clock In', " . htmlspecialchars($notes) . "]\n";
            // --- END DEBUGGING ---
            $stmt->execute([$employee_id, $record_time_in, 'Clock In', $notes]);

            // Insert Clock Out if provided
            if (!empty($time_out)) {
                $record_time_out = $attendance_date . ' ' . $time_out;
                // --- START DEBUGGING: Clock Out Insert ---
                echo "Attempting to insert Clock Out:\n";
                echo "SQL: " . htmlspecialchars($insertSql) . "\n";
                echo "Params: [" . htmlspecialchars($employee_id) . ", " . htmlspecialchars($record_time_out) . ", 'Clock Out', " . htmlspecialchars($notes) . "]\n";
                // --- END DEBUGGING ---
                $stmt->execute([$employee_id, $record_time_out, 'Clock Out', $notes]);
            }

            // Insert Break Start/End if provided
            if (!empty($break_start)) {
                $record_break_start = $attendance_date . ' ' . $break_start;
                // --- START DEBUGGING: Break Start Insert ---
                echo "Attempting to insert Break Start:\n";
                echo "SQL: " . htmlspecialchars($insertSql) . "\n";
                echo "Params: [" . htmlspecialchars($employee_id) . ", " . htmlspecialchars($record_break_start) . ", 'Break Start', " . htmlspecialchars($notes) . "]\n";
                // --- END DEBUGGING ---
                $stmt->execute([$employee_id, $record_break_start, 'Break Start', $notes]);
            }
            if (!empty($break_end)) {
                $record_break_end = $attendance_date . ' ' . $break_end;
                // --- START DEBUGGING: Break End Insert ---
                echo "Attempting to insert Break End:\n";
                echo "SQL: " . htmlspecialchars($insertSql) . "\n";
                echo "Params: [" . htmlspecialchars($employee_id) . ", " . htmlspecialchars($record_break_end) . ", 'Break End', " . htmlspecialchars($notes) . "]\n";
                // --- END DEBUGGING ---
                $stmt->execute([$employee_id, $record_break_end, 'Break End', $notes]);
            }

            $_SESSION['success_message'] = "Log entry added successfully!";
        } catch (PDOException $e) {
            error_log("Error saving log entry: " . $e->getMessage());
            $_SESSION['error_message'] = "Failed to add log entry: " . $e->getMessage();
            // --- START DEBUGGING: PDO Exception ---
            echo "<h3>PDO Exception Caught:</h3><pre>";
            echo "Error: " . htmlspecialchars($e->getMessage()) . "\n";
            echo "Code: " . htmlspecialchars($e->getCode()) . "\n";
            echo "Trace: " . htmlspecialchars($e->getTraceAsString()) . "\n";
            echo "</pre><hr>";
            // --- END DEBUGGING ---
        }
    } else {
        $_SESSION['error_message'] = "Please fill in all required fields (Employee, Date, Time In).";
    }
    // Redirect to prevent form resubmission
    header('Location: employee logs.php');
    exit();
}
?>

<div class="modal fade" id="addNewLogModal" tabindex="-1" aria-labelledby="addNewLogLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewLogLabel">Add New Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addNewLogForm" method="POST" action="addnewlog_modal.php"> 
                    <input type="hidden" name="action" value="save_log_entry">
                    <div class="px-2 m-4">
                        <div class="mb-3">
                            <label for="employeeSelect" class="form-label"><h4>Employee Name:</h4></label>
                            <select class="form-select form-select-lg" name="employee_id" id="employeeSelect" required>
                                <option value="">Select Employee</option>
                                <?php foreach ($employees as $employee): ?>
                                    <option value="<?= htmlspecialchars($employee['employee_id']) ?>">
                                        <?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="logDate" class="form-label"><h4>Date:</h4></label>
                            <input class="form-control form-control-lg" type="date" name="attendance_date" id="logDate" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="timeIn" class="form-label"><h4>Time In:</h4></label>
                                <input class="form-control form-control-lg" type="time" name="time_in" id="timeIn" required>
                            </div>
                            <div class="col-md-6">
                                <label for="timeOut" class="form-label"><h4>Time Out:</h4></label>
                                <input class="form-control form-control-lg" type="time" name="time_out" id="timeOut">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="breakStart" class="form-label"><h4>Break Start:</h4></label>
                                <input class="form-control form-control-lg" type="time" name="break_start" id="breakStart">
                            </div>
                            <div class="col-md-6">
                                <label for="breakEnd" class="form-label"><h4>Break End:</h4></label>
                                <input class="form-control form-control-lg" type="time" name="break_end" id="breakEnd">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="logStatus" class="form-label"><h4>Status:</h4></label>
                            <select class="form-select form-select-lg" name="status" id="logStatus">
                                <option value="Approved">Approved</option>
                                <option value="Pending">Pending</option>
                                <option value="Rejected">Rejected</option>
                                <option value="Manual Clock Out">Manual Clock Out</option>
                                <option value="Manual Clock In">Manual Clock In</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="logNotes" class="form-label"><h4>Notes:</h4></label>
                            <textarea class="form-control fs-5" name="notes" id="logNotes" rows="3"></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-warning">Save Log Entry</button>
                            <button type="reset" class="btn btn-primary">Clear</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addNewLogModal = document.getElementById('addNewLogModal');
    const addNewLogForm = document.getElementById('addNewLogForm');

    // Reset form when modal is hidden
    addNewLogModal.addEventListener('hidden.bs.modal', function () {
        addNewLogForm.reset();
    });

    // You might want to pre-fill the date with today's date
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0'); // Months start at 0!
    const dd = String(today.getDate()).padStart(2, '0');
    document.getElementById('logDate').value = `${yyyy}-${mm}-${dd}`;
});
</script>