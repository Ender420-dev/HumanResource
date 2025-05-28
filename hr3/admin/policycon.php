<?php
session_start();
ob_start(); // Start output buffering

// Page title for the admin.php layout
$title = "Policy Configuration";

// Include your admin.php for common layout (header, footer, etc.)
include_once 'admin.php'; // Adjust path if necessary

// --- Hardcoded Data (Simulating Database Tables) ---
// These are included here for demonstration. In a real application,
// these would typically come from a centralized configuration or database connection.

// Simulate Claims Categories (from claims_dashboard.php)
$claim_categories = [
    ['category_id' => 1, 'category_name' => 'Travel Expense'],
    ['category_id' => 2, 'category_name' => 'Medical Reimbursement'],
    ['category_id' => 3, 'category_name' => 'Office Supplies'],
    ['category_id' => 4, 'category_name' => 'Training & Development'],
    ['category_id' => 5, 'category_name' => 'Utilities (Home Office)'],
];

// Simulate a very basic Employees table (for audit trail if needed, but not heavily used here)
$employees_data = [
    ['employee_id' => 101, 'first_name' => 'Alice', 'last_name' => 'Smith', 'department_id' => 10],
    ['employee_id' => 102, 'first_name' => 'Bob', 'last_name' => 'Johnson', 'department_id' => 20],
    ['employee_id' => 103, 'first_name' => 'Charlie', 'last_name' => 'Brown', 'department_id' => 10],
];

// In a real app, policies would be fetched from a database.
// For this simulation, we use a session variable.
if (!isset($_SESSION['policies_data'])) {
    $_SESSION['policies_data'] = [
        [
            'policy_id' => 1,
            'policy_name' => 'Standard Travel Policy',
            'category_id' => 1, // Travel Expense
            'limit_type' => 'Per Claim',
            'amount_limit' => 15000.00,
            'description' => 'Covers flights, accommodation, and per diems for official business trips.',
            'effective_date' => '2024-01-01',
            'status' => 'Active',
            'last_updated' => '2024-04-10 10:00:00'
        ],
        [
            'policy_id' => 2,
            'policy_name' => 'Medical Reimbursement Policy',
            'category_id' => 2, // Medical Reimbursement
            'limit_type' => 'Annual',
            'amount_limit' => 20000.00,
            'description' => 'Annual limit for medical consultation, prescription, and minor procedures.',
            'effective_date' => '2023-07-01',
            'status' => 'Active',
            'last_updated' => '2023-06-20 15:30:00'
        ],
        [
            'policy_id' => 3,
            'policy_name' => 'Office Supplies Procurement Policy',
            'category_id' => 3, // Office Supplies
            'limit_type' => 'Monthly',
            'amount_limit' => 1000.00,
            'description' => 'Monthly allowance for approved office supplies purchases for home office setup.',
            'effective_date' => '2024-03-01',
            'status' => 'Active',
            'last_updated' => '2024-02-28 09:00:00'
        ]
    ];
}

// Function to get category name from ID (re-used)
function getCategoryNameById($category_id, $claim_categories) {
    foreach ($claim_categories as $cat) {
        if ($cat['category_id'] == $category_id) {
            return htmlspecialchars($cat['category_name']);
        }
    }
    return 'N/A';
}

// Function to get employee name from ID (re-used)
function getEmployeeNameById($employee_id, $employees_data) {
    foreach ($employees_data as $emp) {
        if ($emp['employee_id'] == $employee_id) {
            return htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']);
        }
    }
    return 'N/A';
}


// --- Handle Form Submission (Simulated Add Policy) ---
$status_message = $_SESSION['status_message'] ?? null;
unset($_SESSION['status_message']); // Clear message after retrieving

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_policy'])) {
        $policy_name = trim($_POST['policy_name']);
        $category_id = intval($_POST['category_id']);
        $limit_type = trim($_POST['limit_type']);
        $amount_limit = floatval($_POST['amount_limit']);
        $description = trim($_POST['description']);
        $effective_date = trim($_POST['effective_date']);
        $status = trim($_POST['status']);

        // Basic validation
        if (empty($policy_name) || empty($category_id) || empty($limit_type) || $amount_limit <= 0 || empty($description) || empty($effective_date) || empty($status)) {
            $_SESSION['status_message'] = ['type' => 'danger', 'message' => 'Please fill in all required fields and ensure amount limit is positive.'];
        } else {
            // Simulate generating a new policy ID
            $new_policy_id = count($_SESSION['policies_data']) > 0 ? max(array_column($_SESSION['policies_data'], 'policy_id')) + 1 : 1;

            $new_policy = [
                'policy_id' => $new_policy_id,
                'policy_name' => $policy_name,
                'category_id' => $category_id,
                'limit_type' => $limit_type,
                'amount_limit' => $amount_limit,
                'description' => $description,
                'effective_date' => $effective_date,
                'status' => $status,
                'last_updated' => date('Y-m-d H:i:s')
            ];

            $_SESSION['policies_data'][] = $new_policy;

            $_SESSION['status_message'] = ['type' => 'success', 'message' => 'Policy added successfully! (Changes are not persistent.)'];
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
            <a class="text-decoration-none text-muted" href="claims_dashboard.php">Claims & Reimbursements</a> >
            <a class="text-decoration-none text-muted" href="policy_configuration.php">Policy Configuration</a>
        </h6>
    </div>
    <hr>
    <div class="nav col-12 d-flex justify-content-around">
        <?php include('nav/claims/nav.php'); // Include the common claims navigation ?>
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
            <h3 class="text-center">Add New Reimbursement Policy</h3>
            <hr>
            <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="policy_name" class="form-label">Policy Name</label>
                        <input type="text" class="form-control" id="policy_name" name="policy_name" required placeholder="e.g., Travel Policy, Medical Max">
                    </div>
                    <div class="col-md-6">
                        <label for="category_id" class="form-label">Applies to Category</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($claim_categories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat['category_id']) ?>">
                                    <?= htmlspecialchars($cat['category_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="limit_type" class="form-label">Limit Type</label>
                        <select class="form-select" id="limit_type" name="limit_type" required>
                            <option value="">Select Type</option>
                            <option value="Per Claim">Per Claim</option>
                            <option value="Daily">Daily</option>
                            <option value="Monthly">Monthly</option>
                            <option value="Annual">Annual</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="amount_limit" class="form-label">Amount Limit (PHP)</label>
                        <input type="number" step="0.01" class="form-control" id="amount_limit" name="amount_limit" placeholder="e.g., 5000.00" required min="0.01">
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label">Policy Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="effective_date" class="form-label">Effective Date</label>
                        <input type="date" class="form-control" id="effective_date" name="effective_date" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" name="add_policy" class="btn btn-primary">Add Policy</button>
                        <button type="reset" class="btn btn-secondary">Reset Form</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col d-flex flex-column p-4 border border-2 rounded-3">
            <h3 class="text-center">Existing Reimbursement Policies</h3>
            <hr>
            <?php if (empty($_SESSION['policies_data'])): ?>
                <p class="text-center text-muted">No policies configured yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover border text-center align-middle">
                        <thead>
                            <tr>
                                <th>Policy ID</th>
                                <th>Policy Name</th>
                                <th>Category</th>
                                <th>Limit Type</th>
                                <th>Amount Limit</th>
                                <th>Status</th>
                                <th>Effective Date</th>
                                <th>Last Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_reverse($_SESSION['policies_data']) as $policy): // Show newest first ?>
                                <tr>
                                    <td><?= htmlspecialchars($policy['policy_id']) ?></td>
                                    <td><?= htmlspecialchars($policy['policy_name']) ?></td>
                                    <td><?= getCategoryNameById($policy['category_id'], $claim_categories) ?></td>
                                    <td><?= htmlspecialchars($policy['limit_type']) ?></td>
                                    <td>PHP <?= number_format($policy['amount_limit'], 2) ?></td>
                                    <td>
                                        <?php
                                        $status_class = ($policy['status'] == 'Active') ? 'bg-success' : 'bg-secondary';
                                        ?>
                                        <span class="badge <?= $status_class ?>"><?= htmlspecialchars($policy['status']) ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($policy['effective_date']) ?></td>
                                    <td><?= htmlspecialchars($policy['last_updated']) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#policyDetailsModal" data-policy-id="<?= $policy['policy_id'] ?>">View Details</button>
                                        <button type="button" class="btn btn-sm btn-secondary" disabled title="Edit not implemented in simulation">Edit</button>
                                        <button type="button" class="btn btn-sm btn-danger" disabled title="Delete not implemented in simulation">Delete</button>
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

<div class="modal fade" id="policyDetailsModal" tabindex="-1" aria-labelledby="policyDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="policyDetailsModalLabel">Policy Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="policyDetailsContent">
                    Loading policy details...
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var policyDetailsModal = document.getElementById('policyDetailsModal');
    if (policyDetailsModal) {
        policyDetailsModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var policyId = button.getAttribute('data-policy-id');
            var policyDetailsContent = policyDetailsModal.querySelector('#policyDetailsContent');

            // Find the policy data based on policyId from the PHP session data
            var policiesData = <?php echo json_encode($_SESSION['policies_data']); ?>;
            var policy = policiesData.find(p => p.policy_id == policyId);

            if (policy) {
                var categoryName = "<?= addslashes(getCategoryNameById('__CAT_ID__', $claim_categories)) ?>".replace('__CAT_ID__', policy.category_id);
                var statusClass = (policy.status == 'Active') ? 'bg-success' : 'bg-secondary';

                policyDetailsContent.innerHTML = `
                    <p><strong>Policy ID:</strong> ${policy.policy_id}</p>
                    <p><strong>Policy Name:</strong> ${policy.policy_name}</p>
                    <p><strong>Applies to Category:</strong> ${categoryName}</p>
                    <p><strong>Limit Type:</strong> ${policy.limit_type}</p>
                    <p><strong>Amount Limit:</strong> PHP ${parseFloat(policy.amount_limit).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</p>
                    <p><strong>Status:</strong> <span class="badge ${statusClass}">${policy.status}</span></p>
                    <p><strong>Effective Date:</strong> ${policy.effective_date}</p>
                    <p><strong>Description:</strong> ${policy.description}</p>
                    <p><strong>Last Updated:</strong> ${policy.last_updated}</p>
                `;
            } else {
                policyDetailsContent.innerHTML = '<p class="text-danger">Policy details not found.</p>';
            }
        });
    }
});
</script>

<?php ob_end_flush(); ?>