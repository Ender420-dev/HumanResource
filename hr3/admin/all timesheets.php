<?php
session_start();
ob_start(); // Start output buffering early to catch any accidental output before headers

$title = 'All Timesheets';
include_once 'admin.php'; // Assuming this includes necessary headers and navigation
include_once '../connections.php'; // Include your database connection file

$message = ''; // For displaying success/error messages

// --- Handle CSV Export Request (MUST BE AT THE VERY TOP) ---
if (isset($_GET['action']) && $_GET['action'] === 'export_csv') {
    if (!isset($conn) || !$conn instanceof PDO) {
        header('Location: all timesheets.php?message=' . urlencode('Database connection error for export.'));
        exit();
    }

    $export_employee_id = $_GET['employee_id'] ?? '';
    $export_department_id = $_GET['department_id'] ?? '';
    $export_start_date = $_GET['start_date'] ?? '';
    $export_end_date = $_GET['end_date'] ?? '';
    $export_status = $_GET['status'] ?? '';

    $export_timesheets = [];

    try {
        $sql = "
            SELECT
                t.timesheet_id,
                CONCAT(e.first_name, ' ', e.last_name) AS employee_name,
                COALESCE(d.department_name, e.job_title) AS department_name,
                t.start_date,
                t.end_date,
                t.total_hours_submitted,
                t.status,
                t.submission_date,
                t.approved_date,
                t.manager_comments,
                e.email AS employee_email
            FROM
                Timesheets t
            JOIN
                Employees e ON t.employee_id = e.employee_id
            LEFT JOIN
                Departments d ON e.department_id = d.department_id
            WHERE
                1=1
        ";

        $params = [];

        if (!empty($export_employee_id)) {
            $sql .= " AND t.employee_id = ?";
            $params[] = $export_employee_id;
        }
        if (!empty($export_department_id)) {
            $sql .= " AND e.department_id = ?";
            $params[] = $export_department_id;
        }
        if (!empty($export_start_date)) {
            $sql .= " AND t.end_date >= ?";
            $params[] = $export_start_date;
        }
        if (!empty($export_end_date)) {
            $sql .= " AND t.end_date <= ?";
            $params[] = $export_end_date;
        }
        if (!empty($export_status)) {
            $sql .= " AND t.status = ?";
            $params[] = $export_status;
        }

        $sql .= " ORDER BY t.submission_date DESC, employee_name ASC";

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $export_timesheets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log("Database error fetching timesheets for CSV export: " . $e->getMessage());
        header('Location: all timesheets.php?message=' . urlencode('Error exporting data. Please try again.'));
        exit();
    }

    ob_end_clean(); // Clear any output that might have been buffered

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="timesheets_export_' . date('Ymd_His') . '.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');

    $headers = [
        'Timesheet ID', 'Employee Name', 'Department', 'Start Date', 'End Date',
        'Total Hours', 'Status', 'Submitted On', 'Approved On', 'Manager Comments', 'Employee Email'
    ];
    fputcsv($output, $headers);

    if (!empty($export_timesheets)) {
        foreach ($export_timesheets as $row) {
            $csv_row = [
                $row['timesheet_id'],
                $row['employee_name'],
                $row['department_name'],
                $row['start_date'],
                $row['end_date'],
                $row['total_hours_submitted'],
                $row['status'],
                (new DateTime($row['submission_date']))->format('Y-m-d H:i:s'),
                $row['approved_date'] ? (new DateTime($row['approved_date']))->format('Y-m-d H:i:s') : 'N/A',
                $row['manager_comments'],
                $row['employee_email']
            ];
            fputcsv($output, $csv_row);
        }
    }

    fclose($output);
    exit();
}
// --- END CSV Export Request Handling ---

// --- Handle Timesheet Edit Form Submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_timesheet') {
    $timesheet_id = $_POST['timesheet_id'] ?? null;
    $employee_id = $_POST['employee_id'] ?? null;
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;
    $total_hours = $_POST['total_hours'] ?? null;
    $status = $_POST['status'] ?? null;
    $manager_comments = $_POST['manager_comments'] ?? null;

    // Sanitize and validate inputs
    $timesheet_id = filter_var($timesheet_id, FILTER_VALIDATE_INT);
    $employee_id = filter_var($employee_id, FILTER_VALIDATE_INT);
    $start_date = filter_var($start_date, FILTER_SANITIZE_STRING);
    $end_date = filter_var($end_date, FILTER_SANITIZE_STRING);
    $total_hours = filter_var($total_hours, FILTER_VALIDATE_FLOAT);
    $status = filter_var($status, FILTER_SANITIZE_STRING);
    $manager_comments = filter_var($manager_comments, FILTER_SANITIZE_STRING);

    if (
        $timesheet_id === false || $timesheet_id === null ||
        $employee_id === false || $employee_id === null ||
        empty($start_date) || empty($end_date) ||
        $total_hours === false || $total_hours === null ||
        $total_hours < 0 || $total_hours > 168 ||
        empty($status)
    ) {
        $message = "Please fill all required fields correctly for editing the timesheet.";
    } elseif ($start_date > $end_date) {
        $message = "Start date cannot be after end date.";
    } else {
        try {
            // Determine approved_date update logic
            $approved_date_clause = '';
            $stmt_current_status = $conn->prepare("SELECT status, approved_date FROM Timesheets WHERE timesheet_id = ?");
            $stmt_current_status->execute([$timesheet_id]);
            $current_timesheet = $stmt_current_status->fetch(PDO::FETCH_ASSOC);

            if ($status === 'Approved' && (!$current_timesheet || $current_timesheet['status'] !== 'Approved')) {
                $approved_date_clause = ", approved_date = NOW()";
            } elseif ($status !== 'Approved' && ($current_timesheet && $current_timesheet['status'] === 'Approved')) {
                // If status is changed from Approved to something else, clear approved_date
                $approved_date_clause = ", approved_date = NULL";
            }

            $stmt_update = $conn->prepare("
                UPDATE Timesheets
                SET
                    employee_id = ?,
                    start_date = ?,
                    end_date = ?,
                    total_hours_submitted = ?,
                    status = ?,
                    manager_comments = ?
                    " . $approved_date_clause . "
                WHERE timesheet_id = ?
            ");
            $params_update = [$employee_id, $start_date, $end_date, $total_hours, $status, $manager_comments, $timesheet_id];

            $stmt_update->execute($params_update);
            $message = "Timesheet updated successfully!";
            // Redirect to clear POST data and show the updated list, preserving current filters
            header("Location: all timesheets.php?" . http_build_query($_GET));
            exit();
        } catch (PDOException $e) {
            $message = "Error updating timesheet: " . $e->getMessage();
            error_log("Database error updating timesheet: " . $e->getMessage());
        }
    }
}


// --- Handle Add New Timesheet Form Submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_new_timesheet') {
    $new_employee_id = $_POST['employee_id'] ?? null;
    $new_start_date = $_POST['start_date'] ?? null;
    $new_end_date = $_POST['end_date'] ?? null;
    $new_total_hours = $_POST['total_hours'] ?? null;
    $new_status = $_POST['status'] ?? 'Submitted'; // Default to 'Submitted'

    // Sanitize and validate inputs
    $new_employee_id = filter_var($new_employee_id, FILTER_VALIDATE_INT);
    $new_start_date = filter_var($new_start_date, FILTER_SANITIZE_STRING);
    $new_end_date = filter_var($new_end_date, FILTER_SANITIZE_STRING);
    $new_total_hours = filter_var($new_total_hours, FILTER_VALIDATE_FLOAT);
    $new_status = filter_var($new_status, FILTER_SANITIZE_STRING);

    if (
        $new_employee_id === false || $new_employee_id === null ||
        empty($new_start_date) || empty($new_end_date) ||
        $new_total_hours === false || $new_total_hours === null ||
        $new_total_hours < 0 || $new_total_hours > 168
    ) {
        $message = "Please fill all required fields correctly for the new timesheet.";
    } elseif ($new_start_date > $new_end_date) {
        $message = "Start date cannot be after end date.";
    } else {
        try {
            $stmt_insert = $conn->prepare("
                INSERT INTO Timesheets (employee_id, start_date, end_date, total_hours_submitted, status, submission_date)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt_insert->execute([$new_employee_id, $new_start_date, $new_end_date, $new_total_hours, $new_status]);
            $message = "New timesheet added successfully!";
            // Redirect to clear POST data and show the updated list, preserving current filters
            header("Location: all timesheets.php?" . http_build_query($_GET));
            exit();
        } catch (PDOException $e) {
            $message = "Error adding new timesheet: " . $e->getMessage();
            error_log("Database error adding new timesheet: " . $e->getMessage());
        }
    }
}


// Initialize filter variables for HTML display
$filter_employee_id = $_GET['employee_id'] ?? '';
$filter_department_id = $_GET['department_id'] ?? '';
$filter_start_date = $_GET['start_date'] ?? '';
$filter_end_date = $_GET['end_date'] ?? '';
$filter_status = $_GET['status'] ?? '';

// --- Fetch Filter Options (Employees, Departments) ---
$employees = [];
$departments = [];
$timesheet_statuses = ['Pending Approval', 'Approved', 'Rejected', 'Revisions Requested', 'Submitted'];

if (isset($conn) && $conn instanceof PDO) {
    try {
        $stmt_employees = $conn->query("SELECT employee_id, first_name, last_name FROM Employees ORDER BY last_name, first_name");
        $employees = $stmt_employees->fetchAll(PDO::FETCH_ASSOC);

        $stmt_departments = $conn->query("SELECT department_id, department_name FROM Departments ORDER BY department_name");
        $departments = $stmt_departments->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        $message = "Error fetching filter options: " . $e->getMessage();
        error_log("Database error fetching filter options (HTML part): " . $e->getMessage());
    }
} else {
    $message = "Database connection not established. Please check connections.php.";
    error_log("Database connection failed in all timesheets.php (HTML part)");
}


// --- Fetch Filtered Timesheets for HTML Display ---
$all_timesheets = [];
$total_timesheets_displayed = 0;

if (isset($conn) && $conn instanceof PDO) {
    try {
        $sql = "
            SELECT
                t.timesheet_id,
                t.employee_id, -- Needed for editing
                CONCAT(e.first_name, ' ', e.last_name) AS employee_name,
                COALESCE(d.department_name, e.job_title) AS department_name,
                t.start_date,
                t.end_date,
                t.total_hours_submitted,
                t.status,
                t.submission_date,
                t.approved_date,
                t.manager_comments -- Needed for editing
            FROM
                Timesheets t
            JOIN
                Employees e ON t.employee_id = e.employee_id
            LEFT JOIN
                Departments d ON e.department_id = d.department_id
            WHERE
                1=1
        ";

        $params = [];

        if (!empty($filter_employee_id)) {
            $sql .= " AND t.employee_id = ?";
            $params[] = $filter_employee_id;
        }
        if (!empty($filter_department_id)) {
            $sql .= " AND e.department_id = ?";
            $params[] = $filter_department_id;
        }
        if (!empty($filter_start_date)) {
            $sql .= " AND t.end_date >= ?";
            $params[] = $filter_start_date;
        }
        if (!empty($filter_end_date)) {
            $sql .= " AND t.end_date <= ?";
            $params[] = $filter_end_date;
        }
        if (!empty($filter_status)) {
            $sql .= " AND t.status = ?";
            $params[] = $filter_status;
        }

        $sql .= " ORDER BY t.submission_date DESC, employee_name ASC";

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $all_timesheets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $total_timesheets_displayed = count($all_timesheets);

    } catch (PDOException $e) {
        $message = "Error retrieving timesheets for display: " . $e->getMessage();
        error_log("Database error fetching timesheets for HTML display: " . $e->getMessage());
    }
}
?>

<div class="p-2 gap-3">
    <div class="d-flex col">
        <h6 class="text-muted pe-none mb-0"><a class="text-decoration-none text-muted" href="">Home</a> > <a class="text-decoration-none text-muted" href="">Time Sheets</a> > <a class="text-decoration-none text-muted" href="all timesheets.php">All Timesheets</a></h6>
    </div>
    <hr>
    <div class="nav col-12 d-flex justify-content-around">
        <h3><a class="text-decoration-none" href="timesheets for approval.php">Timesheets for Approval</a></h3>
        <h3><a href="timesheets.php" class="text-decoration-none">Timesheets</a></h3>
        <h3><a href="project & tasks.php" class="text-decoration-none">Projects & Tasks</a></h3>
        <h3><a class="text-decoration-none" href="reports.php">Reports</a></h3>
    </div>
    <hr>
    <div class="container-fluid shadow-lg col p-4">
        <div class="col d-flex flex-column p-4">
            <?php if (!empty($message)): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="col d-flex flex-column border border-2 border rounded-3 p-4">
                <h3 class="align-items-center text-center">Filter All Timesheet Records</h3>
                <hr>
                <form method="GET" action="all timesheets.php" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="employee_id" class="form-label">Employee:</label>
                        <select class="form-select" name="employee_id" id="employee_id">
                            <option value="">Select Employee</option>
                            <?php foreach ($employees as $emp): ?>
                                <option value="<?= htmlspecialchars($emp['employee_id']) ?>"
                                    <?= ($filter_employee_id == $emp['employee_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="department_id" class="form-label">Department:</label>
                        <select class="form-select" name="department_id" id="department_id">
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?= htmlspecialchars($dept['department_id']) ?>"
                                    <?= ($filter_department_id == $dept['department_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($dept['department_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Date Range From:</label>
                        <input class="form-control" type="date" name="start_date" id="start_date" value="<?= htmlspecialchars($filter_start_date) ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">Date Range To:</label>
                        <input class="form-control" type="date" name="end_date" id="end_date" value="<?= htmlspecialchars($filter_end_date) ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status:</label>
                        <select class="form-select" name="status" id="status">
                            <option value="">All Statuses</option>
                            <?php foreach ($timesheet_statuses as $status_option): ?>
                                <option value="<?= htmlspecialchars($status_option) ?>"
                                    <?= ($filter_status == $status_option) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($status_option) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="all timesheets.php" class="btn btn-secondary">Clear Filters</a>
                    </div>
                </form>
            </div>
            <br>
            <div class="col d-flex flex-column border border-2 border rounded-3 p-4">
                <h3 class="align-items-center text-center">All Timesheet Records</h3>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped table-hover border text-center">
                        <thead>
                            <tr>
                                <th>Staff Name</th>
                                <th>Department</th>
                                <th>Week Ending</th>
                                <th>Total Hours</th>
                                <th>Status</th>
                                <th>Submitted On</th>
                                <th>Approved On</th>
                                <th class="col-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($all_timesheets)): ?>
                                <?php foreach ($all_timesheets as $timesheet): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($timesheet['employee_name']) ?></td>
                                        <td><?= htmlspecialchars($timesheet['department_name']) ?></td>
                                        <td><?= htmlspecialchars($timesheet['end_date']) ?></td>
                                        <td><?= htmlspecialchars($timesheet['total_hours_submitted']) ?></td>
                                        <td>
                                            <?php
                                                $status_class = '';
                                                switch ($timesheet['status']) {
                                                    case 'Pending Approval': $status_class = 'bg-warning text-dark'; break;
                                                    case 'Approved': $status_class = 'bg-success'; break;
                                                    case 'Rejected': $status_class = 'bg-danger'; break;
                                                    case 'Revisions Requested': $status_class = 'bg-info'; break;
                                                    case 'Submitted': $status_class = 'bg-primary'; break;
                                                    default: $status_class = 'bg-secondary'; break;
                                                }
                                            ?>
                                            <span class="badge rounded-pill <?= $status_class ?>"><?= htmlspecialchars($timesheet['status']) ?></span>
                                        </td>
                                        <td><?= htmlspecialchars((new DateTime($timesheet['submission_date']))->format('Y-m-d H:i')) ?></td>
                                        <td><?= htmlspecialchars($timesheet['approved_date'] ? (new DateTime($timesheet['approved_date']))->format('Y-m-d H:i') : 'N/A') ?></td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm view-details-btn" data-bs-toggle="modal" data-bs-target="#viewTimesheetDetailsModal" data-timesheet-id="<?= htmlspecialchars($timesheet['timesheet_id']) ?>">View</button>
                                            <button type="button" class="btn btn-secondary btn-sm edit-timesheet-btn"
                                                data-bs-toggle="modal" data-bs-target="#editTimesheetModal"
                                                data-timesheet-id="<?= htmlspecialchars($timesheet['timesheet_id']) ?>"
                                                data-employee-id="<?= htmlspecialchars($timesheet['employee_id']) ?>"
                                                data-start-date="<?= htmlspecialchars($timesheet['start_date']) ?>"
                                                data-end-date="<?= htmlspecialchars($timesheet['end_date']) ?>"
                                                data-total-hours="<?= htmlspecialchars($timesheet['total_hours_submitted']) ?>"
                                                data-status="<?= htmlspecialchars($timesheet['status']) ?>"
                                                data-manager-comments="<?= htmlspecialchars($timesheet['manager_comments']) ?>">Edit</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8">No timesheets found matching the current filters.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div>
                    <h4>Total Timesheets Displayed:
                        <span><?= $total_timesheets_displayed ?></span></h4>
                </div>
                <div class="d-flex gap-2">
                    <form action="all timesheets.php" method="GET" class="d-inline">
                        <input type="hidden" name="action" value="export_csv">
                        <input type="hidden" name="employee_id" value="<?= htmlspecialchars($filter_employee_id) ?>">
                        <input type="hidden" name="department_id" value="<?= htmlspecialchars($filter_department_id) ?>">
                        <input type="hidden" name="start_date" value="<?= htmlspecialchars($filter_start_date) ?>">
                        <input type="hidden" name="end_date" value="<?= htmlspecialchars($filter_end_date) ?>">
                        <input type="hidden" name="status" value="<?= htmlspecialchars($filter_status) ?>">
                        <button type="submit" class="btn btn-primary">Export Filtered Data to CSV</button>
                    </form>

                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTimesheetModal">Add New Timesheet</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewTimesheetDetailsModal" tabindex="-1" aria-labelledby="viewTimesheetDetailsModalLabel" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTimesheetDetailsModalLabel">Timesheet Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewTimesheetDetailsModalBody">
                Loading timesheet details...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addTimesheetModal" tabindex="-1" aria-labelledby="addTimesheetModalLabel" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="all timesheets.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTimesheetModalLabel">Add New Timesheet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_new_timesheet">

                    <div class="mb-3">
                        <label for="newEmployeeSelect" class="form-label">Employee:</label>
                        <select class="form-select" id="newEmployeeSelect" name="employee_id" required>
                            <option value="">Select Employee</option>
                            <?php foreach ($employees as $emp): ?>
                                <option value="<?= htmlspecialchars($emp['employee_id']) ?>">
                                    <?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="newStartDate" class="form-label">Start Date:</label>
                        <input type="date" class="form-control" id="newStartDate" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="newEndDate" class="form-label">End Date:</label>
                        <input type="date" class="form-control" id="newEndDate" name="end_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="newTotalHours" class="form-label">Total Hours Submitted:</label>
                        <input type="number" step="0.01" class="form-control" id="newTotalHours" name="total_hours" required min="0" max="168">
                    </div>
                    <div class="mb-3">
                        <label for="newStatus" class="form-label">Status:</label>
                        <select class="form-select" id="newStatus" name="status" required>
                            <option value="Submitted">Submitted</option>
                            <option value="Pending Approval">Pending Approval</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                            <option value="Revisions Requested">Revisions Requested</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add Timesheet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editTimesheetModal" tabindex="-1" aria-labelledby="editTimesheetModalLabel" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="all timesheets.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTimesheetModalLabel">Edit Timesheet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit_timesheet">
                    <input type="hidden" id="editTimesheetId" name="timesheet_id">

                    <div class="mb-3">
                        <label for="editEmployeeSelect" class="form-label">Employee:</label>
                        <select class="form-select" id="editEmployeeSelect" name="employee_id" required>
                            <option value="">Select Employee</option>
                            <?php foreach ($employees as $emp): ?>
                                <option value="<?= htmlspecialchars($emp['employee_id']) ?>">
                                    <?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editStartDate" class="form-label">Start Date:</label>
                        <input type="date" class="form-control" id="editStartDate" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEndDate" class="form-label">End Date:</label>
                        <input type="date" class="form-control" id="editEndDate" name="end_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTotalHours" class="form-label">Total Hours Submitted:</label>
                        <input type="number" step="0.01" class="form-control" id="editTotalHours" name="total_hours" required min="0" max="168">
                    </div>
                    <div class="mb-3">
                        <label for="editStatus" class="form-label">Status:</label>
                        <select class="form-select" id="editStatus" name="status" required>
                            <?php foreach ($timesheet_statuses as $status_option): ?>
                                <option value="<?= htmlspecialchars($status_option) ?>">
                                    <?= htmlspecialchars($status_option) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editManagerComments" class="form-label">Manager Comments (Optional):</label>
                        <textarea class="form-control" id="editManagerComments" name="manager_comments" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Optional: Auto-hide the alert message after a few seconds
    const alertMessage = document.querySelector('.alert');
    if (alertMessage) {
        setTimeout(() => {
            new bootstrap.Alert(alertMessage).close();
        }, 5000);
    }

    // --- View Details Modal (Still uses AJAX to fetch content) ---
    document.querySelectorAll('.view-details-btn').forEach(button => {
        button.addEventListener('click', function() {
            const timesheetId = this.dataset.timesheetId;
            const modalBody = document.getElementById('viewTimesheetDetailsModalBody');
            modalBody.innerHTML = 'Loading timesheet details...';

            // Make an AJAX request to fetch timesheet details
            // You'll need a separate PHP file (e.g., 'get_timesheet_details.php') for this
            fetch('get_timesheet_details.php?timesheet_id=' + timesheetId)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(data => {
                    modalBody.innerHTML = data;
                })
                .catch(error => {
                    console.error('Error fetching timesheet details:', error);
                    modalBody.innerHTML = '<div class="alert alert-danger">Error loading details.</div>';
                });
        });
    });

    // --- Edit Timesheet Modal ---
    document.querySelectorAll('.edit-timesheet-btn').forEach(button => {
        button.addEventListener('click', function() {
            // Populate the edit modal with data from the clicked row
            document.getElementById('editTimesheetId').value = this.dataset.timesheetId;
            document.getElementById('editEmployeeSelect').value = this.dataset.employeeId;
            document.getElementById('editStartDate').value = this.dataset.startDate;
            document.getElementById('editEndDate').value = this.dataset.endDate;
            document.getElementById('editTotalHours').value = this.dataset.totalHours;
            document.getElementById('editStatus').value = this.dataset.status;
            document.getElementById('editManagerComments').value = this.dataset.managerComments;
        });
    });
});
</script>