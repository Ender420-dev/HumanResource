<?php
include '../Database/db.php';

// Add bank account when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_bank'])) {
    $employee_id = $_POST['employee_id'];
    $bank_name = $_POST['bank_name'];
    $account_number = $_POST['account_number'];
    $account_type = $_POST['account_type'];

    $stmt = $conn->prepare("INSERT INTO employee_bank_accounts (employee_id, bank_name, account_number, account_type) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $employee_id, $bank_name, $account_number, $account_type);
    
    if ($stmt->execute()) {
        $success = "Bank account added successfully.";
    } else {
        $error = "Failed to add bank account.";
    }
    $stmt->close();
}

// Get all employees
$employees = $conn->query("SELECT employee_id, first_name, last_name FROM employees WHERE status = 'Active'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Bank Account</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .main {
            max-width: 1000px;
            margin: auto;
            padding: 30px;
        }
        .card {
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
    </style>
</head>
<body>

<aside>
    <?php include 'dashboard.php'; ?>
</aside>

<div class="main" style="max-width: 1200px; width: 100%;">
    <div class="card p-4">
        <h2 class="mb-4 text-center">Add Bank Account</h2>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php elseif (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 1; while ($row = $employees->fetch_assoc()): ?>
                <tr>
                    <td><?= $count++ ?></td>
                    <td><?= htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']) ?></td>
                    <td>
                        <button 
                            class="btn btn-sm btn-success" 
                            data-bs-toggle="modal" 
                            data-bs-target="#addBankModal" 
                            onclick="fillEmployee(<?= $row['employee_id'] ?>, '<?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?>')"
                        >
                            Add Bank Account
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addBankModal" tabindex="-1" aria-labelledby="addBankModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content" >
        <div class="modal-header">
            <h5 class="modal-title">Add Bank Account for <span id="employeeNameModal"></span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <input type="hidden" name="employee_id" id="employeeIdInput">
        <div class="modal-body">
            <div class="mb-3">
                <label for="bank_name" class="form-label">Bank Name</label>
                <input type="text" class="form-control" name="bank_name" required>
            </div>
            <div class="mb-3">
                <label for="account_number" class="form-label">Account Number</label>
                <input type="text" class="form-control" name="account_number" required>
            </div>
            <div class="mb-3">
                <label for="account_type" class="form-label">Account Type</label>
                <select class="form-select" name="account_type" required>
                    <option value="" selected disabled>Select Type</option>
                    <option value="Checking">Checking</option>
                    <option value="Savings">Savings</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" name="add_bank" class="btn btn-primary">Save Bank Account</button>
        </div>
    </form>
  </div>
</div>

<script>
    function fillEmployee(id, name) {
        document.getElementById('employeeIdInput').value = id;
        document.getElementById('employeeNameModal').textContent = name;
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
