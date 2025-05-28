<?php
include '../Database/db.php';

// Fetch employees
$employees = $conn->query("SELECT employee_id, CONCAT(first_name, ' ', last_name) AS full_name FROM employees WHERE status = 'Active'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Add Shift Log</title>
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
        .time-inputs {
            display: flex;
            gap: 15px;
        }
        .time-input-group {
            flex: 1;
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
                <h2 class="form-header"><i class="fas fa-clock me-2"></i>Add Shift Log</h2>
                
                <form action="insert_shift_log.php" method="post">
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Employee</label>
                        <select class="form-select" id="employee_id" name="employee_id" required>
                            <option value="">Select Employee</option>
                            <?php while ($emp = $employees->fetch_assoc()): ?>
                                <option value="<?= $emp['employee_id'] ?>"><?= htmlspecialchars($emp['full_name']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="shift_date" class="form-label">Shift Date</label>
                        <input type="date" class="form-control" id="shift_date" name="shift_date" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Shift Time</label>
                        <div class="time-inputs">
                            <div class="time-input-group">
                                <label for="time_in" class="form-label">Time In</label>
                                <input type="time" class="form-control" id="time_in" name="time_in" required>
                            </div>
                            <div class="time-input-group">
                                <label for="time_out" class="form-label">Time Out</label>
                                <input type="time" class="form-control" id="time_out" name="time_out" required>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-submit">
                        <i class="fas fa-save me-2"></i>Add Shift
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set default date to today
        document.getElementById('shift_date').valueAsDate = new Date();
        
        // Set default times (8am-5pm)
        document.getElementById('time_in').value = '08:00';
        document.getElementById('time_out').value = '17:00';
    </script>
</body>
</html>