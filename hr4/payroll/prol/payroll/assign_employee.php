<?php
include '../Database/db.php';

// Fetch employees
$employees = $conn->query("SELECT employee_id, CONCAT(first_name, ' ', last_name) AS full_name FROM employees WHERE status = 'Active'");

// Deduction Types (static list)
$deductionTypes = ['Tax', 'Insurance', 'Loan', 'SSS', 'PhilHealth', 'Pag-IBIG', 'Other'];
?>

<h2>Assign Deduction to Employee</h2>
<form action="insert_employee_deduction.php" method="post">
    <!-- Employee Dropdown -->
    <label for="employee_id">Employee:</label>
    <select name="employee_id" id="employee_id" required>
        <option value="">Select Employee</option>
        <?php while ($emp = $employees->fetch_assoc()): ?>
            <option value="<?= $emp['employee_id'] ?>"><?= htmlspecialchars($emp['full_name']) ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <!-- Deduction Type Dropdown -->
    <label for="deduction_type">Deduction Type:</label>
    <select name="deduction_type" id="deduction_type" required>
        <option value="">Select Deduction Type</option>
        <?php foreach ($deductionTypes as $type): ?>
            <option value="<?= $type ?>"><?= $type ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <!-- Amount -->
    <label for="amount">Amount (₱):</label>
    <input type="number" name="amount" step="0.01" required><br><br>

    <!-- Effective Date -->
    <label for="effective_date">Effective Date:</label>
    <input type="date" name="effective_date" required><br><br>

    <button type="submit">Assign Deduction</button>
</form>
