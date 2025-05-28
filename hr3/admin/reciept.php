<?php
session_start();
ob_start(); // Start output buffering

// Page title for the admin.php layout
$title = "Receipt Management";

// Include your admin.php for common layout (header, footer, etc.)
include_once 'admin.php'; // Adjust path if necessary

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

// In a real app, this would be fetched from a database.
// For this simulation, we use a session variable to keep track for the current session,
// but it will reset if the server restarts or session is cleared.

// Initialize claims_data if not set (from other pages)
if (!isset($_SESSION['claims_data'])) {
    $_SESSION['claims_data'] = [
        [
            'claim_id' => 1, 'employee_id' => 101, 'claim_date' => '2024-05-10', 'category_id' => 1,
            'description' => 'Business trip to Cebu for client meeting (simulated flight).',
            'amount' => 12500.00, 'status' => 'Approved', 'submitted_at' => '2024-05-12 10:00:00',
            'document_path' => 'receipt_cebu_1.pdf', // Simulated path
            'project_id' => null, 'department_id_allocated' => 10,
            'approval_comments' => 'Approved as per travel policy.', 'approved_by' => 103, 'approval_date' => '2024-05-13 11:00:00'
        ],
        [
            'claim_id' => 2, 'employee_id' => 102, 'claim_date' => '2024-05-01', 'category_id' => 2,
            'description' => 'Consultation and medication for flu (simulated).',
            'amount' => 1500.00, 'status' => 'Pending', 'submitted_at' => '2024-05-05 14:30:00',
            'document_path' => 'medical_bill_bob.jpg',
            'project_id' => null, 'department_id_allocated' => 20,
            'approval_comments' => null, 'approved_by' => null, 'approval_date' => null
        ]
    ];
}

// Initialize a separate session variable for standalone receipts
if (!isset($_SESSION['receipts_data'])) {
    $_SESSION['receipts_data'] = [
        [
            'receipt_id' => 1,
            'filename' => 'grocery_receipt_june_1.jpg',
            'uploaded_by' => 101, // Employee ID
            'upload_date' => '2024-06-01 15:00:00',
            'simulated_path' => 'simulated_receipt_1.jpg',
            'linked_claim_id' => null, // Not yet linked to a claim
            'description' => 'Weekly groceries for home office allowance'
        ],
        [
            'receipt_id' => 2,
            'filename' => 'coffee_meeting_may_25.pdf',
            'uploaded_by' => 102,
            'upload_date' => '2024-05-25 09:30:00',
            'simulated_path' => 'simulated_receipt_2.pdf',
            'linked_claim_id' => 2, // Linked to existing claim ID 2
            'description' => 'Coffee meeting with client'
        ]
    ];
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

// --- Handle Form Submission (Simulated Receipt Upload) ---
$status_message = $_SESSION['status_message'] ?? null;
unset($_SESSION['status_message']); // Clear message after retrieving

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['upload_receipt'])) {
        $uploaded_by = intval($_POST['uploaded_by']);
        $receipt_description = trim($_POST['receipt_description']);
        $simulated_filename = 'receipt_' . uniqid() . '.' . pathinfo($_FILES['receipt_file']['name'], PATHINFO_EXTENSION); // Use actual extension if possible

        // Basic validation
        if (empty($uploaded_by) || empty($receipt_description) || empty($_FILES['receipt_file']['name'])) {
            $_SESSION['status_message'] = ['type' => 'danger', 'message' => 'Please fill in all required fields and select a file.'];
        } else {
            // Simulate file upload (no actual file moved)
            // In a real app: move_uploaded_file($_FILES['receipt_file']['tmp_name'], $target_path);

            // Simulate generating a new receipt ID
            $new_receipt_id = count($_SESSION['receipts_data']) > 0 ? max(array_column($_SESSION['receipts_data'], 'receipt_id')) + 1 : 1;

            $new_receipt = [
                'receipt_id' => $new_receipt_id,
                'filename' => $_FILES['receipt_file']['name'], // Original filename
                'uploaded_by' => $uploaded_by,
                'upload_date' => date('Y-m-d H:i:s'),
                'simulated_path' => 'simulated_uploads/' . $simulated_filename, // A simulated path for display
                'linked_claim_id' => null, // Not linked to a claim initially
                'description' => $receipt_description
            ];

            $_SESSION['receipts_data'][] = $new_receipt;

            $_SESSION['status_message'] = ['type' => 'success', 'message' => 'Receipt simulated as uploaded successfully! (Changes are not persistent.)'];
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
            <a class="text-decoration-none text-muted" href="receipt_management.php">Receipt Management</a>
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
            <h3 class="text-center">Upload New Receipt</h3>
            <hr>
            <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="uploaded_by" class="form-label">Uploaded By Employee</label>
                        <select class="form-select" id="uploaded_by" name="uploaded_by" required>
                            <option value="">Select Employee</option>
                            <?php foreach ($employees_data as $emp): ?>
                                <option value="<?= htmlspecialchars($emp['employee_id']) ?>">
                                    <?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="receipt_file" class="form-label">Select Receipt File</label>
                        <input class="form-control" type="file" id="receipt_file" name="receipt_file" required>
                        <div class="form-text">Accepted formats: JPG, PNG, PDF (simulated upload).</div>
                    </div>
                    <div class="col-12">
                        <label for="receipt_description" class="form-label">Receipt Description</label>
                        <textarea class="form-control" id="receipt_description" name="receipt_description" rows="2" required></textarea>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" name="upload_receipt" class="btn btn-primary">Upload Receipt</button>
                        <button type="reset" class="btn btn-secondary">Clear Form</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col d-flex flex-column p-4 border border-2 rounded-3">
            <h3 class="text-center">My Managed Receipts</h3>
            <hr>
            <?php if (empty($_SESSION['receipts_data'])): ?>
                <p class="text-center text-muted">No receipts have been uploaded yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover border text-center align-middle">
                        <thead>
                            <tr>
                                <th>Receipt ID</th>
                                <th>Filename</th>
                                <th>Uploaded By</th>
                                <th>Upload Date</th>
                                <th>Description</th>
                                <th>Linked Claim ID</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_reverse($_SESSION['receipts_data']) as $receipt): // Show newest first ?>
                                <tr>
                                    <td><?= htmlspecialchars($receipt['receipt_id']) ?></td>
                                    <td><?= htmlspecialchars($receipt['filename']) ?></td>
                                    <td><?= getEmployeeNameById($receipt['uploaded_by'], $employees_data) ?></td>
                                    <td><?= htmlspecialchars($receipt['upload_date']) ?></td>
                                    <td class="text-start" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <span title="<?= htmlspecialchars($receipt['description']) ?>"><?= htmlspecialchars($receipt['description']) ?></span>
                                    </td>
                                    <td>
                                        <?php if (!is_null($receipt['linked_claim_id'])): ?>
                                            <a href="reimbursement_status.php" title="View Claim #<?= htmlspecialchars($receipt['linked_claim_id']) ?>"><?= htmlspecialchars($receipt['linked_claim_id']) ?></a>
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#receiptViewModal" data-receipt-path="<?= htmlspecialchars($receipt['simulated_path']) ?>" data-receipt-filename="<?= htmlspecialchars($receipt['filename']) ?>">View</button>
                                        <button type="button" class="btn btn-sm btn-secondary" disabled title="Download is simulated">Download</button>
                                        <button type="button" class="btn btn-sm btn-danger" disabled title="Delete is simulated">Delete</button>
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

<div class="modal fade" id="receiptViewModal" tabindex="-1" aria-labelledby="receiptViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptViewModalLabel">Simulated Receipt View</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="text-muted">This is a simulated receipt view. In a real application, you would load the actual image or PDF here.</p>
                <div id="receiptContent">
                    <img src="https://via.placeholder.com/600x400?text=Simulated+Receipt" alt="Simulated Receipt" class="img-fluid border rounded">
                    <p class="mt-2">Filename: <span id="modalReceiptFilename"></span></p>
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
    var receiptViewModal = document.getElementById('receiptViewModal');
    if (receiptViewModal) {
        receiptViewModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var receiptPath = button.getAttribute('data-receipt-path');
            var receiptFilename = button.getAttribute('data-receipt-filename');
            var modalReceiptFilenameSpan = receiptViewModal.querySelector('#modalReceiptFilename');

            modalReceiptFilenameSpan.textContent = receiptFilename;

            // In a real application, you would dynamically change the src of the img
            // or embed a PDF viewer based on `receiptPath` (e.g., `<img src="${receiptPath}">`
            // or `<iframe src="${receiptPath}" width="100%" height="500px"></iframe>`).
            // For this simulation, we just keep the placeholder image.
        });
    }
});
</script>

<?php ob_end_flush(); ?>