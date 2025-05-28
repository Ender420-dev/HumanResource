<?php
session_start();
ob_start(); // Start output buffering

// Page title for the admin.php layout
$title = "Reimbursement Status";

// Include your admin.php for common layout (header, footer, etc.)
include_once 'admin.php'; // Adjust path if necessary, assuming it's in the same directory

// --- Hardcoded Data (Simulating Database Tables) ---
// These are included here for demonstration. In a real application,
// these would typically come from a centralized configuration or database connection.

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
            'status' => 'Approved', // Changed to approved for demo purposes
            'submitted_at' => '2024-05-12 10:00:00',
            'document_path' => 'receipt_cebu_1.pdf', // Simulated path
            'project_id' => null, // Not allocated to project
            'department_id_allocated' => 10, // Allocated to HR dept
            'approval_comments' => 'Approved as per travel policy.',
            'approved_by' => 103,
            'approval_date' => '2024-05-13 11:00:00'
        ],
        [
            'claim_id' => 2,
            'employee_id' => 102,
            'claim_date' => '2024-05-01',
            'category_id' => 2,
            'description' => 'Consultation and medication for flu (simulated).',
            'amount' => 1500.00,
            'status' => 'Pending',
            'submitted_at' => '2024-05-05 14:30:00',
            'document_path' => 'medical_bill_bob.jpg',
            'project_id' => null,
            'department_id_allocated' => 20,
            'approval_comments' => null,
            'approved_by' => null,
            'approval_date' => null
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
            'approved_by' => 103,
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

// --- Status Message Handling (Optional, but good practice) ---
$status_message = $_SESSION['status_message'] ?? null;
unset($_SESSION['status_message']); // Clear message after retrieving

?>

<div class="p-2 gap-3">
    <div class="d-flex col">
        <h6 class="text-muted pe-none mb-0">
            <a class="text-decoration-none text-muted" href="">Home</a> >
            <a class="text-decoration-none text-muted" href="claims_dashboard.php">Claims & Reimbursements</a> >
            <a class="text-decoration-none text-muted" href="reimbursement_status.php">Reimbursement Status</a>
        </h6>
    </div>
    <hr>
    <div class="nav col-12 d-flex justify-content-around">
        <?php include('nav/claims/nav.php'); // Include the common claims navigation ?>
    </div>
    <hr>

    <div class="container-fluid shadow-lg col p-4">
        <?php
        // Display status messages here (if any from other pages redirected here)
        if ($status_message) {
            $msg_type = $status_message['type'];
            $msg_content = $status_message['message'];
            echo "<div class='alert alert-$msg_type alert-dismissible fade show' role='alert'>";
            echo $msg_content;
            echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
            echo "</div>";
        }
        ?>

        <div class="alert alert-info text-center" role="alert">
            <strong>Information:</strong> This page displays the status of all claims. The data is hardcoded and not persistent across server restarts.
        </div>

        <div class="col d-flex flex-column p-4 border border-2 rounded-3">
            <h3 class="text-center">All Claims Status</h3>
            <hr>
            <?php if (empty($_SESSION['claims_data'])): ?>
                <p class="text-center text-muted">No claims submitted yet.</p>
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
                                <th>Status</th>
                                <th>Submitted At</th>
                                <th>Approval Date</th>
                                <th>Approved/Rejected By</th>
                                <th>Comments</th>
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
                                    <td><?= htmlspecialchars($claim['approval_date'] ?? 'N/A') ?></td>
                                    <td><?= getEmployeeNameById($claim['approved_by'] ?? null, $employees_data) ?></td>
                                    <td class="text-start" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <span title="<?= htmlspecialchars($claim['approval_comments'] ?? 'No comments') ?>">
                                            <?= htmlspecialchars($claim['approval_comments'] ?? 'No comments') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#claimDetailsModal" data-claim-id="<?= $claim['claim_id'] ?>">Details</button>
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

<div class="modal fade" id="claimDetailsModal" tabindex="-1" aria-labelledby="claimDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="claimDetailsModalLabel">Claim Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="claimDetailsContent">
                    Loading claim details...
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
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
    var claimDetailsModal = document.getElementById('claimDetailsModal');
    if (claimDetailsModal) {
        claimDetailsModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var claimId = button.getAttribute('data-claim-id');
            var claimDetailsContent = claimDetailsModal.querySelector('#claimDetailsContent');

            // Find the claim data based on claimId from the PHP session data
            // In a real app, you'd make an AJAX call to fetch this data from the server/DB
            var claimsData = <?php echo json_encode($_SESSION['claims_data']); ?>;
            var claim = claimsData.find(c => c.claim_id == claimId); // Loose comparison as data-claim-id is string

            if (claim) {
                var employeeName = "<?= addslashes(getEmployeeNameById('__EMP_ID__', $employees_data)) ?>".replace('__EMP_ID__', claim.employee_id);
                var categoryName = "<?= addslashes(getCategoryNameById('__CAT_ID__', $claim_categories)) ?>".replace('__CAT_ID__', claim.category_id);
                var approvedBy = "<?= addslashes(getEmployeeNameById('__APPROVER_ID__', $employees_data)) ?>".replace('__APPROVER_ID__', claim.approved_by);
                var projectName = claim.project_id ? "<?= addslashes(getProjectNameById('__PROJ_ID__', $projects_data)) ?>".replace('__PROJ_ID__', claim.project_id) : 'N/A';
                var departmentName = claim.department_id_allocated ? "<?= addslashes(getDepartmentNameById('__DEPT_ID__', $departments_data)) ?>".replace('__DEPT_ID__', claim.department_id_allocated) : 'N/A';


                var statusClass = '';
                switch (claim.status) {
                    case 'Pending': statusClass = 'bg-warning'; break;
                    case 'Approved': statusClass = 'bg-success'; break;
                    case 'Rejected': statusClass = 'bg-danger'; break;
                    default: statusClass = 'bg-secondary'; break;
                }

                claimDetailsContent.innerHTML = `
                    <p><strong>Claim ID:</strong> ${claim.claim_id}</p>
                    <p><strong>Claimant:</strong> ${employeeName}</p>
                    <p><strong>Date of Expense:</strong> ${claim.claim_date}</p>
                    <p><strong>Category:</strong> ${categoryName}</p>
                    <p><strong>Amount:</strong> PHP ${parseFloat(claim.amount).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</p>
                    <p><strong>Status:</strong> <span class="badge ${statusClass}">${claim.status}</span></p>
                    <p><strong>Description:</strong> ${claim.description}</p>
                    <p><strong>Submitted At:</strong> ${claim.submitted_at}</p>
                    <p><strong>Allocated to Project:</strong> ${projectName}</p>
                    <p><strong>Allocated to Department:</strong> ${departmentName}</p>
                    <p><strong>Approval Date:</strong> ${claim.approval_date || 'N/A'}</p>
                    <p><strong>Approved/Rejected By:</strong> ${approvedBy || 'N/A'}</p>
                    <p><strong>Approval Comments:</strong> ${claim.approval_comments || 'No comments'}</p>
                    ${claim.document_path ? `<p><strong>Receipt:</strong> <a href="#" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#receiptModal" data-receipt-path="${claim.document_path}">View Receipt</a></p>` : '<p><strong>Receipt:</strong> Not available</p>'}
                `;
            } else {
                claimDetailsContent.innerHTML = '<p class="text-danger">Claim details not found.</p>';
            }
        });
    }

    // Receipt View Modal (re-used logic from claims_for_approval.php)
    var receiptModal = document.getElementById('receiptModal');
    if (receiptModal) {
        receiptModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var receiptPath = button.getAttribute('data-receipt-path');
            var receiptFilenameSpan = receiptModal.querySelector('#receiptFilename');

            // Display just the filename
            var filename = receiptPath.split('/').pop();
            receiptFilenameSpan.textContent = filename;

            // In a real application, you would load the actual image or PDF here dynamically.
            // For now, it just shows the placeholder.
        });
    }
});
</script>

<?php ob_end_flush(); ?>