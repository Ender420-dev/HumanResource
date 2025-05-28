<?php
include '../Database/db.php';

// Display success/error messages if they exist
if (isset($_GET['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            '.htmlspecialchars($_GET['success']).'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
} elseif (isset($_GET['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Error: ' . htmlspecialchars($_GET['error']) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
}

// Fetch employees and their total deductions
// $sql = "
// SELECT 
//     e.employee_id,
//     e.first_name,
//     e.last_name,
//     IFNULL(SUM(ed.amount), 0) AS total_deductions
// FROM employees e
// LEFT JOIN employee_deductions ed 
//     ON e.employee_id = ed.employee_id 
//     AND (ed.end_date IS NULL OR ed.end_date >= CURRENT_DATE())
// GROUP BY e.employee_id
// ";

$sql = "SELECT * FROM `employeeprofilesetup`";
$result = $hr1->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Employee Deductions</title>
    <style>
        .main {
            margin-left: 250px;
            padding: 20px;
        }
        .table-responsive {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            padding: 20px;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .action-cell {
            white-space: nowrap;
        }
        tr th {
            text-align: center;
        }
        .add-deduction-btn {
            margin-bottom: 20px;
        }
        .amount-cell {
            text-align: right;
            padding-right: 30px !important;
        }
        .deduction-details {
            background-color: #f8f9fa;
        }
        .deduction-table {
            margin-top: 10px;
            width: 100%;
        }
        .deduction-table th {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>
    <aside>
        <?php include 'dashboard.php'; ?>
    </aside>
    <div class="main" style="margin: 0; width: 100%;">
        <div class="container" style="width: 100%;">
            <h1>Employee Deductions</h1>
            
            <!-- Add Deduction Button -->
            <a href="assign_employee.php" class="btn btn-primary add-deduction-btn">
                <i class="fas fa-plus"></i> Add Deduction
            </a>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="">
                        <tr>
                            <th>Full Name</th>
                            <th class="amount-cell">Total Deductions (₱)</th>
                            <th style="width: 120px;">View Details</th>
                            <th style="width: 120px;">Add</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['FullName']) ?></td>
                                <td class="amount-cell">₱00.0<?php // number_format($row['total_deductions'], 2) ?></td>
                                <td class="action-cell">
                                    <button class="btn btn-sm btn-info view-details" data-employee-id="<?php $row['EmployeeID'] ?>">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                                <td class="action-cell">
                                    <a href="assign_employee_deduction.php?employee_id=<?= $row['EmployeeID'] ?>" class="btn btn-sm btn-success">
                                        <i class="fas fa-plus"></i> Add
                                    </a>
                                </td>
                            </tr>
                            <tr class="deduction-details" id="details-<?= $row['EmployeeID'] ?>" style="display:none;">
                                <td colspan="5">
                                    <table class="table deduction-table">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th class="amount-cell">Amount</th>
                                                <th>Effective</th>
                                                <th>End</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $emp_id = $row['EmployeeID'];
                                            $deduction_sql = "
                                                SELECT ed.employee_deduction_id, d.deduction_name, ed.amount, ed.effective_date, ed.end_date 
                                                FROM employee_deductions ed 
                                                JOIN deductions d ON d.deduction_id = ed.deduction_id
                                                WHERE ed.employee_id = $emp_id
                                            ";
                                            $deductions = $conn->query($deduction_sql);
                                            while ($deduction = $deductions->fetch_assoc()):
                                            ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($deduction['deduction_name']) ?></td>
                                                    <td class="amount-cell">₱<?= number_format($deduction['amount'], 2) ?></td>
                                                    <td><?= $deduction['effective_date'] ?></td>
                                                    <td><?= $deduction['end_date'] ?? 'Ongoing' ?></td>
                                                    <td>
                                                        <a href="edit_deduction.php?id=<?= $deduction['employee_deduction_id'] ?>" class="btn btn-sm btn-warning">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle click on view details buttons
        document.querySelectorAll('.view-details').forEach(button => {
            button.addEventListener('click', function() {
                const employeeId = this.getAttribute('data-employee-id');
                const detailsRow = document.getElementById('details-' + employeeId);
                
                // Toggle display
                if (detailsRow.style.display === 'none') {
                    detailsRow.style.display = '';
                    this.innerHTML = '<i class="fas fa-eye-slash"></i> Hide';
                } else {
                    detailsRow.style.display = 'none';
                    this.innerHTML = '<i class="fas fa-eye"></i> View';
                }
            });
        });
    });
    </script>
</body>
</html>