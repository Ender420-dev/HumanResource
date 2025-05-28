<?php
session_start();
ob_start();

$title = "Shifting Schedule";
include_once 'admin.php'; // header, sidebar, etc.
include_once '../connections.php'; // $conn_hr1 and $conn_hr3

if (!isset($conn_hr1) || !$conn_hr1 || !isset($conn_hr3) || !$conn_hr3) {
    echo "<div class='alert alert-danger'>Required database connections not available. Please check connections.php</div>";
    // Consider exiting if critical:
    // ob_end_flush(); exit();
}

// --- Date Range ---
$today = new DateTime();

$monday = new DateTime();
$monday->modify('monday this week'); // Always get Monday of this week
$sunday = clone $monday;
$sunday->modify('+6 days');          // Sunday of same week

// Use 'date_from' and 'date_to' from GET parameters for consistency
$start_date = $_GET['date_from'] ?? $monday->format('Y-m-d');
$end_date = $_GET['date_to'] ?? $sunday->format('Y-m-d');


$dt_start_date = new DateTime($start_date);
$dt_end_date = new DateTime($end_date);

$week_day_headers = [];
$current_week_date = clone $dt_start_date;
for ($i = 0; $i < 7; $i++) {
    $week_day_headers[$current_week_date->format('N')] = [
        'day_name' => $current_week_date->format('l'),
        'date_month' => $current_week_date->format('M j'),
        'full_date' => $current_week_date->format('Y-m-d'),
    ];
    $current_week_date->add(new DateInterval('P1D'));
}


// --- Fetch Shift Types ---
$all_shifts = [];
try {
    if (isset($conn_hr3) && $conn_hr3) {
        $stmt = $conn_hr3->query("SELECT shift_id, shift_name FROM hr3.shifts ORDER BY shift_name");
        $all_shifts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    error_log("Error fetching shifts: " . $e->getMessage());
    echo "<div class='alert alert-danger'>Error loading shift types.</div>";
}

// --- Fetch Employees ---
$all_employees = [];
try {
    if (isset($conn_hr1) && $conn_hr1) {
        $stmt = $conn_hr1->query("SELECT EmployeeID, FullName FROM hr1.employeeprofilesetup ORDER BY FullName");
        $all_employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    error_log("Error fetching employees: " . $e->getMessage());
    echo "<div class='alert alert-danger'>Error loading employee list.</div>";
}

// --- Fetch Departments ---
$all_departments = [];
try {
    if (isset($conn_hr3) && $conn_hr3) {
        $stmt = $conn_hr3->query("SELECT department_id, department_name FROM hr3.departments ORDER BY department_name");
        $all_departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    error_log("Error fetching departments: " . $e->getMessage());
    // Optionally: echo "<div class='alert alert-danger'>Error loading departments.</div>";
}

// --- Build SQL for Schedule ---
$where_clauses = ["es.schedule_date BETWEEN :start_date AND :end_date"];
$params = [
    ':start_date' => $start_date,
    ':end_date' => $end_date,
];

if (!empty($_GET['department'])) {
    $where_clauses[] = "es.department_id = :department_id";
    $params[':department_id'] = $_GET['department'];
}
if (!empty($_GET['shift_type'])) {
    $where_clauses[] = "s.shift_name = :shift_name";
    $params[':shift_name'] = $_GET['shift_type'];
}
if (!empty($_GET['employee_name'])) {
    $where_clauses[] = "eps.FullName LIKE :employee_name";
    $params[':employee_name'] = '%' . $_GET['employee_name'] . '%';
}

$sql_where = implode(' AND ', $where_clauses);

$sql = "
    SELECT
        es.schedule_id,
        es.EmployeeID,
        eps.FullName AS employee_full_name,
        eps.Position AS employee_position,
        es.schedule_date,
        s.shift_id,
        s.shift_name,
        s.start_time,
        s.end_time,
        es.department_id,
        es.is_published
    FROM hr3.employee_schedules es
    JOIN hr1.employeeprofilesetup eps ON es.EmployeeID = eps.EmployeeID
    JOIN hr3.shifts s ON es.shift_id = s.shift_id
    WHERE $sql_where
    ORDER BY eps.FullName, es.schedule_date
";

$schedule_data_raw = [];
$display_schedule = [];

if (isset($conn_hr3) && $conn_hr3) {
    try {
        $stmt = $conn_hr3->prepare($sql);
        $stmt->execute($params);
        $schedule_data_raw = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($schedule_data_raw as $row) {
            $EmployeeID = $row['EmployeeID'];
            if (!isset($display_schedule[$EmployeeID])) {
                $display_schedule[$EmployeeID] = [
                    'name' => $row['employee_full_name'],
                    'EmployeeID' => $EmployeeID,
                    'shifts' => array_fill(1, 7, [
                        'text' => 'N/A',
                        'schedule_id' => null,
                        'shift_id' => null,
                        'full_date' => null,
                        'department_id' => null // Initialize department_id here as well
                    ])
                ];
            }

            $day_of_week_num = (int)date('N', strtotime($row['schedule_date']));
            $display_schedule[$EmployeeID]['shifts'][$day_of_week_num] = [
                'text' => $row['shift_name'],
                'schedule_id' => $row['schedule_id'],
                'shift_id' => $row['shift_id'],
                'full_date' => $row['schedule_date'],
                'department_id' => $row['department_id']
            ];
        }
    } catch (PDOException $e) {
        error_log("Error fetching schedule: " . $e->getMessage());
        // Removed alert message on screen as requested
    }
} else {
    echo "<div class='alert alert-warning'>Database connection for HR3 not available. Cannot fetch schedule.</div>";
}
?>

<div class="p-2 gap-3">
    <div class="d-flex col">
        <h6 class=" text-muted pe-none mb-0"><a class="text-decoration-none text-muted" href="#">Home</a> > <a class="text-decoration-none text-muted" href="#">Shift Schedule</a></h6>
    </div>
    <hr>
    <div class="nav col-12 d-flex justify-content-around">
        <?php include('nav/shift schedule/nav.php') ?>
    </div>
    <hr>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?? 'info' ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    endif;
    ?>

    <div class="container-fluid shadow-lg col p-4">
        <div class="col d-flex flex-column p-4">
            <div class="col d-flex flex-column border border-2 border rounded-3 p-4">
                <h3 class="align-items-center text-center">Shift Schedule Management</h3>
                <hr>
                <form method="GET" class="d-flex flex-wrap gap-3 justify-content-center">
                    <div>
                        <h4>Employee Name:</h4>
                        <input class="form-control fs-4" name="employee_name" type="text" value="<?= htmlspecialchars($_GET['employee_name'] ?? '') ?>">
                    </div>
                    <div>
                        <h4>Department/Ward:</h4>
                        <select name="department" class="form-select fs-4">
                            <option value="">All Departments</option>
                            <?php foreach ($all_departments as $dept): ?>
                                <option value="<?= htmlspecialchars($dept['department_id']) ?>"
                                    <?= (($_GET['department'] ?? '') == $dept['department_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($dept['department_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <h4>Shift Type:</h4>
                        <select name="shift_type" class="form-select fs-4">
                            <option value="">All Shifts</option>
                            <?php foreach ($all_shifts as $shift): ?>
                                <option value="<?= htmlspecialchars($shift['shift_name']) ?>" <?= (($_GET['shift_type'] ?? '') === $shift['shift_name']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($shift['shift_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <h4>Date Range:</h4>
                        <input class="form-control-lg" type="date" name="date_from" value="<?= htmlspecialchars($start_date) ?>">
                        to
                        <input class="form-control-lg" type="date" name="date_to" value="<?= htmlspecialchars($end_date) ?>">
                    </div>
                    <div class="align-content-center d-flex align-items-end">
                        <button class="btn btn-primary fs-5 px-3 mx-1" type="submit">Filter</button>
                        <button type="button" class="btn btn-primary fs-5 px-3 mx-1" data-bs-toggle="modal" data-bs-target="#addShiftTypeModal">Add Shift Type</button>
                        <button type="button" class="btn btn-primary fs-5 px-3 mx-1" data-bs-toggle="modal" data-bs-target="#assignStaffModal">Assign Staff</button>

                    </div>
                </form>
            </div>

            <div class="col d-flex flex-column border border-2 border rounded-3 mt-4 p-4 overflow-auto">
                <h4>Schedule Overview</h4>
                <table class="table table-striped table-hover table-bordered align-middle" id="scheduleTable">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <?php foreach ($week_day_headers as $day_num => $day_info): ?>
                                <th><?= htmlspecialchars($day_info['day_name']) ?><br><small><?= htmlspecialchars($day_info['date_month']) ?></small></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($display_schedule) === 0): ?>
                            <tr><td colspan="8" class="text-center">No schedules found for the selected filters and date range.</td></tr>
                        <?php else: ?>
                            <?php foreach ($display_schedule as $EmployeeID => $data): ?>
                                <tr>
                                    <td>
                                        <?= htmlspecialchars($data['name']) ?><br>
                                        <small class="text-muted"><?= htmlspecialchars($data['EmployeeID']) ?></small>
                                    </td>
                                    <?php
                                    for ($day_num = 1; $day_num <= 7; $day_num++) {
                                        $shift_data = $data['shifts'][$day_num];
                                        $dept_id = $shift_data['department_id'] ?? null;
                                        // Find department name by id
                                        $dept_name = 'N/A'; // Default to N/A if no department or unknown
                                        if ($dept_id !== null) {
                                            foreach ($all_departments as $dept) {
                                                if ($dept['department_id'] == $dept_id) {
                                                    $dept_name = $dept['department_name'];
                                                    break;
                                                }
                                            }
                                        }
                                        ?>
                                        <td
                                            data-schedule_id="<?= htmlspecialchars($shift_data['schedule_id'] ?? '') ?>"
                                            data-EmployeeID="<?= htmlspecialchars($EmployeeID) ?>"
                                            data-shift_id="<?= htmlspecialchars($shift_data['shift_id'] ?? '') ?>"
                                            data-date="<?= htmlspecialchars($shift_data['full_date'] ?? '') ?>"
                                            data-department_id="<?= htmlspecialchars($dept_id ?? '') ?>"
                                            class="schedule-cell"
                                            style="cursor:pointer;"
                                            title="Shift: <?= htmlspecialchars($shift_data['text']) ?>&#10;Department: <?= htmlspecialchars($dept_name) ?>"
                                        >
                                            <?= htmlspecialchars($shift_data['text']) ?><br>
                                            <small class="text-muted"><?= htmlspecialchars($dept_name) ?></small>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="addShiftTypeModal" tabindex="-1" aria-labelledby="addShiftTypeModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="insert_shift.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="addShiftTypeModalLabel">Add Shift Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="shift_name" class="form-label">Shift Name:</label>
                    <input type="text" name="shift_name" id="shift_name" class="form-control" required>
                    <label for="start_time" class="form-label mt-3">Start Time:</label>
                    <input type="time" name="start_time" id="start_time" class="form-control" required>
                    <label for="end_time" class="form-label mt-3">End Time:</label>
                    <input type="time" name="end_time" id="end_time" class="form-control" required>
                    <label for="description" class="form-label mt-3">Description:</label>
                    <textarea name="description" id="description" class="form-control"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Shift Type</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="assignStaffModal" tabindex="-1" aria-labelledby="assignStaffModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="assign_staff.php" method="post" id="assignStaffForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignStaffModalLabel">Assign Staff to Shift</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="assignEmployee" class="form-label">Employee:</label>
                    <select name="EmployeeID" id="assignEmployee" class="form-select" required>
                        <option value="">Select Employee</option>
                        <?php foreach ($all_employees as $emp): ?>
                            <option value="<?= htmlspecialchars($emp['EmployeeID']) ?>"><?= htmlspecialchars($emp['FullName']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label for="assignShift" class="form-label mt-3">Shift:</label>
                    <select name="shift_id" id="assignShift" class="form-select" required>
                        <option value="">Select Shift</option>
                        <?php foreach ($all_shifts as $shift): ?>
                            <option value="<?= htmlspecialchars($shift['shift_id']) ?>"><?= htmlspecialchars($shift['shift_name']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label for="assignDepartment" class="form-label mt-3">Department:</label>
                    <select class="form-select" id="assignDepartment" name="department_id">
                        <option value="">No Specific Department</option>
                        <?php foreach ($all_departments as $dept): ?>
                            <option value="<?= htmlspecialchars($dept['department_id']) ?>"><?= htmlspecialchars($dept['department_name']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label for="assignDate" class="form-label mt-3">Date:</label>
                    <input type="date" class="form-control" id="assignDate" name="schedule_date" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Assign Shift</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Optional: When user clicks a schedule cell, prefill the Assign Staff modal with data
document.querySelectorAll('.schedule-cell').forEach(cell => {
    cell.addEventListener('click', () => {
        const assignModal = new bootstrap.Modal(document.getElementById('assignStaffModal'));
        assignModal.show();

        const EmployeeID = cell.dataset.EmployeeID; // Use lowercase data-attribute names
        const shiftId = cell.dataset.shift_id;
        const departmentId = cell.dataset.department_id;
        const date = cell.dataset.date;

        // Only pre-fill if data exists from the clicked cell
        if (EmployeeID) document.getElementById('assignEmployee').value = EmployeeID;
        if (shiftId) document.getElementById('assignShift').value = shiftId;
        if (departmentId) document.getElementById('assignDepartment').value = departmentId;
        if (date) document.getElementById('assignDate').value = date;
    });
});
</script>