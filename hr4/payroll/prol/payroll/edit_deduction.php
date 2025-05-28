<?php
include '../Database/db.php';

if (!isset($_GET['id'])) {
    die("Deduction ID is missing.");
}

$deduction_id = $_GET['id'];
$success = $error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $deduction_type = $_POST['deduction_type'];
    $amount = $_POST['amount'];
    $effective_date = $_POST['effective_date'];
    $end_date = $_POST['end_date'] ?? null;

    $stmt = $conn->prepare("
        UPDATE employee_deductions 
        SET deduction_id = ?, amount = ?, effective_date = ?, end_date = ? 
        WHERE employee_deduction_id = ?
    ");
    $stmt->bind_param("isdsi", $deduction_type, $amount, $effective_date, $end_date, $deduction_id);

    if ($stmt->execute()) {
        $success = "Deduction updated successfully.";
    } else {
        $error = "Error updating deduction: " . $stmt->error;
    }
}

// Get current deduction data
$sql = "
SELECT ed.*, d.deduction_name
FROM employee_deductions ed
JOIN deductions d ON d.deduction_id = ed.deduction_id
WHERE ed.employee_deduction_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $deduction_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Deduction not found.");
}

$deduction = $result->fetch_assoc();

// Get all deduction types for the dropdown
$deductions_result = $conn->query("SELECT * FROM deductions");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Edit Deduction</title>
    <style>
        .form-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 30px;
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
        }
        .form-control, .form-select {
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
        }
        .btn-submit {
            background-color: #3498db;
            border: none;
            padding: 10px 20px;
            width: 100%;
        }
        .btn-submit:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <aside>
        <?php include 'dashboard.php'; ?>
    </aside>

    <div class="main" style="margin-left: 500px; padding: 20px; margin-top: 60px;">
        <div class="container d-flex justify-content-center">
            <div class="form-container">
                <h2 class="form-header"><i class="fas fa-edit me-2"></i>Edit Deduction</h2>

                <?php if ($success): ?>
                    <div class="alert alert-success"> <?= $success ?> </div>
                <?php elseif ($error): ?>
                    <div class="alert alert-danger"> <?= $error ?> </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="deduction_type" class="form-label">Deduction Type</label>
                        <select name="deduction_type" id="deduction_type" class="form-select" required>
                            <?php while ($row = $deductions_result->fetch_assoc()): ?>
                                <option value="<?= $row['deduction_id'] ?>"
                                    <?= $row['deduction_id'] == $deduction['deduction_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($row['deduction_name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount (₱)</label>
                        <input type="number" step="0.01" name="amount" id="amount" 
                               class="form-control" value="<?= $deduction['amount'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="effective_date" class="form-label">Effective Date</label>
                        <input type="date" name="effective_date" id="effective_date" 
                               class="form-control" value="<?= $deduction['effective_date'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" 
                               value="<?= $deduction['end_date'] ?>">
                    </div>

                    <button type="submit" class="btn btn-primary btn-submit">
                        <i class="fas fa-save me-2"></i>Update Deduction
                    </button>
                    <a href="deduction.php" class="btn btn-secondary mt-2 w-100">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
