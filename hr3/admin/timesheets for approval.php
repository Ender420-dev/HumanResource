<?php
session_start();
ob_start(); // Start output buffering
$title = 'Timesheets Approval';
include_once 'admin.php'; // Assuming this includes necessary headers and navigation
include_once '../connections.php'; // Now includes $conn_hr1, $conn_hr2, $conn_hr3, $conn_hr4

$message = ''; // For displaying success/error messages

// Check if a timesheet_id is provided via GET for modal details
if (isset($_GET['action']) && $_GET['action'] === 'fetch_timesheet_details' && isset($_GET['timesheet_id'])) {
    $timesheet_id = (int)$_GET['timesheet_id'];
    $timesheet_details = null;
    $timesheet_entries = [];

    if (isset($conn_hr3) && $conn_hr3 instanceof PDO && isset($conn_hr4) && $conn_hr4 instanceof PDO) {
        try {
            // Fetch main timesheet details from hr3.Timesheets
            // Removed department_name from selection and join
            $stmt_timesheet = $conn_hr3->prepare("
                SELECT
                    t.*,
                    CONCAT(e.first_name, ' ', e.last_name) AS employee_name,
                    appr.first_name AS approver_first_name,
                    appr.last_name AS approver_last_name
                FROM
                    hr3.Timesheets t
                JOIN
                    hr4.Employees e ON t.employee_id = e.employee_id
                LEFT JOIN
                    hr4.Employees appr ON t.approved_by_id = appr.employee_id
                WHERE
                    t.timesheet_id = ?
            ");
            $stmt_timesheet->execute([$timesheet_id]);
            $timesheet_details = $stmt_timesheet->fetch(PDO::FETCH_ASSOC);

            if ($timesheet_details) {
                // Fetch timesheet entries from hr3.TimesheetEntries
                $stmt_entries = $conn_hr3->prepare("
                    SELECT
                        te.*,
                        p.project_name,
                        task.task_name
                    FROM
                        hr3.TimesheetEntries te
                    LEFT JOIN
                        hr3.Projects p ON te.project_id = p.project_id
                    LEFT JOIN
                        hr3.Tasks task ON te.task_id = task.task_id
                    WHERE
                        te.timesheet_id = ?
                    ORDER BY
                        te.entry_date, te.created_at
                ");
                $stmt_entries->execute([$timesheet_id]);
                $timesheet_entries = $stmt_entries->fetchAll(PDO::FETCH_ASSOC);
            }

        } catch (PDOException $e) {
            error_log("Error fetching timesheet details: " . $e->getMessage());
            echo '<div class="alert alert-danger">Error fetching timesheet details: ' . htmlspecialchars($e->getMessage()) . '</div>';
            exit(); // Exit as this is an AJAX response
        }
    } else {
        echo '<div class="alert alert-danger">Database connections not established.</div>';
        exit(); // Exit as this is an AJAX response
    }

    // Output the details for the modal
    if (!$timesheet_details) {
        echo '<div class="alert alert-warning">Timesheet not found.</div>';
    } else {
        ?>
        <p><strong>Employee:</strong> <?= htmlspecialchars($timesheet_details['employee_name']) ?></p>
        <p><strong>Period:</strong> <?= htmlspecialchars($timesheet_details['start_date']) ?> to <?= htmlspecialchars($timesheet_details['end_date']) ?></p>
        <p><strong>Total Hours Submitted:</strong> <?= htmlspecialchars($timesheet_details['total_hours_submitted']) ?></p>
        <p><strong>Status:</strong> <span class="badge bg-<?=
            ($timesheet_details['status'] == 'Approved' ? 'success' :
            ($timesheet_details['status'] == 'Rejected' ? 'danger' :
            ($timesheet_details['status'] == 'Pending Approval' ? 'warning text-dark' : 'info')))
        ?>"><?= htmlspecialchars($timesheet_details['status']) ?></span></p>
        <p><strong>Submitted On:</strong> <?= htmlspecialchars((new DateTime($timesheet_details['submission_date']))->format('Y-m-d H:i')) ?></p>
        <?php if ($timesheet_details['approved_by_id']): ?>
            <p><strong>Approved/Rejected By:</strong> <?= htmlspecialchars($timesheet_details['approver_first_name'] . ' ' . $timesheet_details['approver_last_name']) ?></p>
            <p><strong>Approved/Rejected Date:</strong> <?= htmlspecialchars((new DateTime($timesheet_details['approved_date']))->format('Y-m-d H:i')) ?></p>
            <p><strong>Manager Comments:</strong> <?= htmlspecialchars($timesheet_details['manager_comments'] ?: 'N/A') ?></p>
        <?php endif; ?>
        <p><strong>Employee Notes:</strong> <?= htmlspecialchars($timesheet_details['employee_notes'] ?: 'N/A') ?></p>

        <h6 class="mt-4">Timesheet Entries:</h6>
        <?php if (!empty($timesheet_entries)): ?>
            <table class="table table-bordered table-sm mt-2">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Project</th>
                        <th>Task</th>
                        <th>Hours Logged</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($timesheet_entries as $entry): ?>
                        <tr>
                            <td><?= htmlspecialchars($entry['entry_date']) ?></td>
                            <td><?= htmlspecialchars($entry['project_name'] ?: 'N/A') ?></td>
                            <td><?= htmlspecialchars($entry['task_name'] ?: 'N/A') ?></td>
                            <td><?= htmlspecialchars($entry['hours_logged']) ?></td>
                            <td><?= htmlspecialchars($entry['activity_description'] ?: 'N/A') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No entries found for this timesheet.</p>
        <?php endif;
    }
    exit(); // Crucial to exit after serving AJAX content
}


// Initialize filter variables
$filter_employee_id = $_GET['employee_id'] ?? '';
// $filter_department_id = $_GET['department_id'] ?? ''; // Removed
$filter_week_ending = $_GET['week_ending'] ?? '';
$filter_status = $_GET['status'] ?? 'Pending Approval'; // Default to Pending Approval

// --- Fetch Filter Options (Employees, Week Endings) ---
$employees = [];
// $departments = []; // Removed
$week_endings = []; // Will store unique end_date for timesheets

// Ensure hr3 and hr4 connections are established for primary operations
if (isset($conn_hr3) && $conn_hr3 instanceof PDO && isset($conn_hr4) && $conn_hr4 instanceof PDO) {
    try {
        // Fetch Employees for filter from hr4 database
        $stmt_employees = $conn_hr4->query("SELECT employee_id, first_name, last_name FROM Employees ORDER BY last_name, first_name");
        $employees = $stmt_employees->fetchAll(PDO::FETCH_ASSOC);

        // Removed Fetch Departments for filter from hr4 database
        // $stmt_departments = $conn_hr4->query("SELECT department_id, department_name FROM Departments ORDER BY department_name");
        // $departments = $stmt_departments->fetchAll(PDO::FETCH_ASSOC);

        // Fetch unique Week Ending dates for filter from hr3 database (Timesheets table is in hr3)
        $stmt_week_endings = $conn_hr3->query("SELECT DISTINCT end_date FROM Timesheets ORDER BY end_date DESC");
        $week_endings = $stmt_week_endings->fetchAll(PDO::FETCH_COLUMN);

    } catch (PDOException $e) {
        $message = "Error fetching filter options: " . $e->getMessage();
        error_log("Database error fetching filter options: " . $e->getMessage());
    }
} else {
    $message = "Database connections not established. Please check connections.php.";
    error_log("Database connection failed in timesheet approval.php (initial load)");
}

// --- Handle Form Submissions (Filtering and Actions) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['timesheet_ids'])) {
        $timesheet_ids = $_POST['timesheet_ids'];
        $action = $_POST['action'];
        $approver_id = $_SESSION['employee_id'] ?? 1; // **IMPORTANT: Use actual logged-in user ID**

        try {
            // Use conn_hr3 for Timesheets and TimesheetApprovalHistory
            $conn_hr3->beginTransaction();

            foreach ($timesheet_ids as $id) {
                $current_timesheet_id = (int)$id;
                $new_status = '';
                $comments = '';

                switch ($action) {
                    case 'approve_selected':
                        $new_status = 'Approved';
                        $comments = 'Timesheet approved in bulk.';
                        break;
                    case 'reject_selected':
                        $new_status = 'Rejected';
                        $comments = $_POST['manager_comments'] ?? 'Timesheet rejected in bulk.'; // Get comments from modal/form
                        break;
                    case 'revisions_selected':
                        $new_status = 'Revisions Requested';
                        $comments = $_POST['revision_comments'] ?? 'Revisions requested in bulk.'; // Get comments from modal/form
                        break;
                    case 'approve_single':
                        $new_status = 'Approved';
                        $comments = 'Timesheet approved.';
                        break;
                    case 'reject_single':
                        $new_status = 'Rejected';
                        $comments = $_POST['manager_comments_single'] ?? 'Timesheet rejected.';
                        break;
                    case 'revisions_single':
                        $new_status = 'Revisions Requested';
                        $comments = $_POST['revision_comments_single'] ?? 'Revisions requested.';
                        break;
                    default:
                        throw new Exception("Invalid action: " . htmlspecialchars($action));
                }

                // Update Timesheet Status in hr3
                $sql_update_timesheet = "UPDATE Timesheets SET status = ?, approved_by_id = ?, approved_date = NOW(), manager_comments = ?, current_approver_id = NULL WHERE timesheet_id = ?";
                $stmt_update = $conn_hr3->prepare($sql_update_timesheet);
                $stmt_update->execute([$new_status, $approver_id, $comments, $current_timesheet_id]);

                // Log Action in History in hr3
                $sql_insert_history = "INSERT INTO TimesheetApprovalHistory (timesheet_id, approver_id, action, comments) VALUES (?, ?, ?, ?)";
                $stmt_history = $conn_hr3->prepare($sql_insert_history);
                $stmt_history->execute([$current_timesheet_id, $approver_id, ucfirst($action), $comments]);
            }

            $conn_hr3->commit();
            $message = "Selected timesheets updated to " . htmlspecialchars($new_status) . " successfully!";
            // Redirect to refresh page and clear POST data
            header("Location: timesheets for approval.php?" . http_build_query($_GET));
            exit();

        } catch (Exception $e) {
            $conn_hr3->rollBack();
            $message = "Error processing timesheet action: " . $e->getMessage();
            error_log("Timesheet action error: " . $e->getMessage());
        }
    }
}

// --- Fetch Filtered Timesheets for Display ---
$timesheets = [];
if (isset($conn_hr3) && $conn_hr3 instanceof PDO && isset($conn_hr4) && $conn_hr4 instanceof PDO) {
    try {
        $sql = "
            SELECT
                t.timesheet_id,
                CONCAT(e.first_name, ' ', e.last_name) AS employee_name,
                -- Removed department_name from selection
                t.start_date,
                t.end_date,
                t.total_hours_submitted,
                t.status,
                t.submission_date
            FROM
                hr3.Timesheets t -- Explicitly reference hr3 for Timesheets
            JOIN
                hr4.Employees e ON t.employee_id = e.employee_id -- Explicitly reference hr4 for Employees
            WHERE
                1=1 -- A true condition to allow easy appending of AND clauses
        ";

        $params = [];

        if (!empty($filter_employee_id)) {
            $sql .= " AND t.employee_id = ?";
            $params[] = $filter_employee_id;
        }
        // if (!empty($filter_department_id)) { // Removed department filter
        //     $sql .= " AND e.department_id = ?";
        //     $params[] = $filter_department_id;
        // }
        if (!empty($filter_week_ending)) {
            $sql .= " AND t.end_date = ?";
            $params[] = $filter_week_ending;
        }
        if (!empty($filter_status)) {
            $sql .= " AND t.status = ?";
            $params[] = $filter_status;
        } else {
            // Default to pending if no status is explicitly chosen
            $sql .= " AND t.status = 'Pending Approval'";
        }

        $sql .= " ORDER BY t.submission_date DESC, employee_name ASC";

        // Execute the query using the hr3 connection since the primary table is Timesheets
        $stmt = $conn_hr3->prepare($sql);
        $stmt->execute($params);
        $timesheets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        $message = "Error retrieving timesheets: " . $e->getMessage();
        error_log("Database error fetching timesheets for approval (main list): " . $e->getMessage());
    }
}
?>

<div class="p-2 gap-3">
    <div class="d-flex col">
        <h6 class=" text-muted pe-none mb-0"><a class="text-decoration-none text-muted" href="">Home</a> > <a class="text-decoration-none text-muted" href="">Time Sheets</a> > <a class="text-decoration-none text-muted" href="">Timesheets Approval</a></h6>
    </div>
    <hr>
    <div class="nav col-12 d-flex justify-content-around">
        <h3><a class="text-decoration-none" href="timesheets.php">Timesheets</a></h3>
        <h3><a href="all timesheets.php" class="text-decoration-none">All Timesheets</a></h3>
        <h3><a href="project & tasks.php" class="text-decoration-none">Projects & Tasks</a></h3>
        <h3><a class="text-decoration-none" href="reports.php">Reports</a></h3>
    </div>
    <hr>
    <div class="container-fluid shadow-lg col p-4">
        <div class="col d-flex flex-column p-4 gap-3">
            <?php if (!empty($message)): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="col d-flex flex-column border border-2 border rounded-3 p-4">
                <h3 class="align-items-center text-center">Filter Timesheets Awaiting Approval</h3>
                <hr>
                <form method="GET" action="timesheets for approval.php" class="row g-3 align-items-end">
                    <div class="col-md-4">
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
                    <div class="col-md-4">
                        <label for="week_ending" class="form-label">Week Ending:</label>
                        <select class="form-select" name="week_ending" id="week_ending">
                            <option value="">Select Week Ending</option>
                            <?php foreach ($week_endings as $date): ?>
                                <option value="<?= htmlspecialchars($date) ?>"
                                    <?= ($filter_week_ending == $date) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($date) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status:</label>
                        <select class="form-select" name="status" id="status">
                            <option value="Pending Approval" <?= ($filter_status == 'Pending Approval') ? 'selected' : '' ?>>Pending Approval</option>
                            <option value="Approved" <?= ($filter_status == 'Approved') ? 'selected' : '' ?>>Approved</option>
                            <option value="Rejected" <?= ($filter_status == 'Rejected') ? 'selected' : '' ?>>Rejected</option>
                            <option value="Revisions Requested" <?= ($filter_status == 'Revisions Requested') ? 'selected' : '' ?>>Revisions Requested</option>
                            <option value="">All</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="timesheets for approval.php" class="btn btn-secondary">Clear Filters</a>
                    </div>
                </form>
            </div>

            <div class="col d-flex flex-column border border-2 border rounded-3 p-4">
                <h3 class="align-items-center text-center">Timesheets Awaiting Approval (Filtered)</h3>
                <hr>
                <form id="bulkActionForm" method="POST" action="timesheets for approval.php">
                    <input type="hidden" name="action" id="bulkActionInput">
                    <table class="table table-striped table-hover border text-center">
                        <thead>
                            <tr>
                                <th class="col-1">
                                    <input class="form-check-input" type="checkbox" id="checkAll">
                                    <label class="form-check-label" for="checkAll"></label>
                                </th>
                                <th>Staff Name</th>
                                <th>Week Ending</th>
                                <th>Total Hours</th>
                                <th>Submitted On</th>
                                <th>Status</th>
                                <th class="col-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($timesheets)): ?>
                                <?php foreach ($timesheets as $timesheet): ?>
                                    <tr>
                                        <td>
                                            <input class="form-check-input timesheet-checkbox" type="checkbox" name="timesheet_ids[]" value="<?= htmlspecialchars($timesheet['timesheet_id']) ?>">
                                        </td>
                                        <td><?= htmlspecialchars($timesheet['employee_name']) ?></td>
                                        <td><?= htmlspecialchars($timesheet['end_date']) ?></td>
                                        <td><?= htmlspecialchars($timesheet['total_hours_submitted']) ?></td>
                                        <td><?= htmlspecialchars((new DateTime($timesheet['submission_date']))->format('Y-m-d H:i')) ?></td>
                                        <td>
                                            <?php
                                                $status_class = '';
                                                switch ($timesheet['status']) {
                                                    case 'Pending Approval': $status_class = 'bg-warning text-dark'; break;
                                                    case 'Approved': $status_class = 'bg-success'; break;
                                                    case 'Rejected': $status_class = 'bg-danger'; break;
                                                    case 'Revisions Requested': $status_class = 'bg-info'; break;
                                                    default: $status_class = 'bg-secondary'; break;
                                                }
                                            ?>
                                            <span class="badge <?= $status_class ?>"><?= htmlspecialchars($timesheet['status']) ?></span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm me-1 view-details-btn" data-bs-toggle="modal" data-bs-target="#viewDetailsModal" data-timesheet-id="<?= htmlspecialchars($timesheet['timesheet_id']) ?>">Review</button>

                                            <?php if ($timesheet['status'] === 'Pending Approval' || $timesheet['status'] === 'Revisions Requested'): ?>
                                                <button type="button" class="btn btn-primary btn-sm me-1 approve-single-btn" data-timesheet-id="<?= htmlspecialchars($timesheet['timesheet_id']) ?>"
                                                    onclick="return confirm('Are you sure you want to approve this timesheet (ID: <?= htmlspecialchars($timesheet['timesheet_id']) ?>)?')">Approve</button>
                                                <button type="button" class="btn btn-warning btn-sm me-1 reject-single-btn" data-bs-toggle="modal" data-bs-target="#rejectModal" data-timesheet-id="<?= htmlspecialchars($timesheet['timesheet_id']) ?>">Reject</button>
                                                <button type="button" class="btn btn-secondary btn-sm revisions-single-btn" data-bs-toggle="modal" data-bs-target="#revisionsModal" data-timesheet-id="<?= htmlspecialchars($timesheet['timesheet_id']) ?>">Revisions</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7">No timesheets found matching the current filters.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div class="mt-3">
                        <h4 class="d-flex align-items-center">Selected Timesheets:
                            <span class="d-flex gap-3 ms-3">
                                <button type="button" class="btn btn-primary" id="approveSelectedBtn">Approve Selected</button>
                                <button type="button" class="btn btn-warning" id="rejectSelectedBtn" data-bs-toggle="modal" data-bs-target="#rejectModalBulk">Reject Selected</button>
                                <button type="button" class="btn btn-secondary" id="revisionsSelectedBtn" data-bs-toggle="modal" data-bs-target="#revisionsModalBulk">Request Revisions for Selected</button>
                            </span>
                        </h4>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectSingleForm" method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Timesheet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="reject_single">
                    <input type="hidden" name="timesheet_ids[]" id="rejectSingleTimesheetId">
                    <div class="mb-3">
                        <label for="managerCommentsSingle" class="form-label">Reason for Rejection:</label>
                        <textarea class="form-control" id="managerCommentsSingle" name="manager_comments_single" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="revisionsModal" tabindex="-1" aria-labelledby="revisionsModalLabel" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="revisionsSingleForm" method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="revisionsModalLabel">Request Revisions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="revisions_single">
                    <input type="hidden" name="timesheet_ids[]" id="revisionsSingleTimesheetId">
                    <div class="mb-3">
                        <label for="revisionCommentsSingle" class="form-label">Comments for Employee:</label>
                        <textarea class="form-control" id="revisionCommentsSingle" name="revision_comments_single" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-secondary">Request Revisions</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModalBulk" tabindex="-1" aria-labelledby="rejectModalBulkLabel" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectBulkForm" method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalBulkLabel">Reject Selected Timesheets</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="reject_selected">
                    <div class="mb-3">
                        <label for="managerCommentsBulk" class="form-label">Reason for Rejection (for all selected):</label>
                        <textarea class="form-control" id="managerCommentsBulk" name="manager_comments" rows="3" required></textarea>
                    </div>
                    <div id="selectedTimesheetsSummaryReject"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Selected</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="revisionsModalBulk" tabindex="-1" aria-labelledby="revisionsModalBulkLabel" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="revisionsBulkForm" method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="revisionsModalBulkLabel">Request Revisions for Selected Timesheets</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="revisions_selected">
                    <div class="mb-3">
                        <label for="revisionCommentsBulk" class="form-label">Comments for Employees (for all selected):</label>
                        <textarea class="form-control" id="revisionCommentsBulk" name="revision_comments" rows="3" required></textarea>
                    </div>
                    <div id="selectedTimesheetsSummaryRevisions"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-secondary">Request Revisions for Selected</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDetailsModalLabel">Timesheet Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewDetailsModalBody">
                Loading timesheet details...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to get selected timesheet IDs and names
    function getSelectedTimesheetsInfo() {
        const selectedIds = [];
        const selectedNames = [];
        document.querySelectorAll('.timesheet-checkbox:checked').forEach(checkbox => {
            selectedIds.push(checkbox.value);
            const row = checkbox.closest('tr');
            selectedNames.push(row.children[1].textContent); // Get staff name from second cell
        });
        return { ids: selectedIds, names: selectedNames };
    }

    // --- Single Action Buttons ---

    // Approve Single Button
    document.querySelectorAll('.approve-single-btn').forEach(button => {
        button.addEventListener('click', function() {
            const timesheetId = this.dataset.timesheetId;
            // Create a hidden form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = ''; // Submit to the same page

            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'approve_single';
            form.appendChild(actionInput);

            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'timesheet_ids[]';
            idInput.value = timesheetId;
            form.appendChild(idInput);

            document.body.appendChild(form);
            form.submit();
        });
    });

    // Reject Single Button (opens modal)
    document.querySelectorAll('.reject-single-btn').forEach(button => {
        button.addEventListener('click', function() {
            const timesheetId = this.dataset.timesheetId;
            document.getElementById('rejectSingleTimesheetId').value = timesheetId;
            // Clear previous comments
            document.getElementById('managerCommentsSingle').value = '';
        });
    });

    // Revisions Single Button (opens modal)
    document.querySelectorAll('.revisions-single-btn').forEach(button => {
        button.addEventListener('click', function() {
            const timesheetId = this.dataset.timesheetId;
            document.getElementById('revisionsSingleTimesheetId').value = timesheetId;
            // Clear previous comments
            document.getElementById('revisionCommentsSingle').value = '';
        });
    });

    // --- Bulk Action Buttons ---

    // Select All Checkbox
    document.getElementById('checkAll').addEventListener('change', function() {
        document.querySelectorAll('.timesheet-checkbox').forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Approve Selected Button
    document.getElementById('approveSelectedBtn').addEventListener('click', function() {
        const { ids, names } = getSelectedTimesheetsInfo();
        if (ids.length === 0) {
            alert('Please select at least one timesheet to approve.');
            return;
        }

        if (confirm('Are you sure you want to approve ' + ids.length + ' selected timesheet(s)?\n\n' + names.join(', ') + '')) {
            const form = document.getElementById('bulkActionForm');
            document.getElementById('bulkActionInput').value = 'approve_selected';
            // Clear previous checkboxes in form, then add selected ones
            form.querySelectorAll('input[name="timesheet_ids[]"]').forEach(input => input.remove());
            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'timesheet_ids[]';
                input.value = id;
                form.appendChild(input);
            });
            form.submit();
        }
    });

    // Reject Selected Button (opens modal)
    document.getElementById('rejectSelectedBtn').addEventListener('click', function() {
        const { ids, names } = getSelectedTimesheetsInfo();
        if (ids.length === 0) {
            alert('Please select at least one timesheet to reject.');
            return;
        }
        // Populate the modal with hidden inputs and summary
        const form = document.getElementById('rejectBulkForm');
        form.querySelectorAll('input[name="timesheet_ids[]"]').forEach(input => input.remove()); // Clear previous
        ids.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'timesheet_ids[]';
            input.value = id;
            form.appendChild(input);
        });
        document.getElementById('selectedTimesheetsSummaryReject').innerHTML = 'Selected: ' + names.join(', ');
        document.getElementById('managerCommentsBulk').value = ''; // Clear previous comments
        // Modal will open via data-bs-target
    });

    // Request Revisions Selected Button (opens modal)
    document.getElementById('revisionsSelectedBtn').addEventListener('click', function() {
        const { ids, names } = getSelectedTimesheetsInfo();
        if (ids.length === 0) {
            alert('Please select at least one timesheet to request revisions for.');
            return;
        }
        // Populate the modal with hidden inputs and summary
        const form = document.getElementById('revisionsBulkForm');
        form.querySelectorAll('input[name="timesheet_ids[]"]').forEach(input => input.remove()); // Clear previous
        ids.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'timesheet_ids[]';
            input.value = id;
            form.appendChild(input);
        });
        document.getElementById('selectedTimesheetsSummaryRevisions').innerHTML = 'Selected: ' + names.join(', ');
        document.getElementById('revisionCommentsBulk').value = ''; // Clear previous comments
        // Modal will open via data-bs-target
    });

    // View Details Modal (AJAX call to fetch details)
    document.querySelectorAll('.view-details-btn').forEach(button => {
        button.addEventListener('click', function() {
            const timesheetId = this.dataset.timesheetId;
            const modalBody = document.getElementById('viewDetailsModalBody');
            modalBody.innerHTML = 'Loading timesheet details...'; // Show loading message

            // Make an AJAX request to the same file, but with a specific action parameter
            fetch('timesheets for approval.php?action=fetch_timesheet_details&timesheet_id=' + timesheetId)
                .then(response => response.text())
                .then(data => {
                    modalBody.innerHTML = data;
                })
                .catch(error => {
                    console.error('Error fetching timesheet details:', error);
                    modalBody.innerHTML = '<div class="alert alert-danger">Error loading details. Please try again.</div>';
                });
        });
    });
});
</script>