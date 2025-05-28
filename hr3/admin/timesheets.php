<?php
session_start();
ob_start(); // Start output buffering
$title = 'Timesheets';
include_once 'admin.php'; // Assuming this includes necessary headers and navigation
include_once '../connections.php'; // Include your database connection file, which sets up $conn_hr3, $conn_hr4 etc.

$message = ''; // For displaying messages (success/error)

// Initialize $pending array
$pending = [];

// --- Database Connection Check ---
// Ensure both HR3 (for timesheets) and HR4 (for employee master data) connections are established.
if (!isset($conn_hr3) || !$conn_hr3 instanceof PDO) {
    $message = "HR3 Database connection not established. Please check connections.php.";
    error_log("HR3 Database connection failed in timesheets for approval.php");
}
if (!isset($conn_hr4) || !$conn_hr4 instanceof PDO) {
    // Append to message if HR3 connection also failed, otherwise start new message
    $message = (empty($message) ? "" : $message . " ") . "HR4 Database connection not established. Please check connections.php.";
    error_log("HR4 Database connection failed in timesheets for approval.php");
}

if (!empty($message)) {
    // If any connection failed, we can't proceed with database operations.
    // The message will be displayed, and subsequent DB operations will be skipped.
} else {
    // --- Fetch Pending Timesheets ---
    try {
        // Query Timesheets from HR3, and Employee/Department data from HR4
        $sql_pending = "
            SELECT
                t.timesheet_id,
                h4_e.first_name,
                h4_e.last_name,
                CONCAT(h4_e.first_name, ' ', h4_e.last_name) AS employee_name,
                COALESCE(h4_d.department_name, 'N/A') AS department, -- Use department name from HR4
                t.start_date,
                t.end_date,
                t.total_hours_submitted,
                t.status
            FROM
                hr3.Timesheets t  -- Explicitly reference hr3 for Timesheets
            JOIN
                hr4.employees h4_e ON t.employee_id = h4_e.employee_id -- Join with HR4 employees
            LEFT JOIN
                hr4.positions h4_p ON h4_e.position_id = h4_p.position_id
            LEFT JOIN
                hr4.departments h4_d ON h4_p.department_id = h4_d.department_id
            WHERE
                t.status = 'Pending Approval'
            ORDER BY
                t.submission_date DESC, h4_e.last_name ASC
        ";
        $stmt_pending = $conn_hr3->prepare($sql_pending); // Use conn_hr3 for this query
        $stmt_pending->execute();
        $pending = $stmt_pending->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        $message = "Error retrieving pending timesheets: " . $e->getMessage();
        error_log("HR3/HR4 Database error fetching pending timesheets: " . $e->getMessage());
    }

    // --- Handle Timesheet Approval/Rejection ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action']) && isset($_POST['timesheet_id'])) {
            $timesheet_id = $_POST['timesheet_id'];
            $action = $_POST['action']; // 'approve' or 'reject'
            // In a real application, you'd get the current user's ID for approved_by_id from hr4.system_users
            $approver_id = 1; // Placeholder: Replace with actual logged-in admin/manager system_user_id or employee_id

            try {
                $conn_hr3->beginTransaction(); // Timesheet updates are on HR3

                if ($action === 'approve') {
                    $new_status = 'Approved';
                    $sql_update = "UPDATE hr3.Timesheets SET status = ?, approved_by_id = ?, approved_date = NOW(), current_approver_id = NULL WHERE timesheet_id = ?";
                } elseif ($action === 'reject') {
                    $new_status = 'Rejected';
                    $sql_update = "UPDATE hr3.Timesheets SET status = ?, approved_by_id = ?, approved_date = NOW(), manager_comments = ?, current_approver_id = NULL WHERE timesheet_id = ?";
                    $manager_comments = $_POST['manager_comments'] ?? 'Rejected by manager.'; // Placeholder
                } else {
                    throw new Exception("Invalid action specified.");
                }

                $stmt_update = $conn_hr3->prepare($sql_update);
                if ($action === 'approve') {
                    $stmt_update->execute([$new_status, $approver_id, $timesheet_id]);
                } elseif ($action === 'reject') {
                    $stmt_update->execute([$new_status, $approver_id, $manager_comments, $timesheet_id]);
                }

                // Log the action in TimesheetApprovalHistory (also in HR3)
                $sql_history = "INSERT INTO hr3.TimesheetApprovalHistory (timesheet_id, approver_id, action, comments) VALUES (?, ?, ?, ?)";
                $stmt_history = $conn_hr3->prepare($sql_history);
                $stmt_history->execute([
                    $timesheet_id,
                    $approver_id,
                    ucfirst($action), // 'Approved' or 'Rejected'
                    ($action === 'reject' ? $manager_comments : 'Timesheet ' . $action . 'd.')
                ]);

                $conn_hr3->commit();
                $message = "Timesheet " . ($action === 'approve' ? 'approved' : 'rejected') . " successfully!";
                // Refresh the page to show updated list
                header("Location: timesheets for approval.php");
                exit();

            } catch (Exception $e) {
                $conn_hr3->rollBack();
                $message = "Error processing timesheet action: " . $e->getMessage();
                error_log("Timesheet action error: " . $e->getMessage());
            }
        }
        // Handle "Approve All" and "Reject Selected" (requires checkboxes on each row)
        // This part needs more complex logic, as it requires iterating through selected IDs.
        // For brevity, it's omitted here but would involve a loop and batch updates.
    }
}

// --- Fetch Compliance & Trends Data ---
$compliance_trends = [
    'submitted_on_time_percent' => 'N/A',
    'avg_overtime_per_staff_last_month' => 'N/A',
    'unapproved_timesheets_total' => 0
];

if (isset($conn_hr3)) { // Only proceed if hr3 connection is available
    try {
        // Unapproved Timesheets (Total) from HR3
        $stmt_unapproved = $conn_hr3->query("SELECT COUNT(*) FROM hr3.Timesheets WHERE status = 'Pending Approval'");
        $compliance_trends['unapproved_timesheets_total'] = $stmt_unapproved->fetchColumn();

        // Timesheets Submitted on Time (Current Week) - Placeholder logic remains
        // You'd need TimesheetSettings for submission deadlines to calculate "on time".
        $compliance_trends['submitted_on_time_percent'] = '95%'; // Sample value for demo

        // Average Overtime Hours/Staff (Last Month) from HR3
        $last_month_start = date('Y-m-01', strtotime('-1 month'));
        $last_month_end = date('Y-m-t', strtotime('-1 month'));

        $sql_overtime = "
            SELECT
                SUM(t.total_hours_submitted) AS total_hours_last_month,
                COUNT(DISTINCT t.employee_id) AS num_employees
            FROM
                hr3.Timesheets t
            WHERE
                t.start_date BETWEEN ? AND ?
                AND t.status = 'Approved' -- Only count approved timesheets
        ";
        $stmt_overtime = $conn_hr3->prepare($sql_overtime);
        $stmt_overtime->execute([$last_month_start, $last_month_end]);
        $overtime_data = $stmt_overtime->fetch(PDO::FETCH_ASSOC);

        if ($overtime_data && $overtime_data['num_employees'] > 0) {
            $total_reported_hours = $overtime_data['total_hours_last_month'];
            $num_employees = $overtime_data['num_employees'];

            // Rough estimate: Assuming ~4.33 weeks in a month
            // Standard hours for one employee for one month = 8 hours/day * 5 days/week * 4.33 weeks/month = 173.2 hours
            $standard_hours_per_employee_per_month = 40 * 4.33; // 40 hours/week
            $total_standard_hours_expected = $standard_hours_per_employee_per_month * $num_employees;

            $total_overtime_calculated = max(0, $total_reported_hours - $total_standard_hours_expected);
            $avg_overtime = ($num_employees > 0) ? round($total_overtime_calculated / $num_employees, 2) : 0;
            $compliance_trends['avg_overtime_per_staff_last_month'] = $avg_overtime;
        } else {
            $compliance_trends['avg_overtime_per_staff_last_month'] = '0.00';
        }

    } catch (PDOException $e) {
        $message = "Error fetching compliance trends from HR3: " . $e->getMessage();
        error_log("HR3 Database error fetching compliance trends: " . $e->getMessage());
    }
}

// --- Fetch Hours Breakdown by Project/Department (Current Week) ---
$hours_breakdown = [];
if (isset($conn_hr3)) { // Only proceed if hr3 connection is available
    try {
        $start_of_current_week = date('Y-m-d', strtotime('last monday'));
        $end_of_current_week = date('Y-m-d', strtotime('sunday this week'));

        // Query TimesheetEntries from HR3, and Projects from HR3
        $sql_breakdown = "
            SELECT
                COALESCE(p.project_name, 'No Project') AS project_name, -- Assuming hr3.Projects table
                SUM(te.hours_logged) AS total_hours
            FROM
                hr3.TimesheetEntries te
            JOIN
                hr3.Timesheets t ON te.timesheet_id = t.timesheet_id
            LEFT JOIN
                hr3.Projects p ON te.project_id = p.project_id -- Assuming 'Projects' table is in hr3
            WHERE
                te.entry_date BETWEEN ? AND ?
                AND t.status = 'Approved' -- Only count approved hours
            GROUP BY
                project_name
            ORDER BY
                total_hours DESC
        ";
        $stmt_breakdown = $conn_hr3->prepare($sql_breakdown);
        $stmt_breakdown->execute([$start_of_current_week, $end_of_current_week]);
        $hours_breakdown = $stmt_breakdown->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        $message = "Error fetching hours breakdown from HR3: " . $e->getMessage();
        error_log("HR3 Database error fetching hours breakdown: " . $e->getMessage());
    }
}

$total_all_breakdown_hours = array_sum(array_column($hours_breakdown, 'total_hours'));

?>

<div class="p-2 gap-3">
    <div class="d-flex col">
        <h6 class=" text-muted pe-none mb-0"><a class="text-decoration-none text-muted" href="">Home</a> > <a class="text-decoration-none text-muted" href="">Time Sheets</a></h6>
    </div>
    <hr>
    <div class="nav col-12 d-flex justify-content-around">
        <h3><a class="text-decoration-none" href="timesheets for approval.php">Timesheets for Approval</a></h3>
        <h3><a href="all timesheets.php" class="text-decoration-none">All Timesheets</a></h3>
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
                <h3 class="align-items-center text-center">Timesheets Pending Approval</h3>
                <hr>
                <form method="POST" action="">
                    <table class="table table-striped table-hover border text-center">
                        <thead>
                            <tr>
                                <th scope="col">Staff Name</th>
                                <th scope="col">Department</th>
                                <th scope="col">Week Ending</th>
                                <th scope="col">Total Hours</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pending)): ?>
                                <?php foreach ($pending as $entry): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($entry['employee_name']) ?></td>
                                        <td><?= htmlspecialchars($entry['department']) ?></td>
                                        <td><?= htmlspecialchars($entry['end_date']) ?></td>
                                        <td><?= htmlspecialchars($entry['total_hours_submitted']) ?></td>
                                        <td>
                                            <span class="badge bg-warning text-dark"><?= htmlspecialchars($entry['status']) ?></span>
                                        </td>
                                        <td class="col-3">
                                            <button type="submit" name="action" value="approve" data-timesheet-id="<?= $entry['timesheet_id'] ?>"
                                                class="btn btn-primary mx-1 approve-btn"
                                                onclick="return confirm('Are you sure you want to approve this timesheet (ID: <?= $entry['timesheet_id'] ?>)?')">Approve</button>
                                            <button type="button" class="btn btn-danger mx-1 reject-btn"
                                                data-bs-toggle="modal" data-bs-target="#rejectModal"
                                                data-timesheet-id="<?= $entry['timesheet_id'] ?>">Reject</button>
                                            <button type="button" class="btn btn-info mx-1 view-details-btn"
                                                data-bs-toggle="modal" data-bs-target="#viewDetailsModal"
                                                data-timesheet-id="<?= $entry['timesheet_id'] ?>">View Details</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">No timesheets pending approval.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </form>
            </div>

            <div id="complianceReportContent" style="display: none;">
                <div class="col d-flex flex-column p-4">
                    <h3 class="align-items-center text-center">Time Sheet Compliance & Trends</h3>
                    <hr>
                    <div>
                        <h4>Timesheets Submitted on Time (Current Week):
                            <span><?= htmlspecialchars($compliance_trends['submitted_on_time_percent']) ?></span>
                        </h4>
                        <h4>
                            Average Overtime Hours/Staff (Last Month):
                            <span><?= htmlspecialchars($compliance_trends['avg_overtime_per_staff_last_month']) ?></span>
                        </h4>
                        <h4>Unapproved Timesheets (Total):
                            <span><?= htmlspecialchars($compliance_trends['unapproved_timesheets_total']) ?></span>
                        </h4><br>
                    </div>
                </div>

                <div class="col d-flex flex-column p-4 mt-3">
                    <h3 class="align-items-center text-center">Hours Breakdown by Project/Department (Current Week)</h3>
                    <hr>
                    <div>
                        <table class="table table-striped table-hover border text-center">
                            <thead>
                                <tr>
                                    <th>Project/Department</th>
                                    <th>Total Hours</th>
                                    <th>% of Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($hours_breakdown) && $total_all_breakdown_hours > 0): ?>
                                    <?php foreach ($hours_breakdown as $entry): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($entry['project_name']) ?></td>
                                            <td><?= htmlspecialchars($entry['total_hours']) ?></td>
                                            <td>
                                                <?= round(($entry['total_hours'] / $total_all_breakdown_hours) * 100, 2) ?>%
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3">No hours breakdown data for the current week.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col d-flex flex-column border border-2 border rounded-3 p-4 mt-3">
                <h3 class="align-items-center text-center">Time Sheet Compliance & Trends Overview</h3>
                <hr>
                <div>
                    <h4>Timesheets Submitted on Time (Current Week):
                        <span><?= htmlspecialchars($compliance_trends['submitted_on_time_percent']) ?></span>
                    </h4>
                    <h4>
                        Average Overtime Hours/Staff (Last Month):
                        <span><?= htmlspecialchars($compliance_trends['avg_overtime_per_staff_last_month']) ?></span>
                    </h4>
                    <h4>Unapproved Timesheets (Total):
                        <span><?= htmlspecialchars($compliance_trends['unapproved_timesheets_total']) ?></span>
                    </h4><br>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#complianceModal">View Compliance Report</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true" aria-bs-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectForm" method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Timesheet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="reject">
                    <input type="hidden" name="timesheet_id" id="rejectTimesheetId">
                    <div class="mb-3">
                        <label for="managerComments" class="form-label">Reason for Rejection:</label>
                        <textarea class="form-control" id="managerComments" name="manager_comments" rows="3" required></textarea>
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

<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" aria-hidden="true" aria-bs-backdrop="false">
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

<div class="modal fade" id="complianceModal" tabindex="-1" aria-labelledby="complianceModalLabel" aria-hidden="true" aria-bs-backdrop="false">
    <div class="modal-dialog modal-xl"> <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="complianceModalLabel">Timesheet Compliance and Trends Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="complianceModalBody">
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle Reject button click to populate modal
    document.querySelectorAll('.reject-btn').forEach(button => {
        button.addEventListener('click', function() {
            const timesheetId = this.dataset.timesheetId;
            document.getElementById('rejectTimesheetId').value = timesheetId;
        });
    });

    // Handle View Details button click to populate modal
    document.querySelectorAll('.view-details-btn').forEach(button => {
        button.addEventListener('click', function() {
            const timesheetId = this.dataset.timesheetId;
            const modalBody = document.getElementById('viewDetailsModalBody');
            modalBody.innerHTML = 'Loading timesheet details...'; // Show loading message

            // Make an AJAX request to fetch timesheet details
            // IMPORTANT: You'll also need to update 'fetch_timesheet_details.php' to use HR3 and HR4 for relevant data.
            fetch('fetch_timesheet_details.php?timesheet_id=' + timesheetId)
                .then(response => response.text())
                .then(html => {
                    modalBody.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error fetching timesheet details:', error);
                    modalBody.innerHTML = '<div class="alert alert-danger">Error loading details. Please try again.</div>';
                });
        });
    });

    // Handle Compliance Report button click to populate modal
    const complianceModal = document.getElementById('complianceModal');
    complianceModal.addEventListener('show.bs.modal', function () {
        const modalBody = document.getElementById('complianceModalBody');
        const complianceContent = document.getElementById('complianceReportContent');
        modalBody.innerHTML = complianceContent.innerHTML; // Copy the content into the modal
    });

    // Optional: Auto-hide the alert message after a few seconds
    const alertMessage = document.querySelector('.alert');
    if (alertMessage) {
        setTimeout(() => {
            alertMessage.style.display = 'none';
        }, 5000); // Hide after 5 seconds
    }
});
</script>

<?php
ob_end_flush(); // End output buffering and send output to browser
?>