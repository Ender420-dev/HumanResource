<?php
session_start();
ob_start();
$title = 'Attendance Tracking';
include_once 'admin.php';
include_once 'addnewlog_modal.php';
// This should establish $conn_hr3 and $conn_hr4
include_once '../connections.php';

// Fetch logs from database
// Using PDO correctly:
$where = [];
$params = [];

if (!empty($_GET['employee_name'])) {
    // Parameterized binding uses '?' for positional placeholders
    $where[] = "CONCAT(e.first_name, ' ', e.last_name) LIKE ?";
    $params[] = "%" . $_GET['employee_name'] . "%";
}
if (!empty($_GET['date_from'])) {
    $where[] = "da.attendance_date >= ?";
    $params[] = $_GET['date_from'];
}
if (!empty($_GET['date_to'])) {
    $where[] = "da.attendance_date <= ?";
    $params[] = $_GET['date_to'];
}
if (!empty($_GET['status'])) {
    $where[] = "da.status = ?";
    $params[] = $_GET['status'];
}

$where_sql = count($where) > 0 ? 'WHERE ' . implode(' AND ', $where) : '';

$sql = "
    SELECT
        da.attendance_date AS date,
        CONCAT(e.first_name, ' ', e.last_name) AS name,
        da.first_clock_in AS time_in,
        da.last_clock_out AS time_out,
        (
            SELECT MIN(a.record_time)
            FROM hr3.Attendance a
            WHERE a.employee_id = da.employee_id
              AND a.record_type = 'Break Start'
              AND DATE(a.record_time) = da.attendance_date
        ) AS break_start,
        (
            SELECT MAX(a.record_time)
            FROM hr3.Attendance a
            WHERE a.employee_id = da.employee_id
              AND a.record_type = 'Break End'
              AND DATE(a.record_time) = da.attendance_date
        ) AS break_end,
        da.total_hours,
        da.status
    FROM hr3.DailyAttendanceSummary da
    JOIN hr4.Employees e ON da.employee_id = e.employee_id
    $where_sql
    ORDER BY da.attendance_date DESC
";

try {
    // Use $conn_hr3 for queries primarily involving hr3 tables,
    // assuming it has permissions to access hr4.Employees via join.
    $stmt = $conn_hr3->prepare($sql);
    $stmt->execute($params);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching attendance logs: " . $e->getMessage());
    $logs = []; // Initialize logs as empty array on error
    // You might want to set a user-friendly error message here as well
    // e.g., $_SESSION['status_message'] = ['type' => 'danger', 'message' => 'Error fetching attendance logs.'];
}

?>

<div class="p-2 gap-3">
    <div class="d-flex col">
        <h6 class="text-muted pe-none mb-0">
            <a class="text-decoration-none text-muted" href="#">Home</a> >
            <a class="text-decoration-none text-muted" href="#">Attendance Tracking</a> >
            <a class="text-decoration-none text-muted" href="#">Employee Logs</a>
        </h6>
    </div>
    <hr>
    <div class="nav col-12 d-flex justify-content-around">
        <h3><a class="text-decoration-none" href="attendance tracking.php">Attendance Tracking</a></h3>
        <h3><a href="pending approval.php" class="text-decoration-none">Pending Approvals</a></h3>
        <h3><a class="text-decoration-none" href="rules and config.php">Rules & Config</a></h3>
        <h3><a class="text-decoration-none" href="report.php">Report</a></h3>
    </div>
    <hr>

    <div class="container-fluid shadow-lg col p-5">
        <div class="col d-flex flex-column border border-2 rounded-3 p-4">
            <h3 class="text-center">Filter Attendance Logs</h3>
            <hr>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <form method="GET" class="d-flex gap-3 justify-content-center flex-wrap">
                    <div>
                        <h4>Employee Name:</h4>
                        <input class="form-control fs-4" name="employee_name" type="text" value="<?= htmlspecialchars($_GET['employee_name'] ?? '') ?>">
                    </div>
                    <div>
                        <h4>Date:</h4>
                        <input class="form-control-lg" type="date" name="date_from" value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>">
                        to
                        <input class="form-control-lg" type="date" name="date_to" value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>">
                    </div>
                    <div>
                        <h4>Status:</h4>
                        <select class="form-select-lg" name="status">
                            <option value="">All</option>
                            <option value="Approved" <?= (($_GET['status'] ?? '') === 'Approved') ? 'selected' : '' ?>>Approved</option>
                            <option value="Rejected" <?= (($_GET['status'] ?? '') === 'Rejected') ? 'selected' : '' ?>>Rejected</option>
                        </select>
                    </div>
                    <div class="align-content-center d-flex align-items-end">
                        <button class="btn btn-primary fs-5 px-3 mx-1" type="submit">Search</button>
                        <a href="employee logs.php" class="btn btn-secondary fs-5 px-3 mx-1">Reset</a>
                    </div>
                </form>

            </div>
        </div>

        <div class="col d-flex flex-column border border-2 rounded-3 p-4 mt-4">
            <h3 class="text-center">Detailed Attendance Log</h3>
            <hr>
            <div class="table-responsive">
                <table class="table table-striped table-hover border text-center">
                    <thead>
                        <tr>
                            <th>Date</th>
                              <th>Employee Name</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Break Start</th>
                            <th>Break End</th>
                            <th>Total Hours</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($logs) > 0): ?>
                            <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><?= htmlspecialchars($log['date']) ?></td>
                                <td><?= htmlspecialchars($log['name']) ?></td>
                                <td><?= htmlspecialchars($log['time_in']) ?></td>
                                <td><?= htmlspecialchars($log['time_out']) ?></td>
                                <td><?= htmlspecialchars($log['break_start'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($log['break_end'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($log['total_hours']) ?></td>
                                <td><?= htmlspecialchars($log['status']) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary">Edit</button>
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="9">No attendance logs found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="d-flex gap-3">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewLogModal">Add New Log</button>
                <button class="btn btn-secondary">Export to CSV</button>
            </div>
        </div>
    </div>
</div>

<?php ob_end_flush(); ?>