<?php
session_start();
ob_start(); // Start output buffering
$title = 'Reports';
include_once 'admin.php'; // Assuming admin.php handles common header/sidebar
include_once '../connections.php'; // Include your PDO database connection file, which defines $conn_hr3

// --- IMPORTANT: Set the connection for THIS script to hr3 ---
$conn = $conn_hr3;

// Initialize variables for dropdowns and report data
$employees = [];
$departments = [];
$projects = [];
$reportData = [];

// --- Initialize $reportType with a default value and other POST variables ---
// This ensures variables exist when the page first loads (GET request)
$reportType = $_POST['report_type'] ?? 'overtime'; // Default to 'overtime' if not set
$startDate = $_POST['start_date'] ?? null;
$endDate = $_POST['end_date'] ?? null;
$filterEmployeeId = $_POST['filter_employee_id'] ?? null;
$filterDepartmentId = $_POST['filter_department_id'] ?? null;
$filterProjectId = $_POST['filter_project_id'] ?? null;

$reportSummary = [
    'total_overtime_hours' => 0,
    'average_overtime_per_staff' => 0,
    'total_project_hours' => 0,
    'num_unique_projects' => 0,
    'report_type_display' => ucfirst(str_replace('_', ' ', $reportType)) . ' Report', // Default display based on initial $reportType
    'date_range_display' => ($startDate && $endDate) ?
        date('M d, Y', strtotime($startDate)) . ' to ' . date('M d, Y', strtotime($endDate)) : 'All Dates'
];


// --- Fetch Filter Data from Database (using $conn_hr3) ---

// Fetch Employees from hr3.employees
$sql_employees = "SELECT employee_id, CONCAT(first_name, ' ', last_name) AS full_name FROM employees ORDER BY full_name ASC";
try {
    $stmt_employees = $conn->query($sql_employees);
    $employees = $stmt_employees->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['message'] = "Error fetching employees from hr3: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

// Fetch Departments from hr3.departments
$sql_departments = "SELECT department_id, department_name FROM departments ORDER BY department_name ASC";
try {
    $stmt_departments = $conn->query($sql_departments);
    $departments = $stmt_departments->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['message'] = "Error fetching departments from hr3: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

// Fetch Projects from hr3.projects
$sql_projects = "SELECT project_id, project_name FROM projects ORDER BY project_name ASC";
try {
    $stmt_projects = $conn->query($sql_projects);
    $projects = $stmt_projects->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['message'] = "Error fetching projects from hr3: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}


// --- Handle Report Generation (Only runs on POST request from the form) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['generate_report'])) {
    // Re-assign variables from POST data
    $reportType = $_POST['report_type'] ?? 'overtime';
    $startDate = $_POST['start_date'] ?? null;
    $endDate = $_POST['end_date'] ?? null;
    $filterEmployeeId = $_POST['filter_employee_id'] ?? null;
    $filterDepartmentId = $_POST['filter_department_id'] ?? null;
    $filterProjectId = $_POST['filter_project_id'] ?? null;

    // Update display for report type and date range based on actual POST data
    $reportSummary['report_type_display'] = ucfirst(str_replace('_', ' ', $reportType)) . ' Report';
    $reportSummary['date_range_display'] = ($startDate && $endDate) ?
        date('M d, Y', strtotime($startDate)) . ' to ' . date('M d, Y', strtotime($endDate)) : 'All Dates';


    // --- Implement Report Logic Based on $reportType (using $conn_hr3) ---
    switch ($reportType) {
        case 'overtime':
            // Using dailyattendancesummary for overtime hours and attendance_date
            $sql_report = "
                SELECT
                    das.summary_id,
                    e.employee_id,
                    CONCAT(e.first_name, ' ', e.last_name) AS employee_name,
                    d.department_name,
                    das.attendance_date,
                    das.overtime_hours,
                    das.approval_notes AS notes
                FROM
                    dailyattendancesummary das
                JOIN
                    employees e ON das.employee_id = e.employee_id
                LEFT JOIN
                    departments d ON e.department_id = d.department_id
                WHERE
                    das.overtime_hours > 0
            ";
            $params = [];

            if ($startDate && $endDate) {
                $sql_report .= " AND das.attendance_date BETWEEN :start_date AND :end_date";
                $params[':start_date'] = $startDate;
                $params[':end_date'] = $endDate;
            }
            if (!empty($filterEmployeeId)) {
                $sql_report .= " AND e.employee_id = :employee_id";
                $params[':employee_id'] = $filterEmployeeId;
            }
            if (!empty($filterDepartmentId)) {
                $sql_report .= " AND d.department_id = :department_id";
                $params[':department_id'] = $filterDepartmentId;
            }

            $sql_report .= " ORDER BY das.attendance_date ASC, employee_name ASC";

            try {
                $stmt_report = $conn->prepare($sql_report); // Using $conn (which is $conn_hr3)
                $stmt_report->execute($params);
                $reportData = $stmt_report->fetchAll(PDO::FETCH_ASSOC);

                // Calculate summary for Overtime Report
                $totalOvertime = 0;
                $uniqueEmployees = [];
                foreach ($reportData as $row) {
                    $totalOvertime += $row['overtime_hours'];
                    $uniqueEmployees[$row['employee_id']] = true;
                }
                $reportSummary['total_overtime_hours'] = $totalOvertime;
                $numUniqueEmployees = count($uniqueEmployees);
                $reportSummary['average_overtime_per_staff'] = $numUniqueEmployees > 0 ? $totalOvertime / $numUniqueEmployees : 0;

            } catch (PDOException $e) {
                $_SESSION['message'] = "Error generating overtime report from hr3: " . $e->getMessage();
                $_SESSION['message_type'] = "danger";
                $reportData = []; // Clear data on error
            }
            break;

        case 'hours_by_project':
            $reportSummary['report_type_display'] = 'Hours by Project Report';
            // Using timesheetentries for project hours and entry_date
            $sql_report = "
                SELECT
                    p.project_name,
                    t.task_name,
                    CONCAT(e.first_name, ' ', e.last_name) AS employee_name,
                    te.entry_date,
                    SUM(te.hours_logged) AS total_hours_on_task
                FROM
                    timesheetentries te
                JOIN
                    tasks t ON te.task_id = t.task_id
                JOIN
                    projects p ON t.project_id = p.project_id
                JOIN
                    timesheets ts ON te.timesheet_id = ts.timesheet_id
                JOIN
                    employees e ON ts.employee_id = e.employee_id
                WHERE 1=1
            ";
            $params = [];

            if ($startDate && $endDate) {
                $sql_report .= " AND te.entry_date BETWEEN :start_date AND :end_date";
                $params[':start_date'] = $startDate;
                $params[':end_date'] = $endDate;
            }
            if (!empty($filterEmployeeId)) {
                $sql_report .= " AND e.employee_id = :employee_id";
                $params[':employee_id'] = $filterEmployeeId;
            }
            if (!empty($filterDepartmentId)) {
                // Filter by employee's department who logged hours on the project
                $sql_report .= " AND e.department_id = :department_id";
                $params[':department_id'] = $filterDepartmentId;
            }
            if (!empty($filterProjectId)) {
                $sql_report .= " AND p.project_id = :project_id";
                $params[':project_id'] = $filterProjectId;
            }

            $sql_report .= "
                GROUP BY
                    p.project_name, t.task_name, employee_name, te.entry_date
                ORDER BY
                    p.project_name, te.entry_date, employee_name";

            try {
                $stmt_report = $conn->prepare($sql_report); // Using $conn (which is $conn_hr3)
                $stmt_report->execute($params);
                $reportData = $stmt_report->fetchAll(PDO::FETCH_ASSOC);

                // Calculate summary for Hours by Project Report
                $totalProjectHours = 0;
                $uniqueProjects = [];
                foreach ($reportData as $row) {
                    $totalProjectHours += $row['total_hours_on_task'];
                    $uniqueProjects[$row['project_name']] = true;
                }
                $reportSummary['total_project_hours'] = $totalProjectHours;
                $reportSummary['num_unique_projects'] = count($uniqueProjects);

            } catch (PDOException $e) {
                $_SESSION['message'] = "Error generating hours by project report from hr3: " . $e->getMessage();
                $_SESSION['message_type'] = "danger";
                $reportData = [];
            }
            break;

        default:
            $_SESSION['message'] = "Invalid report type selected.";
            $_SESSION['message_type'] = "danger";
            break;
    }
}

// --- Fetch Saved Reports (from hr3.scheduled_reports table) ---
$savedReports = [];
$sql_saved_reports = "SELECT report_id, report_name, report_type, frequency, last_run_date FROM scheduled_reports ORDER BY report_name ASC";
try {
    $stmt_saved = $conn->query($sql_saved_reports); // Using $conn (which is $conn_hr3)
    $savedReports = $stmt_saved->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // If table doesn't exist yet, handle gracefully (e.g., just show empty list)
    // In a production environment, you might log this or show a specific message.
    $_SESSION['message'] = "Warning: 'scheduled_reports' table not found or error fetching saved reports from hr3: " . $e->getMessage();
    $_SESSION['message_type'] = "warning";
    $savedReports = []; // Ensure it's an empty array if there's an error
}

$conn = null; // Close PDO connection
?>

<div class="p-2 gap-3">
    <div class="d-flex col">
        <h6 class=" text-muted pe-none mb-0"><a class="text-decoration-none text-muted" href="#">Home</a> > <a class="text-decoration-none text-muted" href="#">Time Sheets</a> > <a class="text-decoration-none text-muted" href="#">Reports</a></h6>
    </div>
    <hr>
    <div class="nav col-12 d-flex justify-content-around">
        <h3><a class="text-decoration-none" href="timesheets for approval.php">Timesheets for Approval</a></h3>
        <h3><a href="timesheets.php" class="text-decoration-none">Timesheets</a></h3>
        <h3><a href="all timesheets.php" class="text-decoration-none">All Timesheets</a></h3>
        <h3><a class="text-decoration-none" href="project & tasks.php">Project & Tasks</a></h3>
    </div>
    <hr>

    <?php
    // Display session messages (e.g., success/error after form submission)
    if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
        unset($_SESSION['message']); // Clear the message after displaying
        unset($_SESSION['message_type']);
    endif;
    ?>

    <div class="container-fluid shadow-lg col p-4">
        <div class="col d-flex flex-column p-4">
            <div class="col d-flex flex-column border border-2 border rounded-3 p-4">
                <h3 class="align-items-center text-center">Generate New Time Sheet Report</h3>
                <hr>
                <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                    <input type="hidden" name="generate_report" value="1">
                    <div class="row mb-3">
                        <div class="col-3 d-flex flex-column gap-1">
                            <h4>Report Type:</h4>
                            <h4>Date Range:</h4>
                        </div>
                        <div class="col-9">
                            <h4 class="mb-3">
                                <select class="form-select" name="report_type" id="reportTypeSelect">
                                    <option value="overtime" <?= ($reportType == 'overtime') ? 'selected' : '' ?>>Overtime Hours Report</option>
                                    <option value="hours_by_project" <?= ($reportType == 'hours_by_project') ? 'selected' : '' ?>>Hours by Project Report</option>
                                </select>
                            </h4>
                            <h4 class="d-flex gap-2">
                                <input class="form-control" type="date" name="start_date" id="startDate" value="<?= htmlspecialchars($startDate ?? '') ?>">
                                to
                                <input class="form-control" type="date" name="end_date" id="endDate" value="<?= htmlspecialchars($endDate ?? '') ?>">
                            </h4>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <h4>Filter By:</h4>
                    </div>
                    <div class="row mb-4">
                        <div class="col-3 d-flex flex-column gap-3">
                            <h4>Employee:</h4>
                            <h4>Department/Ward:</h4>
                            <h4>Project/Activity:</h4>
                        </div>
                        <div class="col-9 d-flex flex-column gap-2">
                            <h4>
                                <select class="form-select" name="filter_employee_id" id="filterEmployee">
                                    <option value="">All Employees</option>
                                    <?php foreach ($employees as $emp): ?>
                                        <option value="<?= htmlspecialchars($emp['employee_id']) ?>"
                                            <?= (!empty($filterEmployeeId) && $filterEmployeeId == $emp['employee_id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($emp['full_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </h4>
                            <h4>
                                <select name="filter_department_id" id="filterDepartment" class="form-select">
                                    <option value="">All Departments</option>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?= htmlspecialchars($dept['department_id']) ?>"
                                            <?= (!empty($filterDepartmentId) && $filterDepartmentId == $dept['department_id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($dept['department_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </h4>
                            <h4>
                                <select name="filter_project_id" id="filterProject" class="form-select">
                                    <option value="">All Projects</option>
                                    <?php foreach ($projects as $proj): ?>
                                        <option value="<?= htmlspecialchars($proj['project_id']) ?>"
                                            <?= (!empty($filterProjectId) && $filterProjectId == $proj['project_id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($proj['project_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </h4>
                        </div>
                    </div>
                    <div class="mt-4 d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <h4 class="mb-0 me-3">Output Format:</h4>
                            <div class="d-flex gap-3">
                                <button type="button" class="btn btn-primary px-3" id="exportPdfBtn">PDF</button>
                                <button type="button" class="btn btn-primary px-3" id="exportCsvBtn">CSV</button>
                            </div>
                        </div><hr>
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary">Generate Report</button>
                            <button type="button" class="btn btn-primary" id="downloadReportBtn" disabled>Download Report</button>
                            <button type="button" class="btn btn-primary" id="printReportBtn">Print Report</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col d-flex flex-column border border-2 border rounded-3 p-4 mt-4">
                <h3 class="align-items-center text-center">Report Preview: <span id="reportPreviewTitle"><?= htmlspecialchars($reportSummary['report_type_display']) ?></span></h3>
                <h6 class="text-center text-muted">Date Range: <span id="reportPreviewDateRange"><?= htmlspecialchars($reportSummary['date_range_display']) ?></span></h6>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped table-hover border text-center" id="reportPreviewTable">
                        <thead>
                            <?php if ($reportType == 'overtime'): ?>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Staff Name</th>
                                    <th>Department</th>
                                    <th>Attendance Date</th>
                                    <th>Overtime Hours</th>
                                    <th>Notes</th>
                                </tr>
                            <?php elseif ($reportType == 'hours_by_project'): ?>
                                <tr>
                                    <th>Project Name</th>
                                    <th>Task Name</th>
                                    <th>Employee Name</th>
                                    <th>Entry Date</th>
                                    <th>Total Hours</th>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <th>No report generated or unknown report type.</th>
                                </tr>
                            <?php endif; ?>
                        </thead>
                        <tbody>
                            <?php if (!empty($reportData)): ?>
                                <?php foreach ($reportData as $row): ?>
                                    <tr>
                                        <?php if ($reportType == 'overtime'): ?>
                                            <td><?= htmlspecialchars($row['employee_id']) ?></td>
                                            <td><?= htmlspecialchars($row['employee_name']) ?></td>
                                            <td><?= htmlspecialchars($row['department_name'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($row['attendance_date']) ?></td>
                                            <td><?= htmlspecialchars($row['overtime_hours']) ?></td>
                                            <td><?= htmlspecialchars($row['notes'] ?? 'N/A') ?></td>
                                        <?php elseif ($reportType == 'hours_by_project'): ?>
                                            <td><?= htmlspecialchars($row['project_name']) ?></td>
                                            <td><?= htmlspecialchars($row['task_name'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($row['employee_name'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($row['entry_date']) ?></td>
                                            <td><?= htmlspecialchars($row['total_hours_on_task']) ?></td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">No data available for the selected criteria. Generate a report above.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div>
                    <?php if ($reportType == 'overtime'): ?>
                        <h4>Summary: <span>Total Overtime Hours: <?= htmlspecialchars(number_format($reportSummary['total_overtime_hours'], 2)) ?></span></h4>
                        <h4>Average Overtime per Staff: <span><?= htmlspecialchars(number_format($reportSummary['average_overtime_per_staff'], 2)) ?></span></h4>
                    <?php elseif ($reportType == 'hours_by_project'): ?>
                        <h4>Summary: <span>Total Hours Logged on Projects: <?= htmlspecialchars(number_format($reportSummary['total_project_hours'], 2)) ?></span></h4>
                        <h4>Number of Projects Included: <span><?= htmlspecialchars($reportSummary['num_unique_projects']) ?></span></h4>
                    <?php endif; ?>
                </div>
            </div>
            <br>
            <div class="col d-flex flex-column border border-2 border rounded-3 p-4 mt-4">
                <h3 class="align-items-center text-center">Saved & Scheduled Reports</h3>
                <hr>
                <div>
                    <table class="table table-striped table-hover border text-center">
                        <thead>
                            <tr>
                                <th>Report Name</th>
                                <th>Type</th>
                                <th>Frequency</th>
                                <th>Last Run</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($savedReports)): ?>
                                <?php foreach ($savedReports as $saved): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($saved['report_name']) ?></td>
                                        <td><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $saved['report_type']))) ?></td>
                                        <td><?= htmlspecialchars($saved['frequency']) ?></td>
                                        <td><?= htmlspecialchars($saved['last_run_date'] ?? 'N/A') ?></td>
                                        <td class="d-flex justify-content-center gap-2">
                                            <button class="btn btn-success btn-sm run-report-btn" data-report-id="<?= htmlspecialchars($saved['report_id']) ?>">Run Now</button>
                                            <button class="btn btn-info btn-sm edit-report-btn" data-report-id="<?= htmlspecialchars($saved['report_id']) ?>" data-bs-toggle="modal" data-bs-target="#scheduleReportModal">Edit</button>
                                            <button class="btn btn-danger btn-sm delete-report-btn" data-report-id="<?= htmlspecialchars($saved['report_id']) ?>">Delete</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">No saved or scheduled reports found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#scheduleReportModal" id="addNewReportBtn">Schedule New Report</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="scheduleReportModal" tabindex="-1" aria-labelledby="scheduleReportModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleReportModalLabel">Schedule New Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="scheduleReportForm">
                <input type="hidden" id="modalReportId" name="report_id" value="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="scheduleReportName" class="form-label">Report Name</label>
                        <input type="text" class="form-control" id="scheduleReportName" name="report_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="scheduleReportType" class="form-label">Report Type</label>
                        <select class="form-select" id="scheduleReportType" name="report_type">
                            <option value="overtime">Overtime Hours Report</option>
                            <option value="hours_by_project">Hours by Project Report</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="scheduleFrequency" class="form-label">Frequency</label>
                        <select class="form-select" id="scheduleFrequency" name="frequency">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="bi-weekly">Bi-Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="scheduleRecipient" class="form-label">Recipient Email (Optional)</label>
                        <input type="email" class="form-control" id="scheduleRecipient" name="recipient_email" placeholder="e.g., reports@example.com">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Schedule</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.16/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Export to PDF ---
        document.getElementById('exportPdfBtn').addEventListener('click', function() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            const reportTitle = document.getElementById('reportPreviewTitle').textContent;
            const dateRange = document.getElementById('reportPreviewDateRange').textContent;
            const table = document.getElementById('reportPreviewTable');

            const dataRows = Array.from(table.querySelectorAll('tbody tr'));
            if (dataRows.length === 0 || (dataRows.length === 1 && dataRows[0].cells[0].textContent.includes('No data available'))) {
                alert("No report data to export to PDF.");
                return;
            }

            // Add title and date range to the PDF
            doc.text(reportTitle, 10, 10);
            doc.text(dateRange, 10, 20);

            // Get headers and data from the table
            const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent.trim());
            const data = Array.from(table.querySelectorAll('tbody tr')).map(row =>
                Array.from(row.querySelectorAll('td')).map(td => td.textContent.trim())
            );

            doc.autoTable({
                startY: 30, // Start table below the title and date range
                head: [headers],
                body: data,
                theme: 'striped',
                headStyles: { fillColor: [20, 100, 200] },
                margin: { top: 25 },
                didDrawPage: function(data) {
                    // Footer
                    let str = "Page " + doc.internal.getNumberOfPages();
                    doc.setFontSize(10);
                    doc.text(str, data.settings.margin.left, doc.internal.pageSize.height - 10);
                }
            });

            doc.save('timesheet_report.pdf');
        });

        // --- Export to CSV ---
        document.getElementById('exportCsvBtn').addEventListener('click', function() {
            const table = document.getElementById('reportPreviewTable');
            let csv = [];

            const dataRows = Array.from(table.querySelectorAll('tbody tr'));
            if (dataRows.length === 0 || (dataRows.length === 1 && dataRows[0].cells[0].textContent.includes('No data available'))) {
                alert("No report data to export to CSV.");
                return;
            }

            // Get headers
            let headers = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent.trim());
            csv.push(headers.join(','));

            dataRows.forEach(row => {
                let rowData = Array.from(row.querySelectorAll('td')).map(td => {
                    let text = td.textContent.trim();
                    // Handle commas and quotes in data by enclosing in double quotes and escaping existing quotes
                    if (text.includes(',') || text.includes('"') || text.includes('\n')) {
                        text = '"' + text.replace(/"/g, '""') + '"';
                    }
                    return text;
                });
                csv.push(rowData.join(','));
            });

            let csvContent = "data:text/csv;charset=utf-8," + csv.join('\n');
            let encodedUri = encodeURI(csvContent);
            let link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "timesheet_report.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });

        // --- Print Report ---
        document.getElementById('printReportBtn').addEventListener('click', function() {
            const printContents = document.getElementById('reportPreviewTable').outerHTML;
            const originalContents = document.body.innerHTML;
            const reportTitle = document.getElementById('reportPreviewTitle').textContent;
            const dateRange = document.getElementById('reportPreviewDateRange').textContent;
            const summaryContent = document.querySelector('.col.d-flex.flex-column.border.border-2.border.rounded-3.p-4.mt-4 > div:last-of-type').outerHTML;

            const printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Time Sheet Report</title>');
            // Include Bootstrap CSS for styling
            printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">');
            printWindow.document.write('<style>');
            printWindow.document.write('body { font-family: Arial, sans-serif; margin: 20px; }');
            printWindow.document.write('h3, h6 { text-align: center; }');
            printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 20px; }');
            printWindow.document.write('th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }');
            printWindow.document.write('th { background-color: #f2f2f2; }');
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<h3>' + reportTitle + '</h3>');
            printWindow.document.write('<h6>' + dateRange + '</h6>');
            printWindow.document.write(printContents);
            printWindow.document.write(summaryContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            // printWindow.close(); // Optionally close after printing, but can be annoying for users
        });

        // --- Schedule Report Modal Logic (AJAX placeholders) ---

        // Event listener for "Add New Report" button to clear form
        document.getElementById('addNewReportBtn').addEventListener('click', function() {
            document.getElementById('scheduleReportForm').reset();
            document.getElementById('modalReportId').value = ''; // Clear report ID for new entry
            document.getElementById('scheduleReportModalLabel').textContent = 'Schedule New Report';
        });

        // Event listeners for "Edit" buttons
        document.querySelectorAll('.edit-report-btn').forEach(button => {
            button.addEventListener('click', function() {
                const reportId = this.dataset.reportId;
                document.getElementById('scheduleReportModalLabel').textContent = 'Edit Scheduled Report';
                document.getElementById('modalReportId').value = reportId;

                // In a real application, you'd fetch the report details via AJAX here
                // and populate the form fields. For now, this is a placeholder.
                // Example:
                // fetch('ajax/get_scheduled_report.php?id=' + reportId)
                // .then(response => response.json())
                // .then(data => {
                //     document.getElementById('scheduleReportName').value = data.report_name;
                //     document.getElementById('scheduleReportType').value = data.report_type;
                //     document.getElementById('scheduleFrequency').value = data.frequency;
                //     document.getElementById('scheduleRecipient').value = data.recipient_email;
                // })
                // .catch(error => console.error('Error fetching report:', error));

                // For demonstration, if data is already available on page (less common for full edit):
                const row = this.closest('tr');
                document.getElementById('scheduleReportName').value = row.cells[0].textContent;
                // Map display type back to value
                const typeDisplay = row.cells[1].textContent.toLowerCase().replace(/ /g, '_');
                if (typeDisplay.includes('overtime')) {
                    document.getElementById('scheduleReportType').value = 'overtime';
                } else if (typeDisplay.includes('project')) {
                    document.getElementById('scheduleReportType').value = 'hours_by_project';
                } else {
                    document.getElementById('scheduleReportType').value = ''; // Or a default
                }
                document.getElementById('scheduleFrequency').value = row.cells[2].textContent.toLowerCase();
                // Recipient email would need to be fetched from DB as it's not in the table
                document.getElementById('scheduleRecipient').value = ''; // Clear or fetch
            });
        });

        // Handle Schedule Report Form Submission (AJAX placeholder)
        document.getElementById('scheduleReportForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const reportId = document.getElementById('modalReportId').value;
            const action = reportId ? 'update' : 'add'; // Determine if adding or updating

            // Example AJAX call
            fetch('ajax/handle_scheduled_report.php', { // You need to create this PHP file
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Close modal and refresh the page to show updated list
                    const modal = bootstrap.Modal.getInstance(document.getElementById('scheduleReportModal'));
                    modal.hide();
                    location.reload(); // Simple reload; consider more dynamic table update for production
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });

        // Handle Run Report Button (AJAX placeholder)
        document.querySelectorAll('.run-report-btn').forEach(button => {
            button.addEventListener('click', function() {
                const reportId = this.dataset.reportId;
                if (confirm('Are you sure you want to run this report now?')) {
                    fetch('ajax/run_scheduled_report.php', { // You need to create this PHP file
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'report_id=' + reportId
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload(); // To update 'Last Run' date
                        } else {
                            alert('Error running report: ' + data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });

        // Handle Delete Report Button (AJAX placeholder)
        document.querySelectorAll('.delete-report-btn').forEach(button => {
            button.addEventListener('click', function() {
                const reportId = this.dataset.reportId;
                if (confirm('Are you sure you want to delete this scheduled report?')) {
                    fetch('ajax/delete_scheduled_report.php', { // You need to create this PHP file
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'report_id=' + reportId
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            this.closest('tr').remove(); // Remove row from table
                        } else {
                            alert('Error deleting report: ' + data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });

        // Enable/Disable Download button based on report data presence
        function checkReportData() {
            const tableBody = document.querySelector('#reportPreviewTable tbody');
            const downloadBtn = document.getElementById('downloadReportBtn');
            if (tableBody && tableBody.children.length > 0 && !tableBody.querySelector('td[colspan]')) {
                downloadBtn.disabled = false;
            } else {
                downloadBtn.disabled = true;
            }
        }
        checkReportData(); // Call on page load

        // The "Download Report" button can simply trigger the currently selected output format
        document.getElementById('downloadReportBtn').addEventListener('click', function() {
            // By default, let's make it download as PDF if no specific preference
            // You could add radio buttons for format selection or make it smarter.
            document.getElementById('exportPdfBtn').click();
            // Or if you want to provide choice:
            // if (confirm('Download as PDF (OK) or CSV (Cancel)?')) {
            //     document.getElementById('exportPdfBtn').click();
            // } else {
            //     document.getElementById('exportCsvBtn').click();
            // }
        });
    });
</script>