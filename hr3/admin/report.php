<?php
session_start();
ob_start();

$title = 'Attendance Tracking';
include_once 'admin.php';
include_once '../connections.php'; // Include your database connection file
include_once 'addnewlog_modal.php'; // Ensure this sets a valid $user_id, if needed

// Initialize variables for report data
$report_data = [];
$employees = [];
$departments = [];
$message = ''; // For displaying messages to the user

// Check if $conn is established
if (!isset($conn) || !$conn instanceof PDO) {
    $message = "Database connection not established. Please check connections.php.";
    error_log("Database connection failed in report.php");
}

// --- Fetch Employees for Dropdown ---
if (isset($conn)) {
    try {
        // Fetch employee_id and their full name (first_name + last_name)
        $stmt_employees = $conn->query("SELECT employee_id, CONCAT(first_name, ' ', last_name) AS full_name FROM hr3.Employees ORDER BY full_name ASC");
        $employees = $stmt_employees->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $message = "Error fetching employees: " . $e->getMessage();
        error_log("Error fetching employees for report: " . $e->getMessage());
    }
}

// --- Fetch Departments for Dropdown (from a dedicated 'Departments' table) ---
if (isset($conn)) {
    try {
        // Assuming a 'hr3.Departments' table with 'department_id' and 'department_name'
        $stmt_departments = $conn->query("SELECT DISTINCT department_name FROM hr3.Departments WHERE department_name IS NOT NULL AND department_name != '' ORDER BY department_name ASC");
        $departments = $stmt_departments->fetchAll(PDO::FETCH_COLUMN); // Fetch just the column values
    } catch (PDOException $e) {
        $message = "Error fetching departments: " . $e->getMessage();
        error_log("Error fetching departments for report: " . $e->getMessage());
    }
}


// --- Handle Report Generation (Form Submission) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_report'])) {
    $report_type = $_POST['report_type'] ?? 'summary';
    $start_date = $_POST['date_range'] ?? null;
    $end_date = $_POST['date_range_end'] ?? null;
    $selected_employee_id = $_POST['employees'] ?? null;
    $selected_department_name = $_POST['department'] ?? null; // Variable name changed to reflect department name

    // Basic validation
    if (!$start_date || !$end_date) {
        $message = "Please select a valid date range.";
    } elseif ($start_date > $end_date) {
        $message = "Start date cannot be after end date.";
    } else {
        // Build the SQL query for attendance logs from the 'Attendance' table
        $sql_report = "
            SELECT
                e.employee_id,
                CONCAT(e.first_name, ' ', e.last_name) AS employee_name,
                DATE(a.record_time) AS attendance_date,
                MIN(CASE WHEN a.record_type = 'Clock In' THEN TIME(a.record_time) ELSE NULL END) AS first_clock_in,
                MAX(CASE WHEN a.record_type = 'Clock Out' THEN TIME(a.record_time) ELSE NULL END) AS last_clock_out
            FROM
                hr3.Attendance a
            JOIN
                hr3.Employees e ON a.employee_id = e.employee_id
        ";
        
        $params = [$start_date, $end_date];

        // Conditional JOIN for Departments table if a specific department is selected
        if (!empty($selected_department_name)) {
            $sql_report .= " JOIN hr3.Departments d ON e.department_id = d.department_id ";
        }

        $sql_report .= " WHERE DATE(a.record_time) BETWEEN ? AND ?";

        if (!empty($selected_employee_id)) {
            $sql_report .= " AND e.employee_id = ?";
            $params[] = $selected_employee_id;
        }

        // Filter by department name using the joined Departments table
        if (!empty($selected_department_name)) {
            $sql_report .= " AND d.department_name = ?";
            $params[] = $selected_department_name;
        }

        $sql_report .= " GROUP BY e.employee_id, employee_name, attendance_date ORDER BY employee_name ASC, attendance_date ASC";

        try {
            $stmt_report = $conn->prepare($sql_report);
            $stmt_report->execute($params);
            $raw_attendance_data = $stmt_report->fetchAll(PDO::FETCH_ASSOC);

            // Process raw attendance data to calculate total hours, overtime, absences
            $report_data = processAttendanceData($raw_attendance_data, $start_date, $end_date);

            if (empty($report_data)) {
                $message = "No attendance data found for the selected criteria.";
            }

        } catch (PDOException $e) {
            $message = "Error generating report: " . $e->getMessage();
            error_log("Error generating attendance report: " . $e->getMessage());
        }
    }
}

// Function to process attendance data and calculate summary metrics
// This function needs to consider all days in the range for each employee, even if no logs exist.
function processAttendanceData($raw_data, $start_date_str, $end_date_str) {
    $processed_data = [];
    $employee_daily_records = []; // To aggregate daily records per employee_id

    // Reorganize raw data by employee_id and attendance_date
    foreach ($raw_data as $row) {
        $employee_id = $row['employee_id'];
        $attendance_date = $row['attendance_date'];
        if (!isset($employee_daily_records[$employee_id])) {
            $employee_daily_records[$employee_id] = [
                'name' => $row['employee_name'],
                'dates' => []
            ];
        }
        $employee_daily_records[$employee_id]['dates'][$attendance_date] = [
            'time_in' => $row['first_clock_in'],
            'time_out' => $row['last_clock_out']
        ];
    }

    $start_date_dt = new DateTime($start_date_str);
    $end_date_dt = new DateTime($end_date_str);
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($start_date_dt, $interval, $end_date_dt->modify('+1 day')); // Include end date

    // Assuming a standard 8-hour workday for calculations (480 minutes)
    $standard_work_minutes = 8 * 60;
    // Assuming standard time-in for 'Late' calculation (e.g., 09:00 AM)
    $standard_time_in_hour = 9;
    $standard_time_in_minute = 0;
    $late_grace_period_minutes = 5; // e.g., 5 minutes grace

    // Iterate through each employee and each date in the specified range
    foreach ($employee_daily_records as $employee_id => $employee_info) {
        $employee_name = $employee_info['name'];
        foreach ($period as $date) {
            $date_str = $date->format('Y-m-d');
            $status = 'Absent';
            $time_in = '';
            $time_out = '';
            $total_hours = '0.00';
            $overtime = '0.00';
            $absences = '1';

            if (isset($employee_info['dates'][$date_str])) {
                $record = $employee_info['dates'][$date_str];
                $time_in = $record['time_in'];
                $time_out = $record['time_out'];

                if ($time_in && $time_out) {
                    try {
                        $time_in_dt = new DateTime($date_str . ' ' . $time_in);
                        $time_out_dt = new DateTime($date_str . ' ' . $time_out);

                        // If time_out is earlier than time_in, assume it's next day (e.g., overnight shift)
                        if ($time_out_dt < $time_in_dt) {
                            $time_out_dt->modify('+1 day');
                        }

                        $interval_diff = $time_in_dt->diff($time_out_dt);
                        $total_minutes_worked = ($interval_diff->days * 24 * 60) + ($interval_diff->h * 60) + $interval_diff->i;

                        $total_hours = round($total_minutes_worked / 60, 2);
                        $overtime_minutes = max(0, $total_minutes_worked - $standard_work_minutes);
                        $overtime = round($overtime_minutes / 60, 2);
                        $absences = '0'; // Not absent if clocked in/out

                        $status = 'Present';
                        $standard_time_in_dt = new DateTime($date_str . " " . sprintf('%02d:%02d:00', $standard_time_in_hour, $standard_time_in_minute));
                        if ($time_in_dt > $standard_time_in_dt->modify("+$late_grace_period_minutes minutes")) {
                            $status = 'Late';
                        }

                    } catch (Exception $e) {
                        // Log parsing errors if time formats are invalid
                        error_log("Date/Time parsing error for employee $employee_id on $date_str: " . $e->getMessage());
                        $status = 'Data Error'; // Indicate a problem for this entry
                    }
                } elseif ($time_in) {
                    $status = 'Time In Only'; // Clocked in but not out
                    $absences = '0';
                } else {
                    $status = 'Absent';
                    $absences = '1';
                }
            } else {
                // Check if it's a weekend or holiday if you have such a system
                // For this example, any day without a log is an absence
            }

            $processed_data[] = [
                'name' => $employee_name,
                'date' => $date_str,
                'status' => $status,
                'time_in' => $time_in,
                'time_out' => $time_out,
                'total_hours' => $total_hours . ' hours', // Append 'hours' for display
                'overtime' => $overtime . ' hours',     // Append 'hours' for display
                'absences' => $absences,
            ];
        }
    }
    return $processed_data;
}


// --- Calculate Summary for Display ---
$total_present = 0;
$total_absent = 0;
// Note: This summary counts occurrences in the $report_data array.
// For a true "summary" report, you might want to group by employee and show overall totals/averages.
if (!empty($report_data)) {
    foreach ($report_data as $entry) {
        if ($entry['status'] === 'Present' || $entry['status'] === 'Late' || $entry['status'] === 'Time In Only' || $entry['status'] === 'Data Error') {
            $total_present++;
        } elseif ($entry['status'] === 'Absent') {
            $total_absent++;
        }
    }
}
?>

<div class="p-2 gap-3">
    <div class="d-flex col">
        <h6 class="text-muted pe-none mb-0"><a class="text-decoration-none text-muted" href="">Home</a> > <a class="text-decoration-none text-muted" href="">Attendance Tracking</a> > <a class="text-decoration-none text-muted" href="">Report</a></h6>
    </div>
    <hr>
    <div class="nav col-12 d-flex justify-content-around">
        <h3>
            <a class="text-decoration-none fs-3" href="attendance tracking.php">Attendance Tracking</a>
        </h3>
        <h3>
            <a class="text-decoration-none fs-3" href="employee logs.php">Employee Logs</a>
        </h3>
        <h3>
            <a class="text-decoration-none fs-3" href="pending approval.php">Pending Approvals</a>
        </h3>
        <h3>
            <a class="text-decoration-none fs-3" href="rules and config.php">Rules and Config</a>
        </h3>
    </div>
    <hr>
    <div class="container-fluid shadow-lg col p-5">
        <form method="POST" action="">
            <div class="col d-flex flex-column border border-2 border rounded-3 p-4">
                <h3 class="align-items-center text-center">Generate Attendance Reports</h3>
                <?php if (!empty($message)): ?>
                    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>
                <hr>
                <div class="col d-flex flex-column border border-2 border rounded-3 p-4 justify-content-center">
                    <h4 class="d-flex align-items-center">Report Type:
                        <span class="ms-2">
                            <select class="form-select" name="report_type" id="report_type">
                                <option value="summary" <?= (isset($_POST['report_type']) && $_POST['report_type'] == 'summary') ? 'selected' : '' ?>>Summary</option>
                                <option value="detailed" <?= (isset($_POST['report_type']) && $_POST['report_type'] == 'detailed') ? 'selected' : '' ?>>Detailed</option>
                            </select>
                        </span>
                    </h4>
                    <h4 class="d-flex align-items-center">Date Range:
                        <span class="d-flex ms-2">
                            <input class="form-control me-2" type="date" name="date_range" id="date_range" value="<?= htmlspecialchars($_POST['date_range'] ?? '') ?>"> to
                            <input class="form-control" type="date" name="date_range_end" id="date_range_end" value="<?= htmlspecialchars($_POST['date_range_end'] ?? '') ?>">
                        </span>
                    </h4>
                    <h4 class="d-flex align-items-center">Employee(s):
                        <span class="ms-2">
                            <select class="form-select" name="employees" id="employees">
                                <option value="">All Employees</option>
                                <?php foreach ($employees as $emp): ?>
                                    <option value="<?= htmlspecialchars($emp['employee_id']) ?>" <?= (isset($_POST['employees']) && $_POST['employees'] == $emp['employee_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($emp['full_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </span>
                    </h4>
                    <h4 class="d-flex align-items-center">Department:
                        <span class="ms-2">
                            <select class="form-select" name="department" id="department">
                                <option value="">All Departments</option>
                                <?php foreach ($departments as $dept_name): ?>
                                    <option value="<?= htmlspecialchars($dept_name) ?>" <?= (isset($_POST['department']) && $_POST['department'] == $dept_name) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($dept_name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </span>
                    </h4>
                    <div class="mt-3">
                        <button class="btn btn-primary me-2" type="submit" name="generate_report">Generate Report</button>
                        <button class="btn btn-secondary me-2" type="button" onclick="downloadCSV()">Download CSV</button>
                        <button class="btn btn-danger" type="button" onclick="window.print()">Print</button>
                    </div>
                </div>
                <hr>
                <div>
                    <h3 class="align-items-center text-center">Report Preview</h3>
                    <hr>
                    <div class="col d-flex flex-column border border-2 border rounded-3 p-4 justify-content-center">
                        <div style="overflow-x: auto; max-height: 350px;">
                            <table class="table table-striped table-hover border text-center" id="reportTable">
                                <thead class="sticky-top bg-light">
                                    <tr>
                                        <th class="col">Employee Name</th>
                                        <th class="col date-header" style="display: none;">Date</th>
                                        <th class="col">Status</th>
                                        <th class="col">Time In</th>
                                        <th class="col">Time Out</th>
                                        <th class="col">Total Hours</th>
                                        <th class="col">Overtime</th>
                                        <th class="col">Absences</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($report_data)): ?>
                                        <?php foreach ($report_data as $entry): ?>
                                            <tr class="report-row">
                                                <td><?= htmlspecialchars($entry['name']) ?></td>
                                                <td class="date-cell" style="display: none;"><?= htmlspecialchars($entry['date'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($entry['status']) ?></td>
                                                <td><?= htmlspecialchars($entry['time_in']) ?></td>
                                                <td><?= htmlspecialchars($entry['time_out']) ?></td>
                                                <td><?= htmlspecialchars($entry['total_hours']) ?></td>
                                                <td><?= htmlspecialchars($entry['overtime']) ?></td>
                                                <td><?= htmlspecialchars($entry['absences']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8">No report data to display. Please generate a report.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex mt-3">
                            <h4>Summary: </h4>
                            <ul class="list-unstyled d-flex gap-3 fs-5 fw-bold ms-3">
                                <li class="border px-3">Present: <?= $total_present ?></li>
                                <li class="border px-3">Absent: <?= $total_absent ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reportTypeSelect = document.getElementById('report_type');
    const dateHeader = document.querySelector('.date-header');
    const dateCells = document.querySelectorAll('.date-cell');

    function toggleDetailedView() {
        const isDetailed = reportTypeSelect.value === 'detailed';
        if (isDetailed) {
            dateHeader.style.display = 'table-cell';
            dateCells.forEach(cell => cell.style.display = 'table-cell');
        } else {
            dateHeader.style.display = 'none';
            dateCells.forEach(cell => cell.style.display = 'none');
        }
    }

    // Initial call to set view based on current selection
    toggleDetailedView();

    // Event listener for report type change
    reportTypeSelect.addEventListener('change', toggleDetailedView);

    // Function to download CSV
    window.downloadCSV = function() {
        const table = document.getElementById('reportTable');
        let csv = [];
        const rows = table.querySelectorAll('tr');

        rows.forEach(function(row, rowIndex) {
            let rowData = [];
            const cols = row.querySelectorAll('th, td');
            cols.forEach(function(col) {
                // Only include columns that are currently visible
                if (col.style.display !== 'none') {
                    rowData.push('"' + col.innerText.replace(/"/g, '""') + '"'); // Handle commas and quotes
                }
            });
            csv.push(rowData.join(','));
        });

        const csvString = csv.join('\n');
        const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'attendance_report.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    };

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
ob_end_flush();
?>