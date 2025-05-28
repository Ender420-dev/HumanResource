<?php
include '../Database/db.php';

// Define payroll period - adjust these dynamically as needed
$start_date = '2025-05-01';
$end_date = '2025-05-31';
$total_working_days = 22; // number of working days in this period

// Comment out delete block because now you don't have a payments table
/*
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_payment_id'])) {
    $delete_id = intval($_POST['delete_payment_id']);

    $stmt = $conn->prepare("DELETE FROM payments WHERE payment_id = ?");
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Payment deleted successfully.'); window.location.href = 'payment_history.php';</script>";
        exit;
    } else {
        echo "<script>alert('Failed to delete payment.');</script>";
    }
}
*/

$sql = "
SELECT 
    e.employee_id,
    e.first_name,
    e.last_name,
    p.position_name,
    sr.base_salary,
    COALESCE(ed.total_deductions, 0) AS total_deductions,
    COALESCE(att.total_present_days, 0) AS total_present_days,
    COALESCE(att.total_overtime_hours, 0) AS total_overtime_hours
FROM employees e
LEFT JOIN positions p ON e.position_id = p.position_id

LEFT JOIN (
    SELECT s.employee_id, s.base_salary
    FROM salary_records s
    INNER JOIN (
        SELECT employee_id, MAX(effective_date) AS max_date
        FROM salary_records
        GROUP BY employee_id
    ) latest ON s.employee_id = latest.employee_id AND s.effective_date = latest.max_date
) sr ON e.employee_id = sr.employee_id

LEFT JOIN (
    SELECT employee_id, SUM(amount) AS total_deductions
    FROM employee_deductions
    WHERE end_date IS NULL OR end_date >= CURDATE()
    GROUP BY employee_id
) ed ON e.employee_id = ed.employee_id

LEFT JOIN (
    SELECT employee_id, 
           COUNT(DISTINCT attendance_date) AS total_present_days, 
           SUM(overtime_hours) AS total_overtime_hours
    FROM dailyattendancesummary
    WHERE status = 'Present' 
      AND attendance_date BETWEEN '$start_date' AND '$end_date'
    GROUP BY employee_id
) att ON e.employee_id = att.employee_id

ORDER BY e.last_name, e.first_name
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payroll History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        th, td {
            text-align: center;
        }
        .action {
            width: 180px;
        }
    </style>
</head>
<body>
<aside>
    <?php include 'dashboard.php'; ?>
</aside>
<div class="main" style="width: 100vw; max-width: 100%; margin-left: 0; padding: 2rem 0;">
    <div class="container-fluid">
        <h2 class="mb-4">Payroll History (<?= htmlspecialchars($start_date) ?> to <?= htmlspecialchars($end_date) ?>)</h2>
        <table class="table table-bordered table-striped w-100">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Base Salary (₱)</th>
                    <th>Present Days</th>
                    <th>Absent Days</th>
                    <th>Overtime Hours</th>
                    <th>Gross Pay (₱)</th>
                    <th>Deductions (₱)</th>
                    <th>Net Pay (₱)</th>
                    <th class="action">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php
                            $base_salary = (float)$row["base_salary"];
                            $deductions = (float)$row["total_deductions"];
                            $total_present_days = (int)$row["total_present_days"];
                            $total_overtime_hours = (float)$row["total_overtime_hours"];
                            $absent_days = max(0, $total_working_days - $total_present_days);

                            $gross_pay = ($base_salary / $total_working_days) * $total_present_days;
                            $net_pay = $gross_pay - $deductions;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($row["first_name"] . ' ' . $row["last_name"]) ?></td>
                            <td><?= number_format($base_salary, 2) ?></td>
                            <td><?= $total_present_days ?></td>
                            <td><?= $absent_days ?></td>
                            <td><?= number_format($total_overtime_hours, 2) ?></td>
                            <td><?= number_format($gross_pay, 2) ?></td>
                            <td><?= number_format($deductions, 2) ?></td>
                            <td><?= number_format($net_pay, 2) ?></td>
                            <td>
                                <a href="generate_payslip.php?employee_id=<?= $row['employee_id'] ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>" class="btn btn-sm btn-success">Generate Payslip</a>
                                <!--
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this payment?');">
                                    <input type="hidden" name="delete_payment_id" value="<?= $row['payment_id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                                -->
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="9" class="text-center">No payroll records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="payment_history.php" class="btn btn-secondary">← Back to Payment Processing</a>
    </div>
</div>
</body>
</html>

<?php $conn->close(); ?>
