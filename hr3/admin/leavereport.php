<?php
session_start();
ob_start(); // Start output buffering

// Page title
$title = "Simulated Reports (No Database)"; // Indicate no database

// Include your admin.php for common layout (header, footer, etc.)
// Make sure admin.php sets up Bootstrap 5 and the basic page layout.
include_once 'admin.php'; // Adjust path if necessary

// --- Hardcoded Data (Simulating Database Tables) ---
// IMPORTANT: This data is static and will not change based on UI actions.

// Simulate hr4.Employees table
$employees_data = [
    ['employee_id' => 101, 'first_name' => 'Alice', 'last_name' => 'Smith', 'department_id' => 1, 'job_title' => 'HR Manager'],
    ['employee_id' => 102, 'first_name' => 'Bob', 'last_name' => 'Johnson', 'department_id' => 2, 'job_title' => 'Software Engineer'],
    ['employee_id' => 103, 'first_name' => 'Charlie', 'last_name' => 'Brown', 'department_id' => 3, 'job_title' => 'Marketing Specialist'],
    ['employee_id' => 104, 'first_name' => 'Diana', 'last_name' => 'Prince', 'department_id' => 1, 'job_title' => 'HR Assistant'],
    ['employee_id' => 105, 'first_name' => 'Eve', 'last_name' => 'Adams', 'department_id' => 2, 'job_title' => 'QA Engineer'],
];

// Simulate hr3.Departments table
$departments_data = [
    ['department_id' => 1, 'department_name' => 'Human Resources'],
    ['department_id' => 2, 'department_name' => 'Engineering'],
    ['department_id' => 3, 'department_name' => 'Marketing'],
];

// Simulate hr3.LeaveTypes table (matching the one from manage_leave_types.php)
$leave_types_data = [
    ['leave_type_id' => 1, 'leave_type_name' => 'Sick Leave'],
    ['leave_type_id' => 2, 'leave_type_name' => 'Vacation Leave'],
    ['leave_type_id' => 3, 'leave_type_name' => 'Maternity Leave'],
    ['leave_type_id' => 4, 'leave_type_name' => 'Paternity Leave'],
    ['leave_type_id' => 5, 'leave_type_name' => 'Unpaid Leave'],
    ['leave_type_id' => 6, 'leave_type_name' => 'Bereavement Leave'],
];

// Simulate hr3.attendance table for monthly summary
$attendance_data = [
    // Employee 102 (Bob)
    ['employee_id' => 102, 'record_time' => '2024-05-01 09:00:00', 'record_type' => 'Clock In'],
    ['employee_id' => 102, 'record_time' => '2024-05-01 17:00:00', 'record_type' => 'Clock Out'],
    ['employee_id' => 102, 'record_time' => '2024-05-02 09:15:00', 'record_type' => 'Clock In'],
    ['employee_id' => 102, 'record_time' => '2024-05-02 17:30:00', 'record_type' => 'Clock Out'],
    ['employee_id' => 102, 'record_time' => '2024-05-03 09:00:00', 'record_type' => 'Clock In'],
    ['employee_id' => 102, 'record_time' => '2024-05-03 17:00:00', 'record_type' => 'Clock Out'],
    // Employee 103 (Charlie)
    ['employee_id' => 103, 'record_time' => '2024-05-01 08:30:00', 'record_type' => 'Clock In'],
    ['employee_id' => 103, 'record_time' => '2024-05-01 16:30:00', 'record_type' => 'Clock Out'],
    ['employee_id' => 103, 'record_time' => '2024-05-02 08:45:00', 'record_type' => 'Clock In'],
    ['employee_id' => 103, 'record_time' => '2024-05-02 16:45:00', 'record_type' => 'Clock Out'],
    // Employee 104 (Diana)
    ['employee_id' => 104, 'record_time' => '2024-05-01 09:00:00', 'record_type' => 'Clock In'],
    ['employee_id' => 104, 'record_time' => '2024-05-01 17:00:00', 'record_type' => 'Clock Out'],
];

// Simulate hr3.leaverequests table
$leave_requests_data = [
    ['request_id' => 1, 'employee_id' => 102, 'leave_type_id' => 2, 'start_date' => '2024-06-10', 'end_date' => '2024-06-14', 'total_days' => 5.0, 'status' => 'Pending'],
    ['request_id' => 2, 'employee_id' => 103, 'leave_type_id' => 1, 'start_date' => '2024-05-15', 'end_date' => '2024-05-15', 'total_days' => 1.0, 'status' => 'Approved'],
    ['request_id' => 3, 'employee_id' => 104, 'leave_type_id' => 2, 'start_date' => '2024-07-01', 'end_date' => '2024-07-05', 'total_days' => 5.0, 'status' => 'Rejected'],
    ['request_id' => 4, 'employee_id' => 105, 'leave_type_id' => 3, 'start_date' => '2024-08-01', 'end_date' => '2024-09-30', 'total_days' => 60.0, 'status' => 'Approved'],
];

// Simulate hr3.projects and hr3.tasks
$projects_data = [
    ['project_id' => 1, 'project_name' => 'HR System Upgrade', 'department_id' => 1],
    ['project_id' => 2, 'project_name' => 'Mobile App Development', 'department_id' => 2],
];

$tasks_data = [
    ['task_id' => 1, 'task_name' => 'Database Migration', 'project_id' => 2, 'assigned_to_employee_id' => 102, 'estimated_hours' => 40, 'status' => 'Completed'],
    ['task_id' => 2, 'task_name' => 'UI Redesign', 'project_id' => 2, 'assigned_to_employee_id' => 105, 'estimated_hours' => 60, 'status' => 'In Progress'],
    ['task_id' => 3, 'task_name' => 'Onboarding Flow', 'project_id' => 1, 'assigned_to_employee_id' => 104, 'estimated_hours' => 20, 'status' => 'To Do'],
];


// --- Helper Functions to "Join" Data (like database joins) ---

function getEmployeeName($employee_id, $employees) {
    foreach ($employees as $emp) {
        if ($emp['employee_id'] == $employee_id) {
            return htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']);
        }
    }
    return 'N/A';
}

function getDepartmentName($department_id, $departments) {
    foreach ($departments as $dept) {
        if ($dept['department_id'] == $department_id) {
            return htmlspecialchars($dept['department_name']);
        }
    }
    return 'N/A';
}

function getLeaveTypeName($leave_type_id, $leave_types) {
    foreach ($leave_types as $lt) {
        if ($lt['leave_type_id'] == $leave_type_id) {
            return htmlspecialchars($lt['leave_type_name']);
        }
    }
    return 'N/A';
}

function getProjectName($project_id, $projects) {
    foreach ($projects as $proj) {
        if ($proj['project_id'] == $project_id) {
            return htmlspecialchars($proj['project_name']);
        }
    }
    return 'N/A';
}

// --- Report Generation Logic ---
$report_type = $_GET['report_type'] ?? '';
$report_content = ''; // HTML content of the generated report
$report_title = '';

if ($report_type) {
    switch ($report_type) {
        case 'monthly_attendance':
            $report_title = "Monthly Attendance Summary (May 2024)";
            $report_content .= '<div class="table-responsive"><table class="table table-striped table-hover border text-center"><thead><tr>';
            $report_content .= '<th>Employee Name</th><th>Total Clock-Ins</th><th>Total Clock-Outs</th>';
            $report_content .= '</tr></thead><tbody>';

            $attendance_summary = [];
            foreach ($attendance_data as $record) {
                $emp_id = $record['employee_id'];
                if (!isset($attendance_summary[$emp_id])) {
                    $attendance_summary[$emp_id] = ['clock_ins' => 0, 'clock_outs' => 0];
                }
                if ($record['record_type'] == 'Clock In') {
                    $attendance_summary[$emp_id]['clock_ins']++;
                } elseif ($record['record_type'] == 'Clock Out') {
                    $attendance_summary[$emp_id]['clock_outs']++;
                }
            }

            foreach ($attendance_summary as $emp_id => $summary) {
                $report_content .= '<tr>';
                $report_content .= '<td>' . getEmployeeName($emp_id, $employees_data) . '</td>';
                $report_content .= '<td>' . htmlspecialchars($summary['clock_ins']) . '</td>';
                $report_content .= '<td>' . htmlspecialchars($summary['clock_outs']) . '</td>';
                $report_content .= '</tr>';
            }
            $report_content .= '</tbody></table></div>';
            break;

        case 'leave_requests_overview':
            $report_title = "Leave Requests Overview";
            $report_content .= '<div class="table-responsive"><table class="table table-striped table-hover border text-center"><thead><tr>';
            $report_content .= '<th>Request ID</th><th>Employee Name</th><th>Leave Type</th><th>Start Date</th><th>End Date</th><th>Total Days</th><th>Status</th>';
            $report_content .= '</tr></thead><tbody>';

            foreach ($leave_requests_data as $request) {
                $report_content .= '<tr>';
                $report_content .= '<td>' . htmlspecialchars($request['request_id']) . '</td>';
                $report_content .= '<td>' . getEmployeeName($request['employee_id'], $employees_data) . '</td>';
                $report_content .= '<td>' . getLeaveTypeName($request['leave_type_id'], $leave_types_data) . '</td>';
                $report_content .= '<td>' . htmlspecialchars($request['start_date']) . '</td>';
                $report_content .= '<td>' . htmlspecialchars($request['end_date']) . '</td>';
                $report_content .= '<td>' . htmlspecialchars($request['total_days']) . '</td>';
                $report_content .= '<td>' . htmlspecialchars($request['status']) . '</td>';
                $report_content .= '</tr>';
            }
            $report_content .= '</tbody></table></div>';
            break;

        case 'project_task_status':
            $report_title = "Project Task Status";
            $report_content .= '<div class="table-responsive"><table class="table table-striped table-hover border text-center"><thead><tr>';
            $report_content .= '<th>Task Name</th><th>Project</th><th>Assigned To</th><th>Estimated Hours</th><th>Status</th>';
            $report_content .= '</tr></thead><tbody>';

            foreach ($tasks_data as $task) {
                $report_content .= '<tr>';
                $report_content .= '<td>' . htmlspecialchars($task['task_name']) . '</td>';
                $report_content .= '<td>' . getProjectName($task['project_id'], $projects_data) . '</td>';
                $report_content .= '<td>' . getEmployeeName($task['assigned_to_employee_id'], $employees_data) . '</td>';
                $report_content .= '<td>' . htmlspecialchars($task['estimated_hours']) . '</td>';
                $report_content .= '<td>' . htmlspecialchars($task['status']) . '</td>';
                $report_content .= '</tr>';
            }
            $report_content .= '</tbody></table></div>';
            break;

        default:
            $report_title = "Select a Report Type";
            $report_content = '<p class="text-center text-muted">Please select a report type from the dropdown above.</p>';
            break;
    }
} else {
    $report_title = "Select a Report Type";
    $report_content = '<p class="text-center text-muted">Please select a report type from the dropdown above.</p>';
}

?>

<div class="p-2 gap-3">
    <div class="d-flex col">
        <h6 class="text-muted pe-none mb-0">
            <a class="text-decoration-none text-muted" href="">Home</a> >
            <a class="text-decoration-none text-muted" href="generate_report.php">Generate Reports</a>
        </h6>
    </div>
    <hr>
    <div class="nav col-12 d-flex justify-content-around">
        <?php
        // Example sub-navigation; adjust as per your project's nav structure
         include('nav/leave management/nav.php');

        ?>
    </div>
    <hr>

        <div class="col d-flex flex-column p-4 border border-2 rounded-3 mb-4">
            <h3 class="text-center">Select Report</h3>
            <hr>
            <form method="GET" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label for="reportType" class="form-label">Report Type</label>
                    <select class="form-select" id="reportType" name="report_type" onchange="this.form.submit()">
                        <option value="">-- Select a Report --</option>
                        <option value="monthly_attendance" <?= ($report_type == 'monthly_attendance') ? 'selected' : '' ?>>Monthly Attendance Summary</option>
                        <option value="leave_requests_overview" <?= ($report_type == 'leave_requests_overview') ? 'selected' : '' ?>>Leave Requests Overview</option>
                        <option value="project_task_status" <?= ($report_type == 'project_task_status') ? 'selected' : '' ?>>Project Task Status</option>
                        </select>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary" style="display: none;">Generate Report</button>
                    <a href="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="btn btn-secondary">Clear Report</a>
                </div>
            </form>
        </div>

        <div class="col d-flex flex-column p-4 border border-2 rounded-3">
            <h3 class="text-center"><?= $report_title ?></h3>
            <hr>
            <?= $report_content ?>
        </div>
    </div>
</div>

<?php ob_end_flush(); ?>