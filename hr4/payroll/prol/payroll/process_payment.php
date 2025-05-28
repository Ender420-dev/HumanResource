<?php
include '../Database/db.php';

define('STANDARD_MONTHLY_HOURS', 160);

// Handle payment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employee_id'], $_POST['net_pay'])) {
    $employee_id = $_POST['employee_id'];
    $amount = $_POST['net_pay'];
    $pay_period = $_POST['pay_period'] ?? date('Y-m');

    // Insert payment record
    $stmt = $conn->prepare("INSERT INTO payments (employee_id, amount, pay_period) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $employee_id, $amount, $pay_period);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Payment of ₱" . number_format($amount, 2) . " to Employee ID {$employee_id} recorded successfully.</p>";
    } else {
        echo "<p style='color:red;'>Error recording payment: " . htmlspecialchars($stmt->error) . "</p>";
    }

    $stmt->close();
}

// Fetch payroll data
$sql = "
SELECT 
    e.employee_id,
    e.first_name,
    e.last_name,
    sr.base_salary,

    IFNULL(SUM(sl.total_hours), 0) AS total_hours,
    IFNULL(SUM(sl.overtime), 0) AS overtime,
    IFNULL(SUM(sl.night_diff_hours), 0) AS night_diff_hours,
    IFNULL(SUM(ed.amount), 0) AS total_deductions,

    ba.bank_name,
    ba.account_number,
    ba.account_type

FROM employees e
LEFT JOIN salary_records sr ON sr.employee_id = e.employee_id 
    AND sr.effective_date = (
        SELECT MAX(effective_date) FROM salary_records WHERE employee_id = e.employee_id
    )
LEFT JOIN shift_logs sl ON sl.employee_id = e.employee_id
    AND sl.is_paid = 0
    AND MONTH(sl.shift_date) = MONTH(CURRENT_DATE())
    AND YEAR(sl.shift_date) = YEAR(CURRENT_DATE())
LEFT JOIN employee_deductions ed ON ed.employee_id = e.employee_id
    AND (ed.end_date IS NULL OR ed.end_date >= CURRENT_DATE())
LEFT JOIN employee_bank_accounts ba ON ba.employee_id = e.employee_id

WHERE e.status = 'Active'
GROUP BY e.employee_id
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Process Employee Payments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <aside>
        <?php
        include 'dashboard.php'
        ?>
    </aside>
    <div class="main" style="width: 100vw; max-width: 100%; margin-left: 0; padding: 2rem 0;">
        <div class="container">
            <h2 class="mb-4">Employee Payments</h2>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Base Salary (₱)</th>
                <th>Total Hours</th>
                <th>Overtime</th>
                <th>Night Diff</th>
                <th>Gross Pay (₱)</th>
                <th>Deductions (₱)</th>
                <th>Net Pay (₱)</th>
                <th>Bank</th>
                <th>Account</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                $base_salary = $row['base_salary'] ?? 0;
                $hourly_rate = $base_salary / STANDARD_MONTHLY_HOURS;

                $total_hours = $row['total_hours'];
                $overtime = $row['overtime'];
                $night_diff = $row['night_diff_hours'];
                $deduction = $row['total_deductions'];

                $gross_pay = ($total_hours * $hourly_rate)
                            + ($overtime * $hourly_rate * 1.25)
                            + ($night_diff * $hourly_rate * 1.10);

                $net_pay = $gross_pay - $deduction;
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                    <td><?= number_format($base_salary, 2) ?></td>
                    <td><?= number_format($total_hours, 2) ?></td>
                    <td><?= number_format($overtime, 2) ?></td>
                    <td><?= number_format($night_diff, 2) ?></td>
                    <td><?= number_format($gross_pay, 2) ?></td>
                    <td><?= number_format($deduction, 2) ?></td>
                    <td><strong><?= number_format($net_pay, 2) ?></strong></td>
                    <td><?= htmlspecialchars($row['bank_name'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['account_number'] ?? 'N/A') ?> (<?= htmlspecialchars($row['account_type'] ?? '') ?>)</td>
                    <td>
                        <form method="POST" style="margin:0;">
                            <input type="hidden" name="employee_id" value="<?= $row['employee_id'] ?>">
                            <input type="hidden" name="net_pay" value="<?= $net_pay ?>">
                            <input type="hidden" name="pay_period" value="<?= date('Y-m') ?>">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-credit-card"></i> Pay
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="11" class="text-center">No payroll records found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
