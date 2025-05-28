<?php
include '../Database/db.php';

try {
    // Mark all unpaid shifts in the current month as paid
    $update = "
        UPDATE shift_logs
        SET is_paid = 1
        WHERE is_paid = 0
        AND MONTH(shift_date) = MONTH(CURRENT_DATE())
        AND YEAR(shift_date) = YEAR(CURRENT_DATE())
    ";

    if (mysqli_query($conn, $update)) {
        header("Location: payroll_processing.php?success=Payroll processed successfully and shift logs updated.");
    } else {
        throw new Exception("Error updating shift logs: " . mysqli_error($conn));
    }
} catch (Exception $e) {
    header("Location: payroll_processing.php?error=" . urlencode($e->getMessage()));
}

mysqli_close($conn);
?>
