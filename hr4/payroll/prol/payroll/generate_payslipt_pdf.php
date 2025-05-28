<?php
require '../vendor/autoload.php';
include '../Database/db.php';

use Dompdf\Dompdf;

if (!isset($_GET['payment_id'])) {
    die("Missing payment_id");
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
    die("Payslip not found.");
}

$row = $result->fetch_assoc();

$html = "
    <h2 style='text-align:center;'>Employee Payslip</h2>
    <p><strong>Employee:</strong> {$row['first_name']} {$row['last_name']}</p>
    <p><strong>Employee ID:</strong> {$row['employee_id']}</p>
    <p><strong>Pay Period:</strong> {$row['pay_period']}</p>
    <p><strong>Payment Date:</strong> {$row['payment_date']}</p>
    <hr>
    <p><strong>Base Salary:</strong> ₱" . number_format($row['base_salary'], 2) . "</p>
    <p><strong>Total Deductions:</strong> ₱" . number_format($row['deductions'], 2) . "</p>
    <p><strong>Net Pay:</strong> ₱" . number_format($row['net_pay'], 2) . "</p>
    <hr>
    <p><strong>Bank Name:</strong> {$row['bank_name']}</p>
    <p><strong>Account Number:</strong> {$row['account_number']}</p>
    <p><strong>Account Type:</strong> {$row['account_type']}</p>
";

// Load dompdf and generate
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("payslip_{$row['employee_id']}.pdf", ["Attachment" => true]);
exit;
