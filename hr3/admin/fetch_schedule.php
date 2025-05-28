<?php
require_once '../connections.php'; // your PDO connection

// Get the week start date, default to today
$week_start = $_GET['week_start'] ?? date('Y-m-d');

// Calculate week end date (6 days after start)
$week_end = date('Y-m-d', strtotime($week_start . ' +6 days'));

try {
    $stmt = $conn->prepare("
        SELECT
        es.schedule_id,
        es.department_id,
        d.department_name,
        e.FullName,
        s.shift_name,
        s.start_time,
        s.end_time,
        es.schedule_date
    FROM hr3.employee_schedules es
    JOIN hr1.employeeprofilesetup e ON es.EmployeeID = e.EmployeeID9
    JOIN hr3.shifts s ON es.shift_id = s.shift_id
    LEFT JOIN hr3.departments d ON es.department_id = d.department_id
    WHERE es.schedule_date BETWEEN :week_start AND :week_end
    ORDER BY es.schedule_date, e.FullName
    ");

    $stmt->execute([
        ':week_start' => $week_start,
        ':week_end' => $week_end,
    ]);

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($data);

} catch (PDOException $e) {
    // Send error as JSON
    header('Content-Type: application/json', true, 500);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}
?>
