<?php
session_start();
ob_start();

$title = 'Attendance Tracking';
include_once 'admin.php';

// Assuming connections.php establishes a PDO connection ($conn)
// that has access permissions to both hr3 and hr4 databases.
include('../connections.php');

// --- Overall Attendance Summary Calculations ---
$today = date('Y-m-d');

// 1. Employees Clocked In Today
$clockedInCount = 0;
try {
    $sqlClockedIn = "
        SELECT COUNT(DISTINCT a.employee_id) AS count_clocked_in
        FROM hr3.attendance a
        WHERE DATE(a.record_time) = ? AND a.record_type = 'Clock In'
    ";
    $stmtClockedIn = $conn->prepare($sqlClockedIn);
    $stmtClockedIn->execute([$today]);
    $clockedInResult = $stmtClockedIn->fetch(PDO::FETCH_ASSOC);
    $clockedInCount = $clockedInResult['count_clocked_in'] ?? 0;
} catch (PDOException $e) {
    error_log("Error fetching clocked in count: " . $e->getMessage());
    // You might want to display an error message on the page for debugging:
    // echo "<p class='text-danger'>Error: Could not fetch clocked in count.</p>";
}

// 2. Absent Today (Total Employees - Clocked In Employees)
// First, get total active employees
$totalEmployees = 0;
try {
    $sqlTotalEmployees = "SELECT COUNT(employee_id) AS total_employees FROM hr4.employees";
    $stmtTotalEmployees = $conn->query($sqlTotalEmployees);
    $totalEmployeesResult = $stmtTotalEmployees->fetch(PDO::FETCH_ASSOC);
    $totalEmployees = $totalEmployeesResult['total_employees'] ?? 0;
} catch (PDOException $e) {
    error_log("Error fetching total employees count: " . $e->getMessage());
}

$absentCount = $totalEmployees - $clockedInCount; // Simple calculation based on 'Clock In' records

// (Alternative for Absent - more robust, directly from absentee modal logic)
// $absentCount = 0;
// try {
//     $absentSql = "
//         SELECT COUNT(e.employee_id) AS count_absent
//         FROM hr4.employees e
//         WHERE e.employee_id NOT IN (
//             SELECT DISTINCT a.employee_id FROM hr3.attendance a
//             WHERE DATE(a.record_time) = ?
//         )
//     ";
//     $stmtAbsentCount = $conn->prepare($absentSql);
//     $stmtAbsentCount->execute([$today]);
//     $absentResult = $stmtAbsentCount->fetch(PDO::FETCH_ASSOC);
//     $absentCount = $absentResult['count_absent'] ?? 0;
// } catch (PDOException $e) {
//     error_log("Error fetching absent count: " . $e->getMessage());
// }


// 3. Late Arrivals Today
$lateCount = 0;
try {
    $lateSqlForCount = "
        SELECT COUNT(DISTINCT e.employee_id) AS count_late
        FROM hr3.attendance a
        JOIN hr4.employees e ON e.employee_id = a.employee_id
        WHERE DATE(a.record_time) = ? AND a.record_type = 'Clock In'
        GROUP BY e.employee_id
        HAVING TIME(MIN(a.record_time)) > '09:00:00'
    ";
    $stmtLateCount = $conn->prepare($lateSqlForCount);
    $stmtLateCount->execute([$today]);
    $lateCountResult = $stmtLateCount->fetchAll(PDO::FETCH_ASSOC); // Fetch all results to count them
    $lateCount = count($lateCountResult); // Count the number of latecomers
} catch (PDOException $e) {
    error_log("Error fetching latecomers count: " . $e->getMessage());
}

// 4. Pending Manual Adjustments
$pendingManualCount = 0;
try {
    $manualSqlCount = "
        SELECT COUNT(*) AS count_manual_pending
        FROM hr3.attendance
        WHERE is_manual_entry = 1
    ";
    $stmtManualCount = $conn->query($manualSqlCount);
    $pendingManualResult = $stmtManualCount->fetch(PDO::FETCH_ASSOC);
    $pendingManualCount = $pendingManualResult['count_manual_pending'] ?? 0;
} catch (PDOException $e) {
    error_log("Error fetching pending manual count: " . $e->getMessage());
}

// --- End Overall Attendance Summary Calculations ---


// Filter for Employee Attendance Lookup table
$emp_name = $_GET['emp_name'] ?? '';
$start_date = $_GET['start_date'] ?? date('Y-m-d');
$end_date = $_GET['end_date'] ?? date('Y-m-d');

// Base SQL for employee lookup
$sql = "
SELECT 
    e.employee_id,
    e.first_name,
    DATE(a.record_time) as date,
    MIN(CASE WHEN a.record_type = 'Clock In' THEN a.record_time END) as time_in,
    MAX(CASE WHEN a.record_type = 'Clock Out' THEN a.record_time END) as time_out,
    MIN(CASE WHEN a.record_type = 'Break Start' THEN a.record_time END) as break_start,
    MAX(CASE WHEN a.record_type = 'Break End' THEN a.record_time END) as break_end
FROM hr3.attendance a
JOIN hr4.employees e ON e.employee_id = a.employee_id
WHERE DATE(a.record_time) BETWEEN ? AND ?
";

// Add optional name filter
$params = [$start_date, $end_date];

if (!empty($emp_name)) {
    $sql .= " AND e.first_name LIKE ?";
    $params[] = '%' . $emp_name . '%';
}

$sql .= " GROUP BY e.employee_id, DATE(a.record_time)
          ORDER BY date DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .dashboard {
        color: var(--bs-dark);
    }
</style>
<div class="p-2 gap-3">
    <div class="d-flex col">
        <h6 class=" text-muted pe-none mb-0"><a class="text-decoration-none text-muted" href="">Home</a> > <a class="text-decoration-none text-muted" href="">Attendance Tracking</a></h6>
    </div>
    <hr>
    <div class="nav col-12 d-flex justify-content-around">
        <?php include('nav/attendance/nav.php') ?>
    </div>
    <hr>
    <div class="container-fluid shadow-lg col p-5">
        <div class="col d-flex flex-column border border-2 border rounded-3 p-4">
            <h3 class="align-items-center text-center">Overall Attendance Summary</h3>
            <hr class="">
            <h4>Employees Clocked In: <?= $clockedInCount ?></h4>
            <h4>Absent Today: <?= $absentCount ?></h4>
            <h4>Late Arrivals: <?= $lateCount ?></h4>
            <h4>Pending Manual Adjustments: <?= $pendingManualCount ?></h4>
            <br>
            <div class="d-flex justify-content-around px-5">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#absenteesModal">View Absentees</button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#latecomersModal">View Latecomers</button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pendingModal">Review Pending</button>
            </div>

        </div>
        <div class="col d-flex flex-column border border-2 border rounded-3 p-4 mt-3">
            <h3 class="align-items-center text-center">Employee Attendance Lookup</h3>
            <hr class="">
            <div class="d-flex flex-column">
                <form method="GET" class="d-flex flex-column">
                    <div>
                        <h4>Employee Name:</h4>
                        <span class="d-flex gap-1">
                            <input class="form-control w-25" name="emp_name" type="text" value="<?= htmlspecialchars($_GET['emp_name'] ?? '') ?>">
                            <button class="btn btn-danger" type="submit">Search</button>
                        </span>
                    </div><br>
                    <div>
                        <h4>Date:</h4>
                        <span>
                            <input class="form-control-lg" type="date" name="start_date" value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>">
                            to
                            <input class="form-control-lg" type="date" name="end_date" value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>">
                        </span>
                    </div>
                </form>
            <hr class="">
            <div class="">
                <table class="table table-striped table-hover border">
                    <thead>
                        <tr>
                            <th scope="col">Employee ID</th>
                            <th scope="col">Employee Name</th>
                            <th scope="col">Date</th>
                            <th scope="col">Time In</th>
                            <th scope="col">Time Out</th>
                            <th scope="col">Break Time</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($result)): ?>
                        <tr><td colspan="7" class="text-center">No attendance records found for the selected criteria.</td></tr>
                    <?php else: ?>
                        <?php foreach ($result as $row): ?>
                            <?php
                            // Time formatting
                            $in = $row['time_in'] ? date('h:i A', strtotime($row['time_in'])) : '-';
                            $out = $row['time_out'] ? date('h:i A', strtotime($row['time_out'])) : '-';
                            
                            // Break time calc
                            $break = '-';
                            if ($row['break_start'] && $row['break_end']) {
                                $start = strtotime($row['break_start']);
                                $end = strtotime($row['break_end']);
                                $break_duration = round(($end - $start) / 60); // in minutes
                                $break = $break_duration >= 60 ? round($break_duration / 60, 1) . ' hr' : $break_duration . ' min';
                            }

                            // Determine status
                            $status = ($row['time_in'] && $row['time_out']) ? 'Present' : 'Incomplete';
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($row['employee_id']) ?></td>
                                <td><?= htmlspecialchars($row['first_name']) ?></td>
                                <td><?= htmlspecialchars($row['date']) ?></td>
                                <td><?= $in ?></td>
                                <td><?= $out ?></td>
                                <td><?= $break ?></td>
                                <td><?= $status ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <hr class="">

        </div>
    </div>
</div>

<?php
ob_end_flush();
?>

<div class="modal fade" id="absenteesModal" tabindex="-1" aria-labelledby="absenteesModalLabel" aria-hidden="true" data-bs-backdrop="false">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="absenteesModalLabel">Employees Absent Today</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul>
          <?php
          // This uses the $today variable defined at the top
          $absentSql = "
              SELECT e.employee_id, e.first_name
              FROM hr4.employees e
              WHERE e.employee_id NOT IN (
                  SELECT DISTINCT a.employee_id FROM hr3.attendance a
                  WHERE DATE(a.record_time) = ?
              )
          ";
          $stmtAbsent = $conn->prepare($absentSql);
          $stmtAbsent->execute([$today]);
          $absentees = $stmtAbsent->fetchAll(PDO::FETCH_ASSOC);
          if (empty($absentees)) {
              echo "<li>No absentees recorded for today.</li>";
          } else {
              foreach ($absentees as $absent) {
                  echo "<li>{$absent['first_name']} (ID: {$absent['employee_id']})</li>";
              }
          }
          ?>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="latecomersModal" tabindex="-1" aria-labelledby="latecomersModalLabel" aria-hidden="true" data-bs-backdrop="false">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="latecomersModalLabel">Latecomers Today</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul>
          <?php
          // This uses the $today variable defined at the top
          $lateSql = "
              SELECT e.employee_id, e.first_name, MIN(a.record_time) as time_in
              FROM hr3.attendance a
              JOIN hr4.employees e ON e.employee_id = a.employee_id
              WHERE DATE(a.record_time) = ? AND a.record_type = 'Clock In'
              GROUP BY e.employee_id
              HAVING TIME(time_in) > '09:00:00'
          ";
          $stmtLate = $conn->prepare($lateSql);
          $stmtLate->execute([$today]);
          $latecomers = $stmtLate->fetchAll(PDO::FETCH_ASSOC);
          if (empty($latecomers)) {
              echo "<li>No latecomers recorded for today.</li>";
          } else {
              foreach ($latecomers as $late) {
                  $formatted = date('h:i A', strtotime($late['time_in']));
                  echo "<li>{$late['first_name']} (ID: {$late['employee_id']}) - Clocked in at $formatted</li>";
              }
          }
          ?>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="pendingModal" tabindex="-1" aria-labelledby="pendingModalLabel" aria-hidden="true" data-bs-backdrop="false">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pendingModalLabel">Pending Manual Adjustments</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul>
          <?php
          // This uses the $today variable defined at the top
          $manualSql = "
              SELECT e.employee_id, e.first_name, a.record_time, a.record_type, a.notes
              FROM hr3.attendance a
              JOIN hr4.employees e ON e.employee_id = a.employee_id
              WHERE a.is_manual_entry = 1
              ORDER BY a.record_time DESC
              LIMIT 50
          ";
          $stmtManual = $conn->query($manualSql); // Using query() as there are no parameters
          $manualEntries = $stmtManual->fetchAll(PDO::FETCH_ASSOC);
          if (empty($manualEntries)) {
              echo "<li>No pending manual adjustments.</li>";
          } else {
              foreach ($manualEntries as $entry) {
                  $formattedTime = date('Y-m-d h:i A', strtotime($entry['record_time']));
                  echo "<li>{$entry['first_name']} (ID: {$entry['employee_id']}) - {$entry['record_type']} at $formattedTime <br><em>Note:</em> {$entry['notes']}</li>";
              }
          }
          ?>
        </ul>
      </div>
    </div>
  </div>
</div>