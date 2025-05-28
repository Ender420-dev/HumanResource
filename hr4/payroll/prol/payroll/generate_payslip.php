<?php
include '../Database/db.php';

if (!isset($_GET['payment_id'])) {
    echo "<p style='color:red;'>No payment ID provided.</p>";
    exit;
}

$payment_id = $_GET['payment_id'];

$sql = "
SELECT 
    p.payment_id,
    p.employee_id,
    e.first_name,
    e.last_name,
    sr.base_salary,
    p.amount AS net_pay,
    p.deductions,
    p.pay_period,
    p.payment_date,
    ba.bank_name,
    ba.account_number,
    ba.account_type
FROM payments p
JOIN employees e ON e.employee_id = p.employee_id
LEFT JOIN salary_records sr ON sr.employee_id = e.employee_id
    AND sr.effective_date = (
        SELECT MAX(effective_date) FROM salary_records WHERE employee_id = e.employee_id
    )
LEFT JOIN employee_bank_accounts ba ON ba.employee_id = e.employee_id
WHERE p.payment_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $payment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p style='color:red;'>Payslip not found.</p>";
    exit;
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payslip</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .payslip-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="payslip-container">
    <h2>Payslip</h2>

    <p><strong>Employee:</strong> <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></p>
    <p><strong>Employee ID:</strong> <?= htmlspecialchars($row['employee_id']) ?></p>
    <p><strong>Pay Period:</strong> <?= htmlspecialchars($row['pay_period']) ?></p>
    <p><strong>Payment Date:</strong> <?= htmlspecialchars($row['payment_date']) ?></p>

    <hr>

    <p><strong>Base Salary:</strong> ₱<?= number_format($row['base_salary'], 2) ?></p>
    <p><strong>Total Deductions:</strong> ₱<?= number_format($row['deductions'], 2) ?></p>
    <p><strong>Net Pay:</strong> ₱<?= number_format($row['net_pay'], 2) ?></p>

    <hr>

    <p><strong>Bank Name:</strong> <?= htmlspecialchars($row['bank_name'] ?? 'N/A') ?></p>
    <p><strong>Account Number:</strong> <?= htmlspecialchars($row['account_number'] ?? 'N/A') ?></p>
    <p><strong>Account Type:</strong> <?= htmlspecialchars($row['account_type'] ?? 'N/A') ?></p>

    <hr>

    <!-- <div class="text-center mt-4">
        <a href="payment_history.php" class="btn btn-secondary">← Back to History</a>
        <button onclick="window.print()" class="btn btn-primary">🖨️ Print Payslip</button>
        <a href="generate_payslip_pdf.php?payment_id=<?= $row['payment_id'] ?>" class="btn btn-sm btn-danger">
    🧾 Download PDF
</a>

    </div> -->
    <div class="text-center mt-4">
    <a href="payment_history.php" class="btn btn-secondary">
        &larr; Back to History
    </a>

    <button onclick="window.print()" class="btn btn-primary">
        🖨️ Print Payslip
    </button>

    <a href="generate_payslip_pdf.php?payment_id=<?= htmlspecialchars($row['payment_id']) ?>" class="btn btn-danger">
        🧾 Download PDF
    </a>
</div>

</div>

</body>
</html>

<?php $conn->close(); ?>
