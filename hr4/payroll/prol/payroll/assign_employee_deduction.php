<?php
include '../Database/db.php';

// Fetch employees
$employees = $conn->query("SELECT employee_id, CONCAT(first_name, ' ', last_name) AS full_name FROM employees WHERE status = 'Active'");

// Deduction Types (static list)
$deductionTypes = ['Tax', 'Insurance', 'Loan', 'SSS', 'PhilHealth', 'Pag-IBIG', 'Other'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Assign Deduction</title>
    <style>
        .form-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 20px;
            max-width: 600px;
        }
        .form-header {
            color: #2c3e50;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 5px;
        }
        .form-control, .form-select {
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }
        .btn-submit {
            background-color: #3498db;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            margin-top: 10px;
            width: 100%;
        }
        .btn-submit:hover {
            background-color: #2980b9;
        }
        .input-group-text {
            background-color: #e9ecef;
            border: 1px solid #ced4da;
        }
    </style>
</head>
<body>
    <aside>
        <?php include 'dashboard.php'; ?>
    </aside>
    
    <div class="main" style="margin-left: 500px; padding: 20px; margin-top: 100px;">
        <div class="container d-flex justify-content-center">
            <div class="form-container">
                <h2 class="form-header"><i class="fas fa-minus-circle me-2"></i>Assign Deduction</h2>
                
                <form action="insert_employee_deduction.php" method="post">
                    <!-- Employee Dropdown -->
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Employee</label>
                        <select class="form-select" id="employee_id" name="employee_id" required>
                            <option value="">Select Employee</option>
                            <?php while ($emp = $employees->fetch_assoc()): ?>
                                <option value="<?= $emp['employee_id'] ?>"><?= htmlspecialchars($emp['full_name']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <!-- Deduction Type Dropdown -->
                    <div class="mb-3">
                        <label for="deduction_type" class="form-label">Deduction Type</label>
                        <select class="form-select" id="deduction_type" name="deduction_type" required>
                            <option value="">Select Deduction Type</option>
                            <?php foreach ($deductionTypes as $type): ?>
                                <option value="<?= $type ?>"><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Amount -->
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" class="form-control" id="amount" name="amount" 
                                   step="0.01" min="0" required style="margin-bottom: 0;">
                        </div>
                    </div>
                    
                    <!-- Effective Date -->
                    <div class="mb-3">
                        <label for="effective_date" class="form-label">Effective Date</label>
                        <input type="date" class="form-control" id="effective_date" name="effective_date" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-submit">
                        <i class="fas fa-check-circle me-2"></i>Assign Deduction
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set default date to today
        document.getElementById('effective_date').valueAsDate = new Date();
        
        // Set minimum date to today
        document.getElementById('effective_date').min = new Date().toISOString().split('T')[0];
    </script>
</body>
</html>