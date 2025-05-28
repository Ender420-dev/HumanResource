<?php
session_start();
ob_start(); // Start output buffering

// Page title
$title = "Claims & Reimbursements (Hardcoded)";

// Include your admin.php for common layout (header, footer, etc.)
include_once 'admin.php'; // Adjust path if necessary

// --- Hardcoded Data (Simulating Database Tables) ---
// This data is static and will not change based on UI actions.
// All "added" claims will only be visible until page refresh.

// Simulate a very basic Employees table (for claimant names)
$employees_data = [
    ['employee_id' => 101, 'first_name' => 'Alice', 'last_name' => 'Smith', 'department_id' => 10],
    ['employee_id' => 102, 'first_name' => 'Bob', 'last_name' => 'Johnson', 'department_id' => 20],
    ['employee_id' => 103, 'first_name' => 'Charlie', 'last_name' => 'Brown', 'department_id' => 10],
    ['employee_id' => 104, 'first_name' => 'Diana', 'last_name' => 'Prince', 'department_id' => 30],
];

// Simulate Departments table
$departments_data = [
    ['department_id' => 10, 'department_name' => 'Human Resources'],
    ['department_id' => 20, 'department_name' => 'Engineering'],
    ['department_id' => 30, 'department_name' => 'Marketing'],
];

// Simulate Claims Categories
$claim_categories = [
    ['category_id' => 1, 'category_name' => 'Travel Expense'],
    ['category_id' => 2, 'category_name' => 'Medical Reimbursement'],
    ['category_id' => 3, 'category_name' => 'Office Supplies'],
    ['category_id' => 4, 'category_name' => 'Training & Development'],
    ['category_id' => 5, 'category_name' => 'Utilities (Home Office)'],
];

// Simulate Projects (if applicable for allocation)
$projects_data = [
    ['project_id' => 1001, 'project_name' => 'Q2 Marketing Campaign'],
    ['project_id' => 1002, 'project_name' => 'HR System Migration'],
    ['project_id' => 1003, 'project_name' => 'Mobile App Feature X'],
];

// In a real app, this would be fetched from a database.
// For this simulation, we use a session variable to keep track for the current session,
// but it will reset if the server restarts or session is cleared.
if (!isset($_SESSION['claims_data'])) {
    $_SESSION['claims_data'] = [
        [
            'claim_id' => 1,
            'employee_id' => 101,
            'claim_date' => '2024-05-10',
            'category_id' => 1,
            'description' => 'Business trip to Cebu for client meeting (simulated flight).',
            'amount' => 12500.00,
            'status' => 'Pending',
            'submitted_at' => '2024-05-12 10:00:00',
            'document_path' => 'receipt_cebu_1.pdf', // Simulated path
            'project_id' => null, // Not allocated to project
            'department_id_allocated' => 10 // Allocated to HR dept
        ],
        [
            'claim_id' => 2,
            'employee_id' => 102,
            'claim_date' => '2024-05-01',
            'category_id' => 2,
            'description' => 'Consultation and medication for flu (simulated).',
            'amount' => 1500.00,
            'status' => 'Approved',
            'submitted_at' => '2024-05-05 14:30:00',
            'document_path' => 'medical_bill_bob.jpg',
            'project_id' => null,
            'department_id_allocated' => 20
        ],
        [
            'claim_id' => 3,
            'employee_id' => 101,
            'claim_date' => '2024-05-15',
            'category_id' => 3,
            'description' => 'New printer ink cartridges for office (simulated).',
            'amount' => 800.00,
            'status' => 'Rejected',
            'submitted_at' => '2024-05-16 09:00:00',
            'document_path' => null,
            'project_id' => null,
            'department_id_allocated' => 10
        ]
    ];
}

// Function to get employee name from ID
function getEmployeeNameById($employee_id, $employees_data) {
    foreach ($employees_data as $emp) {
        if ($emp['employee_id'] == $employee_id) {
            return htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']);
        }
    }
    return 'N/A';
}

// Function to get category name from ID
function getCategoryNameById($category_id, $claim_categories) {
    foreach ($claim_categories as $cat) {
        if ($cat['category_id'] == $category_id) {
            return htmlspecialchars($cat['category_name']);
        }
    }
    return 'N/A';
}

// Function to get department name from ID
function getDepartmentNameById($department_id, $departments_data) {
    foreach ($departments_data as $dept) {
        if ($dept['department_id'] == $department_id) {
            return htmlspecialchars($dept['department_name']);
        }
    }
    return 'N/A';
}

// Function to get project name from ID
function getProjectNameById($project_id, $projects_data) {
    foreach ($projects_data as $proj) {
        if ($proj['project_id'] == $project_id) {
            return htmlspecialchars($proj['project_name']);
        }
    }
    return 'N/A';
}

// --- Handle Form Submission (Simulated Add Claim) ---
$status_message = $_SESSION['status_message'] ?? null;
unset($_SESSION['status_message']); // Clear message after retrieving

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_claim'])) {
        $employee_id = intval($_POST['employee_id']);
        $claim_date = trim($_POST['claim_date']);
        $category_id = intval($_POST['category_id']);
        $description = trim($_POST['description']);
        $amount = floatval($_POST['amount']);
        $project_id = !empty($_POST['project_id']) ? intval($_POST['project_id']) : null;
        $department_id_allocated = !empty($_POST['department_id_allocated']) ? intval($_POST['department_id_allocated']) : null;
        $status = 'Pending'; // Default status for new claims
        $submitted_at = date('Y-m-d H:i:s');
        $document_path = 'simulated_receipt_' . uniqid() . '.pdf'; // Simulate a unique document path

        // Basic validation
        if (empty($employee_id) || empty($claim_date) || empty($category_id) || empty($description) || $amount <= 0) {
            $_SESSION['status_message'] = ['type' => 'danger', 'message' => 'Please fill in all required fields and ensure amount is positive.'];
        } else {
            // Simulate generating a new claim ID
            $new_claim_id = count($_SESSION['claims_data']) > 0 ? max(array_column($_SESSION['claims_data'], 'claim_id')) + 1 : 1;

            $new_claim = [
                'claim_id' => $new_claim_id,
                'employee_id' => $employee_id,
                'claim_date' => $claim_date,
                'category_id' => $category_id,
                'description' => $description,
                'amount' => $amount,
                'status' => $status,
                'submitted_at' => $submitted_at,
                'document_path' => $document_path,
                'project_id' => $project_id,
                'department_id_allocated' => $department_id_allocated,
            ];

            // Add the new claim to the session data (this simulates adding to DB but is not persistent across server restarts)
            $_SESSION['claims_data'][] = $new_claim;

            $_SESSION['status_message'] = ['type' => 'success', 'message' => 'Claim submitted successfully! (Changes are not persistent.)'];
        }
    }
    // Redirect to clear POST data and show message
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

?>

<div class="p-2 gap-3">
    <div class="d-flex col">
        <h6 class="text-muted pe-none mb-0">
            <a class="text-decoration-none text-muted" href="">Home</a> >
            <a class="text-decoration-none text-muted" href="claims_dashboard.php">Claims & Reimbursements</a>
        </h6>
    </div>
    <hr>
    <div class="nav col-12 d-flex justify-content-around">
        <?php include('nav/claims/nav.php'); ?>
    </div>
    <hr>

    <div class="container-fluid shadow-lg col p-4">
        <?php
        // Display status messages here
        if ($status_message) {
            $msg_type = $status_message['type'];
            $msg_content = $status_message['message'];
            echo "<div class='alert alert-$msg_type alert-dismissible fade show' role='alert'>";
            echo $msg_content;
            echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
            echo "</div>";
        }
        ?>

        <div class="col d-flex flex-column p-4 border border-2 rounded-3 mb-4">
            <h3 class="text-center">Submit New Expense Claim</h3>
            <hr>
            <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="employee_id" class="form-label">Claimant Employee</label>
                        <select class="form-select" id="employee_id" name="employee_id" required>
                            <option value="">Select Employee</option>
                            <?php foreach ($employees_data as $emp): ?>
                                <option value="<?= htmlspecialchars($emp['employee_id']) ?>">
                                    <?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="claim_date" class="form-label">Date of Expense</label>
                        <input type="date" class="form-control" id="claim_date" name="claim_date" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="category_id" class="form-label">Claim Category</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($claim_categories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat['category_id']) ?>">
                                    <?= htmlspecialchars($cat['category_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="amount" class="form-label">Amount (PHP)</label>
                        <input type="number" step="0.01" class="form-control" id="amount" name="amount" placeholder="e.g., 1500.50" required min="0.01">
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label">Description / Reason for Claim</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="project_id" class="form-label">Allocate to Project (Optional)</label>
                        <select class="form-select" id="project_id" name="project_id">
                            <option value="">-- None --</option>
                            <?php foreach ($projects_data as $proj): ?>
                                <option value="<?= htmlspecialchars($proj['project_id']) ?>">
                                    <?= htmlspecialchars($proj['project_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="department_id_allocated" class="form-label">Allocate to Department (Optional)</label>
                        <select class="form-select" id="department_id_allocated" name="department_id_allocated">
                            <option value="">-- None --</option>
                            <?php foreach ($departments_data as $dept): ?>
                                <option value="<?= htmlspecialchars($dept['department_id']) ?>">
                                    <?= htmlspecialchars($dept['department_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="receiptUpload" class="form-label">Receipt Upload (Simulated)</label>
                        <input class="form-control" type="file" id="receiptUpload" name="receipt_file" disabled>
                        <div class="form-text">File upload is simulated; no actual file will be stored.</div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" name="submit_claim" class="btn btn-primary">Submit Claim</button>
                        <button type="reset" class="btn btn-secondary">Reset Form</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col d-flex flex-column p-4 border border-2 rounded-3">
            <h3 class="text-center">My Submitted Claims History</h3>
            <hr>
            <?php if (empty($_SESSION['claims_data'])): ?>
                <p class="text-center text-muted">No claims submitted yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover border text-center">
                        <thead>
                            <tr>
                                <th>Claim ID</th>
                                <th>Claimant</th>
                                <th>Date of Expense</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Submitted At</th>
                                <th>Allocation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_reverse($_SESSION['claims_data']) as $claim): // Show newest first ?>
                                <tr>
                                    <td><?= htmlspecialchars($claim['claim_id']) ?></td>
                                    <td><?= getEmployeeNameById($claim['employee_id'], $employees_data) ?></td>
                                    <td><?= htmlspecialchars($claim['claim_date']) ?></td>
                                    <td><?= getCategoryNameById($claim['category_id'], $claim_categories) ?></td>
                                    <td>PHP <?= number_format($claim['amount'], 2) ?></td>
                                    <td>
                                        <?php
                                        $status_class = '';
                                        switch ($claim['status']) {
                                            case 'Pending': $status_class = 'bg-warning'; break;
                                            case 'Approved': $status_class = 'bg-success'; break;
                                            case 'Rejected': $status_class = 'bg-danger'; break;
                                            default: $status_class = 'bg-secondary'; break;
                                        }
                                        ?>
                                        <span class="badge <?= $status_class ?>"><?= htmlspecialchars($claim['status']) ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($claim['submitted_at']) ?></td>
                                    <td>
                                        <?php
                                            $allocation_info = [];
                                            if (!is_null($claim['project_id'])) {
                                                $allocation_info[] = 'Project: ' . getProjectNameById($claim['project_id'], $projects_data);
                                            }
                                            if (!is_null($claim['department_id_allocated'])) {
                                                $allocation_info[] = 'Dept: ' . getDepartmentNameById($claim['department_id_allocated'], $departments_data);
                                            }
                                            echo empty($allocation_info) ? 'None' : implode('<br>', $allocation_info);
                                        ?>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-info" title="View Details">View</a>
                                        <?php if ($claim['status'] == 'Pending'): ?>
                                            <button type="button" class="btn btn-sm btn-secondary" disabled title="Cannot edit after submission">Edit</button>
                                            <button type="button" class="btn btn-sm btn-danger" disabled title="Cannot cancel after submission">Cancel</button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-sm btn-secondary" disabled>Edit</button>
                                            <button type="button" class="btn btn-sm btn-danger" disabled>Cancel</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php ob_end_flush(); ?>