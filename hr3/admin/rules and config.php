<?php
session_start();
ob_start();

// Include database connection
include_once '../connections.php'; // Ensure this path is correct

// --- PHP Logic for Handling Form Submission (Add New Holiday) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_new_holiday') {
    $holiday_name = trim($_POST['holiday_name'] ?? '');
    $holiday_date = $_POST['holiday_date'] ?? null;
    $holiday_type = $_POST['holiday_type'] ?? null;
    $applies_to = $_POST['applies_to'] ?? null;
    $description = trim($_POST['description'] ?? '');

    // Basic validation
    if (empty($holiday_name) || empty($holiday_date) || empty($holiday_type)) {
        $_SESSION['status_message'] = ['type' => 'danger', 'message' => "Error: Holiday Name, Date, and Type are required."];
        header('Location: rules and config.php');
        exit();
    }

    try {
        $conn->beginTransaction(); // Start transaction

        // Assuming a table named 'hr3.holidays' exists
        // Adjust table name and columns as per your actual database schema
        $insertSql = "INSERT INTO hr3.holidays (name, date, type, applies_to, description) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertSql);
        
        $stmt->execute([$holiday_name, $holiday_date, $holiday_type, $applies_to, $description]);

        $conn->commit(); // Commit transaction
        $_SESSION['status_message'] = ['type' => 'success', 'message' => "New holiday added successfully!"];

    } catch (PDOException $e) {
        $conn->rollBack(); // Rollback on error
        $_SESSION['status_message'] = ['type' => 'danger', 'message' => "Database Error adding holiday: " . $e->getMessage()];
        error_log("Error adding new holiday: " . $e->getMessage()); // Log for debugging
    } catch (Exception $e) {
        $conn->rollBack(); // Rollback on other errors
        $_SESSION['status_message'] = ['type' => 'danger', 'message' => "Application Error adding holiday: " . $e->getMessage()];
        error_log("Application Error adding new holiday: " . $e->getMessage()); // Log for debugging
    }

    // Redirect to prevent form resubmission and display messages
    header('Location: rules and config.php');
    exit();
}

// --- PHP Logic for Fetching Holidays for Display ---
$holidays = [];
try {
    // Assuming a table named 'hr3.holidays' exists with 'name', 'date', 'type' columns
    $stmt = $conn->prepare("SELECT name, date, type FROM hr3.holidays ORDER BY date ASC");
    $stmt->execute();
    $holidays = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching holidays: " . $e->getMessage());
    $_SESSION['status_message'] = ['type' => 'danger', 'message' => "Error loading holidays: " . $e->getMessage()];
    $holidays = []; // Ensure it's an empty array if an error occurs
}

$title = 'Attendance Tracking';
include_once 'admin.php';
// The modal file now includes the PHP for fetching employees for its dropdown.
// Ensure addholiday_modal.php is included AFTER the above POST handling logic
// so that the $_SESSION message can be set before rendering.
include_once 'addholiday_modal.php'; 
?>
<div class="p-2 gap-3">
    <div class="d-flex col">
        <h6 class=" text-muted pe-none mb-0"><a class="text-decoration-none text-muted" href="">Home</a> > <a class="text-decoration-none text-muted" href="">Attendance Tracking</a> > <a class="text-decoration-none text-muted" href="">Rules and Configurations</a></h6>
    </div>
    <hr>
    <div class="nav col-12 d-flex justify-content-around">
        <h3>
            <a class="text-decoration-none" href="attendance tracking.php">Attendance Tracking</a>
        </h3>
        <h3>
            <a class="text-decoration-none" href="employee logs.php">Employee Logs</a>
        </h3>
        <h3>
            <a href="pending approval.php" class="text-decoration-none">Pending Approvals</a>
        </h3>
        <h3>
            <a class="text-decoration-none" href="report.php">Report</a>
        </h3>
    </div>
    <hr>
    <div class="container-fluid shadow-lg col p-5">
        <?php
        // Display status messages if any
        if (isset($_SESSION['status_message'])) {
            $msg_type = $_SESSION['status_message']['type'];
            $msg_text = $_SESSION['status_message']['message'];
            echo '<div class="alert alert-' . $msg_type . ' alert-dismissible fade show" role="alert">';
            echo $msg_text;
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';
            unset($_SESSION['status_message']); // Clear the message after displaying
        }
        ?>
        <div class="col d-flex flex-column border border-2 border rounded-3 p-4">
            <h3 class="align-items-center text-center">Rules and Configurations</h3>
            <hr>
            <div class="col d-flex flex-column border border-2 border rounded-3 p-4 justify-content-center">
                <div>
                    <ul class="list-group list-group-flush text-center gap-3">
                        <li class="d-flex fs-3">Standard Work Hours/Day: 
                            <span class="d-flex fs-3">
                                <input class="form-control fs-6" type="number" name="" id="" min="4" max="24">  hours
                            </span>
                        </li>
                        <li class="d-flex fs-3">Grace Period for Clock In (min): 
                            <span class="d-flex fs-3">
                                <input class="form-control fs-6" type="number" name="" id="" min="10" max="30"> minutes
                            </span>
                        </li>
                        <li class="d-flex fs-3">Auto Clock Out (if no entry): 
                            <span class="d-flex fs-3">
                                <input class="form-control fs-6" type="time" name="" id="">
                            </span>
                        </li>
                        <li class="d-flex fs-3">Overtime Calculation Starts After: 
                            <span class="d-flex fs-3">
                                <input class="form-control fs-6" type="number" name="" id="" min="30" max="100"> hours/week
                            </span>
                        </li>
                        <li class="d-flex fs-3">
                            <span class="d-flex fs-3">
                                <button class="btn btn-primary">Save General Rules</button>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
            <h3 class="text-center">Holiday Management</h3>
            <hr>
            <div class="col d-flex flex-column border border-2 border rounded-3 p-4 justify-content-center">
                <div>
                    <table class="table table-striped table-hover border text-center">
                        <thead>
                            <tr>
                                <th class="col-1">Check</th>
                                <th>Holiday Name</th>
                                <th>Date</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($holidays) > 0): ?>
                                <?php foreach ($holidays as $holiday): ?>
                                <tr>
                                    <td class="col-1"><input type="checkbox" class="form-check-input border border-secondary-subtle"></td>
                                    <td><?php echo htmlspecialchars($holiday['name']); ?></td>
                                    <td><?php echo htmlspecialchars($holiday['date']); ?></td>
                                    <td><?php echo htmlspecialchars($holiday['type']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4">No holidays found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div class="d-flex gap-3">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewHolidayModal">Add New Holiday</button>
                        <button class="btn btn-secondary">Remove Holiday</button>
                        <button class="btn btn-warning">Edit Holiday</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>