<?php
session_start();
ob_start(); // Start output buffering

// Page title
$title = "Manage Leave Types (Hardcoded)"; // Indicate no database

// Initialize status message
$status_message = $_SESSION['status_message'] ?? null;
unset($_SESSION['status_message']); // Clear message after retrieving

// --- Hardcoded Leave Types Array (Simulating Database Data) ---
// IMPORTANT: Changes made through the UI WILL NOT persist after page refresh.
// This data is reset every time the page loads.
$leave_types = [
    [
        'leave_type_id' => 1,
        'leave_type_name' => 'Sick Leave',
        'description' => 'Time off for personal illness or medical appointments.',
        'accrual_rate' => 10.00,
        'max_carry_over' => 0.00,
        'requires_document' => true,
        'is_active' => true,
    ],
    [
        'leave_type_id' => 2,
        'leave_type_name' => 'Vacation Leave',
        'description' => 'Paid time off for rest and recreation.',
        'accrual_rate' => 15.00,
        'max_carry_over' => 5.00,
        'requires_document' => false,
        'is_active' => true,
    ],
    [
        'leave_type_id' => 3,
        'leave_type_name' => 'Maternity Leave',
        'description' => 'Leave for expectant or new mothers.',
        'accrual_rate' => 60.00,
        'max_carry_over' => 0.00,
        'requires_document' => true,
        'is_active' => true,
    ],
    [
        'leave_type_id' => 4,
        'leave_type_name' => 'Paternity Leave',
        'description' => 'Leave for new fathers.',
        'accrual_rate' => 7.00,
        'max_carry_over' => 0.00,
        'requires_document' => false,
        'is_active' => true,
    ],
    [
        'leave_type_id' => 5,
        'leave_type_name' => 'Unpaid Leave',
        'description' => 'Leave taken without pay.',
        'accrual_rate' => 0.00,
        'max_carry_over' => 0.00,
        'requires_document' => false,
        'is_active' => true,
    ],
    [
        'leave_type_id' => 6,
        'leave_type_name' => 'Bereavement Leave',
        'description' => 'Leave for the death of a family member.',
        'accrual_rate' => 3.00,
        'max_carry_over' => 0.00,
        'requires_document' => true,
        'is_active' => true,
    ],
];
// To simulate ID generation for new types, we can use a simple counter based on max existing ID
$next_id = max(array_column($leave_types, 'leave_type_id')) + 1;


// Initialize variables for form (for editing existing leave type)
$edit_leave_type_id = null;
$edit_leave_type_name = '';
$edit_description = '';
$edit_accrual_rate = '';
$edit_max_carry_over = '';
$edit_requires_document = 0;
$edit_is_active = 1;


// --- Handle Form Submissions (Simulated Add, Edit, Delete) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_leave_type'])) {
        $leave_type_name = trim($_POST['leave_type_name']);
        $description = trim($_POST['description'] ?? '');
        $accrual_rate = floatval($_POST['accrual_rate']);
        $max_carry_over = floatval($_POST['max_carry_over']);
        $requires_document = isset($_POST['requires_document']) ? 1 : 0;
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        if (empty($leave_type_name)) {
            $_SESSION['status_message'] = ['type' => 'danger', 'message' => 'Leave Type Name cannot be empty.'];
        } elseif ($accrual_rate < 0) {
            $_SESSION['status_message'] = ['type' => 'danger', 'message' => 'Accrual Rate cannot be negative.'];
        } else {
            // Simulate adding a new leave type
            $new_leave_type = [
                'leave_type_id' => $next_id++, // Assign a new ID
                'leave_type_name' => $leave_type_name,
                'description' => $description,
                'accrual_rate' => $accrual_rate,
                'max_carry_over' => $max_carry_over,
                'requires_document' => $requires_document,
                'is_active' => $is_active,
            ];
            // Add to session, but remember this won't persist if session is cleared or server restarted
            // For true hardcoded, this simulation is just for the current request.
            // If you truly wanted to "save" in a hardcoded scenario, you'd re-write the PHP array file.
            // For this example, we'll just show the message that it was "added".
            $_SESSION['status_message'] = ['type' => 'success', 'message' => 'Leave Type "' . htmlspecialchars($leave_type_name) . '" simulated as added! (Changes are not persistent.)'];
        }
    } elseif (isset($_POST['update_leave_type'])) {
        $leave_type_id = intval($_POST['leave_type_id']);
        $leave_type_name = trim($_POST['leave_type_name']);
        $description = trim($_POST['description'] ?? '');
        $accrual_rate = floatval($_POST['accrual_rate']);
        $max_carry_over = floatval($_POST['max_carry_over']);
        $requires_document = isset($_POST['requires_document']) ? 1 : 0;
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        if (empty($leave_type_name)) {
            $_SESSION['status_message'] = ['type' => 'danger', 'message' => 'Leave Type Name cannot be empty.'];
        } elseif ($accrual_rate < 0) {
            $_SESSION['status_message'] = ['type' => 'danger', 'message' => 'Accrual Rate cannot be negative.'];
        } else {
            // Simulate updating an existing leave type
            $found_index = -1;
            foreach ($leave_types as $index => $type) {
                if ($type['leave_type_id'] == $leave_type_id) {
                    $found_index = $index;
                    break;
                }
            }

            if ($found_index !== -1) {
                // In a true hardcoded scenario, you'd modify $leave_types[$found_index] directly
                // but since it resets, we just show a message.
                $_SESSION['status_message'] = ['type' => 'success', 'message' => 'Leave Type "' . htmlspecialchars($leave_type_name) . '" simulated as updated! (Changes are not persistent.)'];
            } else {
                $_SESSION['status_message'] = ['type' => 'danger', 'message' => 'Simulated update failed: Leave Type not found.'];
            }
        }
    } elseif (isset($_POST['delete_leave_type'])) {
        $leave_type_id = intval($_POST['leave_type_id']);

        // Simulate deletion
        $initial_count = count($leave_types);
        $leave_types = array_filter($leave_types, function($type) use ($leave_type_id) {
            return $type['leave_type_id'] != $leave_type_id;
        });
        // Re-index array after filter
        $leave_types = array_values($leave_types);

        if (count($leave_types) < $initial_count) {
            $_SESSION['status_message'] = ['type' => 'success', 'message' => 'Leave Type simulated as deleted! (Changes are not persistent.)'];
        } else {
            $_SESSION['status_message'] = ['type' => 'danger', 'message' => 'Simulated delete failed: Leave Type not found or could not be removed.'];
        }
    }
    // Redirect to clear POST data and show message, but the data will reset to the hardcoded array
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// --- Populate form for editing if edit ID is passed ---
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $leave_type_to_edit = null;
    foreach ($leave_types as $type) {
        if ($type['leave_type_id'] == $edit_id) {
            $leave_type_to_edit = $type;
            break;
        }
    }

    if ($leave_type_to_edit) {
        $edit_leave_type_id = $leave_type_to_edit['leave_type_id'];
        $edit_leave_type_name = $leave_type_to_edit['leave_type_name'];
        $edit_description = $leave_type_to_edit['description'];
        $edit_accrual_rate = $leave_type_to_edit['accrual_rate'];
        $edit_max_carry_over = $leave_type_to_edit['max_carry_over'];
        $edit_requires_document = $leave_type_to_edit['requires_document'];
        $edit_is_active = $leave_type_to_edit['is_active'];
    } else {
        $_SESSION['status_message'] = ['type' => 'warning', 'message' => 'Leave Type not found for editing.'];
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Now include admin.php to render the HTML structure
// This file is assumed to contain your common HTML structure (header, navbar, footer, etc.)
// Make sure admin.php sets up Bootstrap 5 and the basic page layout.
include_once 'admin.php'; // Adjust path if necessary
?>

<style>
    .types {
        color: var(--br-dark);
    }
</style>
<div class="p-2 gap-3">
    <div class="d-flex col">
        <h6 class="text-muted pe-none mb-0">
            <a class="text-decoration-none text-muted" href="">Home</a> >
            <a class="text-decoration-none text-muted" href="manage_leave_types.php">Manage Leave Types</a>
        </h6>
    </div>
    <hr>
    <div class="nav col-12 d-flex justify-content-around">
        <?php
        // This is where your sub-navigation for leave management might be
         include('nav/leave management/nav.php');
        // If you don't have this, you can remove or hardcode links here
        ?>
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
            <h3 class="text-center"><?= $edit_leave_type_id ? 'Edit Leave Type' : 'Add New Leave Type' ?></h3>
            <hr>
            <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                <?php if ($edit_leave_type_id): ?>
                    <input type="hidden" name="leave_type_id" value="<?= htmlspecialchars($edit_leave_type_id) ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="leaveTypeName" class="form-label">Leave Type Name</label>
                    <input type="text" class="form-control" id="leaveTypeName" name="leave_type_name"
                           value="<?= htmlspecialchars($edit_leave_type_name) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($edit_description) ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="accrualRate" class="form-label">Accrual Rate (Days)</label>
                    <input type="number" step="0.01" class="form-control" id="accrualRate"
                           name="accrual_rate" value="<?= htmlspecialchars($edit_accrual_rate) ?>" required min="0">
                </div>
                <div class="mb-3">
                    <label for="maxCarryOver" class="form-label">Max Carry Over (Days)</label>
                    <input type="number" step="0.01" class="form-control" id="maxCarryOver"
                           name="max_carry_over" value="<?= htmlspecialchars($edit_max_carry_over) ?>" required min="0">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="requiresDocument" name="requires_document" value="1"
                           <?= $edit_requires_document ? 'checked' : '' ?>>
                    <label class="form-check-label" for="requiresDocument">Requires Document</label>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="isActive" name="is_active" value="1"
                           <?= $edit_is_active ? 'checked' : '' ?>>
                    <label class="form-check-label" for="isActive">Is Active</label>
                </div>

                <button type="submit" name="<?= $edit_leave_type_id ? 'update_leave_type' : 'add_leave_type' ?>"
                        class="btn btn-primary">
                    <?= $edit_leave_type_id ? 'Update Leave Type' : 'Add Leave Type' ?>
                </button>
                <?php if ($edit_leave_type_id): ?>
                    <a href="manage_leave_types.php" class="btn btn-secondary">Cancel Edit</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="col d-flex flex-column p-4 border border-2 rounded-3">
            <h3 class="text-center">Existing Leave Types</h3>
            <hr>
            <?php if (empty($leave_types)): ?>
                <p class="text-center text-muted">No leave types defined yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover border text-center">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Leave Type Name</th>
                                <th>Accrual Rate</th>
                                <th>Max Carry Over</th>
                                <th>Requires Document</th>
                                <th>Active</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($leave_types as $type): ?>
                                <tr>
                                    <td><?= htmlspecialchars($type['leave_type_id']) ?></td>
                                    <td><?= htmlspecialchars($type['leave_type_name']) ?></td>
                                    <td><?= htmlspecialchars($type['accrual_rate']) ?></td>
                                    <td><?= htmlspecialchars($type['max_carry_over']) ?></td>
                                    <td><?= $type['requires_document'] ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' ?></td>
                                    <td><?= $type['is_active'] ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-danger">No</span>' ?></td>
                                    <td>
                                        <a href="?edit_id=<?= htmlspecialchars($type['leave_type_id']) ?>" class="btn btn-sm btn-info me-2">Edit</a>
                                        <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this leave type? This action cannot be undone and will reset on page refresh.');">
                                            <input type="hidden" name="leave_type_id" value="<?= htmlspecialchars($type['leave_type_id']) ?>">
                                            <button type="submit" name="delete_leave_type" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
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