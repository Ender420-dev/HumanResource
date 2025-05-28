<?php
session_start();
ob_start(); // Start output buffering

// Include your database connection file
include_once '../connections.php';

// Include your admin.php for common layout (header, footer, etc.)
$title = "Leave Balances"; // Title for the page

// --- Manager Session Check (Crucial for Authorization) ---
// IMPORTANT: Set actual manager's employee_id from session!
$logged_in_manager_employee_id = $_SESSION['employee_id'] ?? 101; 

// Initialize status message (though less critical for a read-only page)
$status_message = $_SESSION['status_message'] ?? null;
unset($_SESSION['status_message']); // Clear message after retrieving

// Initialize filter variables
$filter_fiscal_year = $_GET['fiscal_year'] ?? date('Y'); // Default to current year
$filter_employee_name = $_GET['employee_name'] ?? '';
$filter_leave_type = $_GET['leave_type'] ?? '';

// Data to be displayed
$leave_balances = [];
$all_fiscal_years = [];
$all_leave_types = [];

try {
    // Fetch all available fiscal years from LeaveBalances
    $stmt_years = $conn_hr3->prepare("SELECT DISTINCT fiscal_year FROM hr3.LeaveBalances ORDER BY fiscal_year DESC");
    $stmt_years->execute();
    $all_fiscal_years = $stmt_years->fetchAll(PDO::FETCH_COLUMN);
    if (empty($all_fiscal_years)) {
        $all_fiscal_years[] = date('Y'); // Ensure current year is an option if no data
    }

    // Fetch all available leave types from LeaveTypes
    $stmt_types = $conn_hr3->prepare("SELECT leave_type_id, leave_type_name FROM hr3.LeaveTypes ORDER BY leave_type_name ASC");
    $stmt_types->execute();
    $all_leave_types = $stmt_types->fetchAll(PDO::FETCH_ASSOC);

    // Build WHERE clause for filters
    $where_clauses = [];
    $params = [];

    // Filter by Fiscal Year (always apply this)
    $where_clauses[] = "lb.fiscal_year = ?";
    $params[] = $filter_fiscal_year;

    if ($filter_employee_name) {
        $where_clauses[] = "(e.first_name LIKE ? OR e.last_name LIKE ?)";
        $params[] = '%' . $filter_employee_name . '%';
        $params[] = '%' . $filter_employee_name . '%';
    }
    if ($filter_leave_type) {
        $where_clauses[] = "lt.leave_type_id = ?";
        $params[] = $filter_leave_type;
    }

    $sql_where = count($where_clauses) > 0 ? ' WHERE ' . implode(' AND ', $where_clauses) : '';

    // Fetch leave balances
    $stmt_balances = $conn_hr3->prepare("
        SELECT
            e.first_name,
            e.last_name,
            e.employee_id,
            lt.leave_type_name,
            lb.accrued_days,
            lb.used_days,
            lb.carried_over_days,
            lb.remaining_days
        FROM
            hr3.LeaveBalances lb
        JOIN
            hr4.Employees e ON lb.employee_id = e.employee_id
        JOIN
            hr3.LeaveTypes lt ON lb.leave_type_id = lt.leave_type_id
        {$sql_where}
        ORDER BY
            e.last_name ASC, e.first_name ASC, lt.leave_type_name ASC
    ");
    $stmt_balances->execute($params);
    $leave_balances = $stmt_balances->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $status_message = ['type' => 'danger', 'message' => "Database error: Could not load leave balances. " . $e->getMessage()];
    error_log("Leave Balances Data Fetch Error: " . $e->getMessage());
    $leave_balances = [];
}

// Now include admin.php to render the HTML structure
include_once 'admin.php';
?>
<style>
    .balance {
        color: var(--br-dark);
    }
</style>

<div class="p-2 gap-3">
    <div class="d-flex col">
        <h6 class="text-muted pe-none mb-0">
            <a class="text-decoration-none text-muted" href="">Home</a> >
            <a class="text-decoration-none text-muted" href="leave_balances.php">Leave Balances</a>
        </h6>
    </div>
    <hr>
    <div class="nav col-12 d-flex justify-content-around">
        <?php include('nav/leave management/nav.php'); ?>
    </div>
    <hr>

    <div class="container-fluid shadow-lg col p-4">
        <?php
        // Display status messages here
        if ($status_message) {
            $msg_type = $status_message['type'];
            $msg_content = $status_message['message'];
            echo "<div class='alert alert-$msg_type alert-dismissible fade show' role='alert'>";
            echo $msg_content;
            echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
            echo "</div>";
        }
        ?>

        <div class="col d-flex flex-column p-4 border border-2 rounded-3 mb-4">
            <h3 class="text-center">Filter Leave Balances</h3>
            <hr>
            <form method="GET" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="filterFiscalYear" class="form-label">Fiscal Year</label>
                    <select class="form-select" id="filterFiscalYear" name="fiscal_year">
                        <?php foreach ($all_fiscal_years as $year_option): ?>
                            <option value="<?= htmlspecialchars($year_option) ?>"
                                <?= ($filter_fiscal_year == $year_option) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($year_option) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filterEmployeeName" class="form-label">Employee Name</label>
                    <input type="text" class="form-control" id="filterEmployeeName" name="employee_name"
                                value="<?= htmlspecialchars($filter_employee_name) ?>" placeholder="Search by name">
                </div>
                <div class="col-md-3">
                    <label for="filterLeaveType" class="form-label">Leave Type</label>
                    <select class="form-select" id="filterLeaveType" name="leave_type">
                        <option value="">All Leave Types</option>
                        <?php foreach ($all_leave_types as $type_option): ?>
                            <option value="<?= htmlspecialchars($type_option['leave_type_id']) ?>"
                                <?= ($filter_leave_type == $type_option['leave_type_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($type_option['leave_type_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>

        <div class="col d-flex flex-column p-4 border border-2 rounded-3">
            <h3 class="text-center">Current Leave Balances</h3>
            <hr>
            <?php if (empty($leave_balances)): ?>
                <p class="text-center text-muted">No leave balances found for the selected criteria.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover border text-center">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Leave Type</th>
                                <th>Accrued Days</th>
                                <th>Used Days</th>
                                <th>Carried Over Days</th>
                                <th>Remaining Days</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($leave_balances as $balance): ?>
                                <tr>
                                    <td><?= htmlspecialchars($balance['first_name'] . ' ' . $balance['last_name']) ?></td>
                                    <td><?= htmlspecialchars($balance['leave_type_name']) ?></td>
                                    <td><?= htmlspecialchars($balance['accrued_days']) ?></td>
                                    <td><?= htmlspecialchars($balance['used_days']) ?></td>
                                    <td><?= htmlspecialchars($balance['carried_over_days']) ?></td>
                                    <td><?= htmlspecialchars($balance['remaining_days']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php ob_end_flush(); ?>