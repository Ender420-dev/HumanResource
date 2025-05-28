<?php
include '../Database/db.php';

// Fetch active employees
$employees = $conn->query("SELECT employee_id, CONCAT(first_name, ' ', last_name) AS full_name FROM employees WHERE status = 'Active'");
?>

<h2>Assign Salary to Employee</h2>
<form action="insert_salary.php" method="post">
    <!-- Employee Dropdown -->
    <label for="employee_id">Employee:</label>
    <select name="employee_id" id="employee_id" required>
        <option value="">Select Employee</option>
        <?php while ($emp = $employees->fetch_assoc()): ?>
            <option value="<?= $emp['employee_id'] ?>"><?= htmlspecialchars($emp['full_name']) ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <!-- Base Salary -->
    <label for="base_salary">Base Salary (₱):</label>
    <input type="number" name="base_salary" step="0.01" required><br><br>

    <!-- Effective Date -->
    <label for="effective_date">Effective Date:</label>
    <input type="date" name="effective_date" required><br><br>

    <!-- End Date (Optional) -->
    <label for="end_date">End Date (optional):</label>
    <input type="date" name="end_date"><br><br>

    <button type="submit">Assign Salary</button>
</form>