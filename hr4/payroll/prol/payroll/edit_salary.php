<?php
include '../Database/db.php';

$employee_id = $_GET['employee_id'] ?? null;
if (!$employee_id) {
    die("Employee ID is required.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $base_salary = $_POST['base_salary'];
    $effective_date = $_POST['effective_date'];
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;

    // Update the most recent salary record
    $sql = "UPDATE salary_records 
            SET base_salary = ?, effective_date = ?, end_date = ?
            WHERE employee_id = ? 
            ORDER BY effective_date DESC 
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ddsi", $base_salary, $effective_date, $end_date, $employee_id);
    
    if ($stmt->execute()) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Salary updated successfully
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error updating salary: ' . htmlspecialchars($conn->error) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }

    $stmt->close();
}

// Fetch current salary info
$sql = "SELECT * FROM salary_records 
        WHERE employee_id = ? 
        ORDER BY effective_date DESC 
        LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$salary = $result->fetch_assoc();
$stmt->close();

// Get employee name for display
$employee_sql = "SELECT CONCAT(first_name, ' ', last_name) AS full_name FROM employees WHERE employee_id = ?";
$employee_stmt = $conn->prepare($employee_sql);
$employee_stmt->bind_param("i", $employee_id);
$employee_stmt->execute();
$employee_result = $employee_stmt->get_result();
$employee = $employee_result->fetch_assoc();
$employee_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Edit Salary</title>
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
        .form-control {
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
        }
        .form-control:focus {
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
        .btn-back {
            background-color: #6c757d;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            margin-top: 10px;
            width: 100%;
        }
        .btn-back:hover {
            background-color: #5a6268;
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
                <h2 class="form-header"><i class="fas fa-money-bill-wave me-2"></i>Edit Salary</h2>
                <p class="text-muted mb-4">Employee: <strong><?= htmlspecialchars($employee['full_name']) ?></strong></p>
                
                <form method="post">
                    <div class="mb-3">
                        <label for="base_salary" class="form-label">Base Salary</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" class="form-control" id="base_salary" name="base_salary" 
                                   step="0.01" min="0" required 
                                   value="<?= htmlspecialchars($salary['base_salary']) ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="effective_date" class="form-label">Effective Date</label>
                                <input type="date" class="form-control" id="effective_date" name="effective_date" 
                                       required value="<?= htmlspecialchars($salary['effective_date']) ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date (optional)</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="<?= htmlspecialchars($salary['end_date'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-submit">
                        <i class="fas fa-save me-2"></i>Update Salary
                    </button>
                    
                    <a href="payroll_processing.php" class="btn btn-secondary btn-back">
                        <i class="fas fa-arrow-left me-2"></i>Back to Payroll
                    </a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set minimum date for effective date to today
        document.getElementById('effective_date').min = new Date().toISOString().split('T')[0];
        
        // Set minimum date for end date to effective date
        document.getElementById('effective_date').addEventListener('change', function() {
            document.getElementById('end_date').min = this.value;
        });
    </script>
</body>
</html>