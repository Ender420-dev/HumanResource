<?php
// Include the connection file which sets up $conn_hr3 and $conn_hr4
include_once '../connections.php'; // Adjust path as necessary

// Assuming a specific employee ID and a date for demonstration
$employeeId = 1; // This ID must exist in hr4.employees and hr3.employees for a full join
$attendanceDate = '2024-05-20'; // Example date, adjust as needed

// Check if both connections are successful
if ($conn_hr3 && $conn_hr4) {
    try {
        echo "<h2>Integrated Employee Data (from HR3 & HR4)</h2>";

        // Query HR4 for core employee details and latest performance review
        $stmtHR4 = $conn_hr4->prepare("
            SELECT
                e.first_name,
                e.last_name,
                e.email,
                p.position_name,
                d.department_name,
                pr.score AS latest_review_score,
                pr.review_date AS latest_review_date
            FROM
                employees e
            LEFT JOIN
                positions p ON e.position_id = p.position_id
            LEFT JOIN
                departments d ON p.department_id = d.department_id
            LEFT JOIN
                performance_reviews pr ON e.employee_id = pr.employee_id
            WHERE
                e.employee_id = ?
            ORDER BY
                pr.review_date DESC
            LIMIT 1
        ");
        $stmtHR4->execute([$employeeId]);
        $employeeDetails = $stmtHR4->fetch(PDO::FETCH_ASSOC);

        // Query HR3 for daily attendance summary
        $stmtHR3 = $conn_hr3->prepare("
            SELECT
                das.total_hours,
                das.status AS attendance_status,
                das.first_clock_in,
                das.last_clock_out
            FROM
                dailyattendancesummary das
            WHERE
                das.employee_id = ? AND das.attendance_date = ?
        ");
        // Important: hr3.employees.employee_id must match hr4.employees.employee_id for this to work seamlessly
        $stmtHR3->execute([$employeeId, $attendanceDate]);
        $attendanceSummary = $stmtHR3->fetch(PDO::FETCH_ASSOC);

        if ($employeeDetails) {
            echo "<h3>Employee: " . htmlspecialchars($employeeDetails['first_name'] . ' ' . $employeeDetails['last_name']) . "</h3>";
            echo "<p>Email: " . htmlspecialchars($employeeDetails['email']) . "</p>";
            echo "<p>Position: " . htmlspecialchars($employeeDetails['position_name'] ?: 'N/A') . "</p>";
            echo "<p>Department: " . htmlspecialchars($employeeDetails['department_name'] ?: 'N/A') . "</p>";

            echo "<h4>Performance Review (from HR4):</h4>";
            if ($employeeDetails['latest_review_score'] !== null) {
                echo "<p>Latest Score: " . htmlspecialchars($employeeDetails['latest_review_score']) . " (on " . htmlspecialchars($employeeDetails['latest_review_date']) . ")</p>";
            } else {
                echo "<p>No performance review found.</p>";
            }

            echo "<h4>Attendance Summary for " . htmlspecialchars($attendanceDate) . " (from HR3):</h4>";
            if ($attendanceSummary) {
                echo "<p>Total Hours: " . htmlspecialchars($attendanceSummary['total_hours']) . "</p>";
                echo "<p>Status: " . htmlspecialchars($attendanceSummary['attendance_status']) . "</p>";
                echo "<p>Clock In: " . htmlspecialchars($attendanceSummary['first_clock_in'] ?: 'N/A') . "</p>";
                echo "<p>Clock Out: " . htmlspecialchars($attendanceSummary['last_clock_out'] ?: 'N/A') . "</p>";
            } else {
                echo "<p>No attendance record found for this date.</p>";
            }

        } else {
            echo "<p style='color: red;'>Employee with ID {$employeeId} not found in HR4.</p>";
        }

    } catch (PDOException $e) {
        echo "<div style='color: red; border: 1px solid red; padding: 10px;'>Database Error: " . htmlspecialchars($e->getMessage()) . "</div>";
        error_log("HR3/HR4 Integration Error: " . $e->getMessage());
    }
} else {
    echo "<div style='color: orange; border: 1px solid orange; padding: 10px;'>Warning: Both HR3 and HR4 database connections are required for this integration.</div>";
}
?>