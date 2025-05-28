<?php
session_start();
ob_start(); // Start output buffering

// Page title
$title = "Claims for Approval (Hardcoded)";

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
            'department_id_allocated' => 10, // Allocated to HR dept
            'approval_comments' => null,
            'approved_by' => null,
            'approval_date' => null
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
            'department_id_allocated' => 20,
            'approval_comments' => 'Approved as per company medical policy.',
            'approved_by' => 103, // Charlie Brown (simulated approver)
            'approval_date' => '2024-05-06 09:00:00'
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
            'department_id_allocated' => 10,
            'approval_comments' => 'Not pre-approved as per office supplies policy.',
            'approved_by' => 103, // Charlie Brown (simulated approver)
            'approval_date' => '2024-05-16 11:00:00'
        ],
        [
            'claim_id' => 4,
            'employee_id' => 104,
            'claim_date' => '2024-05-20',
            'category_id' => 1,
            'description' => 'Taxi fare for client visit.',
            'amount' => 450.00,
            'status' => 'Pending',
            'submitted_at' => '2024-05-21 16:00:00',
            'document_path' => 'taxi_receipt_diana.png',
            'project_id' => 1001,
            'department_id_allocated' => 30,
            'approval_comments' => null,
            'approved_by' => null,
            'approval_date' => null
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


// --- Handle Form Submission (Simulated Approve/Reject) ---
$status_message = $_SESSION['status_message'] ?? null;
unset($_SESSION['status_message']); // Clear message after retrieving

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $claim_id = intval($_POST['claim_id']);
    $action = $_POST['action'] ?? '';
    $comments = trim($_POST['comments'] ?? '');

    $found_index = -1;
    foreach ($_SESSION['claims_data'] as $index => $claim) {
        if ($claim['claim_id'] == $claim_id) {
            $found_index = $index;
            break;
        }
    }

    if ($found_index !== -1 && $_SESSION['claims_data'][$found_index]['status'] === 'Pending') {
        $claimant_name = getEmployeeNameById($_SESSION['claims_data'][$found_index]['employee_id'], $employees_data);
        if ($action === 'approve') {
            $_SESSION['claims_data'][$found_index]['status'] = 'Approved';
            $_SESSION['claims_data'][$found_index]['approval_comments'] = $comments;
            $_SESSION['claims_data'][$found_index]['approved_by'] = 103; // Simulate Charlie Brown as approver
            $_SESSION['claims_data'][$found_index]['approval_date'] = date('Y-m-d H:i:s');
            $_SESSION['status_message'] = ['type' => 'success', 'message' => "Claim #{$claim_id} for {$claimant_name} has been simulated as **Approved**! (Changes are not persistent.)"];
        } elseif ($action === 'reject') {
            $_SESSION['claims_data'][$found_index]['status'] = 'Rejected';
            $_SESSION['claims_data'][$found_index]['approval_comments'] = $comments;
            $_SESSION['claims_data'][$found_index]['approved_by'] = 103; // Simulate Charlie Brown as approver
            $_SESSION['claims_data'][$found_index]['approval_date'] = date('Y-m-d H:i:s');
            $_SESSION['status_message'] = ['type' => 'danger', 'message' => "Claim #{$claim_id} for {$claimant_name} has been simulated as **Rejected**! (Changes are not persistent.)"];
        } else {
            $_SESSION['status_message'] = ['type' => 'warning', 'message' => 'Invalid action received.'];
        }
    } else {
        $_SESSION['status_message'] = ['type' => 'danger', 'message' => 'Claim not found or not in Pending status.'];
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Filter claims to show only 'Pending' ones for approval
$pending_claims = array_filter($_SESSION['claims_data'], function($claim) {
    return $claim['status'] === 'Pending';
});

?>

<div class="p-2 gap-3">
    <div class="d-flex col">
        <h6 class="text-muted pe-none mb-0">
            <a class="text-decoration-none text-muted" href="">Home</a> >
            <a class="text-decoration-none text-muted" href="claims_dashboard.php">Claims & Reimbursements</a> >
            <a class="text-decoration-none text-muted" href="claims_for_approval.php">Claims for Approval</a>
        </h6>
    </div>
    <hr>
    <div class="nav col-12 d-flex justify-content-around">
        <?php include('nav/claims/nav.php') ?>
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

        <div class="col d-flex flex-column p-4 border border-2 rounded-3">
            <h3 class="text-center">Pending Claims for Your Approval</h3>
            <hr>
            <?php if (empty($pending_claims)): ?>
                <p class="text-center text-muted">No pending claims requiring your approval.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover border text-center align-middle">
                        <thead>
                            <tr>
                                <th>Claim ID</th>
                                <th>Claimant</th>
                                <th>Date of Expense</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Submitted At</th>
                                <th>Description</th>
                                <th>Allocation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pending_claims as $claim): ?>
                                <tr>
                                    <td><?= htmlspecialchars($claim['claim_id']) ?></td>
                                    <td><?= getEmployeeNameById($claim['employee_id'], $employees_data) ?></td>
                                    <td><?= htmlspecialchars($claim['claim_date']) ?></td>
                                    <td><?= getCategoryNameById($claim['category_id'], $claim_categories) ?></td>
                                    <td>PHP <?= number_format($claim['amount'], 2) ?></td>
                                    <td><?= htmlspecialchars($claim['submitted_at']) ?></td>
                                    <td class="text-start" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <span title="<?= htmlspecialchars($claim['description']) ?>"><?= htmlspecialchars($claim['description']) ?></span>
                                    </td>
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
                                        <button type="button" class="btn btn-sm btn-success mb-1" data-bs-toggle="modal" data-bs-target="#approvalModal" data-claim-id="<?= $claim['claim_id'] ?>" data-action="approve">Approve</button>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#approvalModal" data-claim-id="<?= $claim['claim_id'] ?>" data-action="reject">Reject</button>
                                        <?php if ($claim['document_path']): ?>
                                            <button type="button" class="btn btn-sm btn-secondary mt-1" data-bs-toggle="modal" data-bs-target="#receiptModal" data-receipt-path="<?= htmlspecialchars($claim['document_path']) ?>">View Receipt</button>
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

<div class="modal fade" id="approvalModal" tabindex="-1" aria-labelledby="approvalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="approvalForm" method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="approvalModalLabel">Approve/Reject Claim</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="claim_id" id="modalClaimId">
                    <input type="hidden" name="action" id="modalAction">
                    <div class="mb-3">
                        <label for="comments" class="form-label">Comments (Optional)</label>
                        <textarea class="form-control" id="comments" name="comments" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="modalSubmitBtn">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel">Simulated Receipt View</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="text-muted">This is a simulated receipt view. In a real application, you would load the actual image or PDF here.</p>
                <div id="receiptContent">
                    <img src="https://via.placeholder.com/600x400?text=Simulated+Receipt" alt="Simulated Receipt" class="img-fluid border rounded">
                    <p class="mt-2">Filename: <span id="receiptFilename"></span></p>
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
    var approvalModal = document.getElementById('approvalModal');
    if (approvalModal) {
        approvalModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var claimId = button.getAttribute('data-claim-id');
            var action = button.getAttribute('data-action');

            var modalTitle = approvalModal.querySelector('.modal-title');
            var modalSubmitBtn = approvalModal.querySelector('#modalSubmitBtn');
            var modalClaimId = approvalModal.querySelector('#modalClaimId');
            var modalAction = approvalModal.querySelector('#modalAction');

            modalClaimId.value = claimId;
            modalAction.value = action;

            if (action === 'approve') {
                modalTitle.textContent = 'Approve Claim #' + claimId;
                modalSubmitBtn.textContent = 'Approve';
                modalSubmitBtn.classList.remove('btn-danger');
                modalSubmitBtn.classList.add('btn-success');
            } else if (action === 'reject') {
                modalTitle.textContent = 'Reject Claim #' + claimId;
                modalSubmitBtn.textContent = 'Reject';
                modalSubmitBtn.classList.remove('btn-success');
                modalSubmitBtn.classList.add('btn-danger');
            }
        });
    }

    var receiptModal = document.getElementById('receiptModal');
    if (receiptModal) {
        receiptModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var receiptPath = button.getAttribute('data-receipt-path');
            var receiptFilenameSpan = receiptModal.querySelector('#receiptFilename');

            // Display just the filename
            var filename = receiptPath.split('/').pop();
            receiptFilenameSpan.textContent = filename;

            // You could dynamically change the image source or embed PDF here if you had actual files.
            // For now, it just shows the placeholder.
        });
    }
});
</script>

<?php ob_end_flush(); ?>