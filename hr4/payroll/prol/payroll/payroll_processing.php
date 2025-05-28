<?php
include '../Database/db.php';

// Define date range and total working days in that period
$start_date = '2025-05-01';
$end_date = '2025-05-31';
$total_working_days = 22;

$sql = " SELECT * FROM `employeeprofilesetup`;

-- Latest salary per employee
--     LEFT JOIN (
--     SELECT s.employee_id, s.base_salary
--     FROM salary_records s
--     INNER JOIN (
--             SELECT employee_id, MAX(effective_date) AS max_date
--             FROM salary_records
--             GROUP BY employee_id
--         ) latest ON s.employee_id = latest.employee_id AND s.effective_date = latest.max_date
--     ) sr ON e.employee_id = sr.employee_id

-- -- Total current deductions per employee
--     LEFT JOIN (
--         SELECT employee_id, SUM(amount) AS total_deductions
--         FROM employee_deductions
--         WHERE end_date IS NULL OR end_date >= CURDATE()
--         GROUP BY employee_id
--     ) ed ON e.employee_id = ed.employee_id

-- -- Attendance summary: total present days and overtime hours filtered by date range
--     LEFT JOIN (
--         SELECT employee_id, 
--             COUNT(DISTINCT attendance_date) AS total_present_days, 
--             SUM(overtime_hours) AS total_overtime_hours
--         FROM dailyattendancesummary
--     WHERE status = 'Present' 
--         AND attendance_date BETWEEN '$start_date' AND '$end_date'
--         GROUP BY employee_id
--     ) att ON e.employee_id = att.employee_id LIMIT 25;";
$result = $hr1->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Employee Payroll Summary</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            color: #333;
        }

        .main {
            width: 95%;
            margin: 20px auto;
        }

        .container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .main h2 {
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9em;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }

        th,
        td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        tbody tr:hover {
            background-color: #e8f4fc;
        }

        .currency {
            text-align: right;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #777;
        }
    </style>
</head>

<body>
    <aside>
        <?php
        include 'dashboard.php';
        ?>
    </aside>
    <div class="main">
        <div class="container">
            <h2>Employee Payroll Summary (<?= htmlspecialchars($start_date) ?> to <?= htmlspecialchars($end_date) ?>)</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Position</th>
                        <th>Base Salary</th>
                        <th>Present Days</th>
                        <th>Absent Days</th>
                        <th>Overtime (hrs)</th>
                        <th>Gross Pay</th>
                        <th>Deductions</th>
                        <th>Net Pay</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <?php
                            $base_salary = 0; //(float)$row["base_salary"];
                            $deductions = 0; // (float)$row["total_deductions"];
                            $total_present_days = 0; //(int)$row["total_present_days"];
                            $total_overtime_hours = 0; //(float)$row["total_overtime_hours"];
                            $absent_days = max(0, $total_working_days - $total_present_days);

                            // Calculate gross pay (prorated by attendance days)
                            $gross_pay = ($base_salary / $total_working_days) * $total_present_days;

                            // Net pay calculation
                            $net_pay = $gross_pay - $deductions;
                            if ($net_pay < 0) {
                                $net_pay = 0;
                            }
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($row["EmployeeID"]) ?></td>
                                <td><?= htmlspecialchars($row["FullName"]) ?></td>
                                <td><?= htmlspecialchars($row["Position"] ?? 'N/A') ?></td>
                                <td class="currency"><?= number_format($base_salary, 2) ?></td>
                                <td><?= $total_present_days ?></td>
                                <td><?= $absent_days ?></td>
                                <td><?= number_format($total_overtime_hours, 2) ?></td>
                                <td class="currency"><?= number_format($gross_pay, 2) ?></td>
                                <td class="currency"><?= number_format($deductions, 2) ?></td>
                                <td class="currency"><?= number_format($net_pay, 2) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="11" class="no-data">No employee records found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>