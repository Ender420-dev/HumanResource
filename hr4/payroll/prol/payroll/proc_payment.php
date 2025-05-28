<?php
include '../Database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];
    $gross_pay = $_POST['gross_pay'];
    $net_pay = $_POST['net_pay'];
    $deductions = $_POST['deductions'];
    $pay_period = $_POST['pay_period']; // e.g., 2025-05
    $paid_date = date('Y-m-d');

    // 1. Insert payment record
    $insert = "INSERT INTO payroll_payments 
        (employee_id, gross_pay, deductions, net_pay, paid_date, pay_period) 
        VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($insert);
    $stmt->bind_param("idddds", $employee_id, $gross_pay, $deductions, $net_pay, $paid_date, $pay_period);
    
    if ($stmt->execute()) {
        // 2. Mark shift logs as paid
        $update = "UPDATE shift_logs 
                   SET is_paid = 1 
                   WHERE employee_id = ? 
                     AND MONTH(shift_date) = MONTH(CURRENT_DATE())
                     AND YEAR(shift_date) = YEAR(CURRENT_DATE()) 
                     AND is_paid = 0";
        $stmt2 = $conn->prepare($update);
        $stmt2->bind_param("i", $employee_id);
        $stmt2->execute();

        header("Location: payroll_processing.php?success=Payment recorded for employee ID $employee_id");
        exit;
    } else {
        header("Location: payroll_processing.php?error=Failed to process payment.");
        exit;
    }
}
?>
