<?php
session_start();
ob_start();

$title = 'Attendance Tracking';
include_once 'admin.php';
include_once 'attendance_modal.php';
include_once '../connections.php'; // Make sure this file establishes $conn

// Initialize $manual to an empty array to prevent the foreach warning if no data is found
$manual = []; 
$message = ''; // Initialize message variable

// --- Database Query to Fetch Pending Manual Adjustments ---
try {
    $sql_fetch = "SELECT id, employee_id, date, type, old_time, new_time, status 
                  FROM ManualAdjustments 
                  WHERE status = 'Pending' 
                  ORDER BY date DESC, id DESC"; // Assuming 'Pending' is the status for review
    $stmt_fetch = $conn->prepare($sql_fetch);
    $stmt_fetch->execute();
    $manual = $stmt_fetch->fetchAll(PDO::FETCH_ASSOC); // Fetch all results as an associative array
} catch (PDOException $e) {
    // In a production environment, you would log this error and show a generic message.
    // For debugging, you might display the error.
    error_log("Database error fetching manual adjustments: " . $e->getMessage());
    $message = "Error retrieving manual adjustments. Please try again later.";
    // Ensure $manual is still an empty array if an error occurs
    $manual = []; 
}
// --- End Database Query ---


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['selected_ids'])) {
    $action = $_POST['action']; // 'approve' or 'reject'
    $validActions = ['approve' => 'Approved', 'reject' => 'Rejected'];

    if (array_key_exists($action, $validActions)) {
        $statusToSet = $validActions[$action];
        $ids = $_POST['selected_ids'];

        if (count($ids) > 0) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $sql = "UPDATE ManualAdjustments SET status = ? WHERE id IN ($placeholders)";
            $stmt = $conn->prepare($sql);
            try {
                $stmt->execute(array_merge([$statusToSet], $ids));
                $message = "Selected requests successfully " . strtolower($statusToSet) . ".";
                // After successful update, re-fetch the data to reflect changes
                // Or you could filter the $manual array to remove updated items
                // For simplicity, let's just refresh the page
                header("Location: pending approval.php?msg=" . urlencode($message));
                exit();
            } catch (PDOException $e) {
                error_log("Database error updating manual adjustments: " . $e->getMessage());
                $message = "Error updating requests. Please try again.";
            }
        } else {
            $message = "No requests selected.";
        }
    } else {
        $message = "Invalid action.";
    }
}

// Check for and display message from URL after redirect
if (isset($_GET['msg'])) {
    $message = htmlspecialchars($_GET['msg']);
}
?>

<div class="p-2 gap-3">
    <div class="d-flex col">
        <h6 class=" text-muted pe-none mb-0"><a class="text-decoration-none text-muted" href="">Home</a> > <a class="text-decoration-none text-muted" href="">Attendance Tracking</a> > <a class="text-decoration-none text-muted" href="">Pending Approvals</a></h6>
    </div>
    <hr>
    <div class="nav col-12 d-flex justify-content-around">
        <h3><a class="text-decoration-none" href="attendance tracking.php">Attendance Tracking</a></h3>
        <h3><a href="employee logs.php" class="text-decoration-none">Employee Logs</a></h3>
        <h3><a class="text-decoration-none" href="rules and config.php">Rules & Config</a></h3>
        <h3><a class="text-decoration-none" href="report.php">Report</a></h3>
    </div>
    <hr>
    <div class="container-fluid shadow-lg col p-5">
        <form method="POST" action="">
            <div class="col d-flex flex-column border border-2 border rounded-3 p-4">
                <h3 class="align-items-center text-center">Review and Approve Manual Time Adjustments</h3>
                <?php if (!empty($message)): ?>
                    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>

                <hr>
                <div class="col d-flex flex-column border border-2 border rounded-3 p-4">
                    <table class="table table-striped table-hover border text-center">
                        <thead>
                            <tr>
                                <th class="col-1 text-center border-end border-opacity-10">Check</th>
                                <th>Request ID</th>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Old Time</th>
                                <th>New Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($manual)): // Check if $manual is not empty before looping ?>
                                <?php foreach ($manual as $entry): ?>
                                <tr>
                                    <td class="col-1 text-center border-end border-black border-opacity-10">
                                        <input class="form-check-input border border-secondary-subtle" type="checkbox" name="selected_ids[]" value="<?= $entry['id'] ?>">
                                    </td>

                                    <td><?= htmlspecialchars($entry['id']) ?></td>
                                    <td><?= htmlspecialchars($entry['employee']) ?></td>
                                    <td><?= htmlspecialchars($entry['date']) ?></td>
                                    <td><?= htmlspecialchars($entry['type']) ?></td>
                                    <td><?= htmlspecialchars($entry['old_time']) ?></td>
                                    <td><?= htmlspecialchars($entry['new_time']) ?></td>
                                    <td>
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pendingApprovalsModal"
                                            data-id="<?= htmlspecialchars($entry['id']) ?>"
                                            data-employee="<?= htmlspecialchars($entry['employee_id']) ?>"
                                            data-date="<?= htmlspecialchars($entry['date']) ?>"
                                            data-type="<?= htmlspecialchars($entry['type']) ?>"
                                            data-oldtime="<?= htmlspecialchars($entry['old_time']) ?>"
                                            data-newtime="<?= htmlspecialchars($entry['new_time']) ?>"
                                            data-reason="<?= htmlspecialchars($entry['reason'] ?? 'N/A') ?>" >Review</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8">No pending manual adjustments to review.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div><br>
                <div>
                    <button class="btn btn-primary" type="submit" name="action" value="approve">Approve Selected</button>
                    <button class="btn btn-secondary" type="submit" name="action" value="reject">Reject Selected</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="pendingApprovalsModal" tabindex="-1" aria-labelledby="pendingApprovalsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pendingApprovalsModalLabel">Manual Adjustment Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Request ID:</strong> <span id="modalRequestId"></span></p>
        <p><strong>Employee:</strong> <span id="modalEmployee"></span></p>
        <p><strong>Date:</strong> <span id="modalDate"></span></p>
        <p><strong>Type:</strong> <span id="modalType"></span></p>
        <p><strong>Old Time:</strong> <span id="modalOldTime"></span></p>
        <p><strong>New Time:</strong> <span id="modalNewTime"></span></p>
        <p><strong>Reason:</strong> <span id="modalReason"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reviewButtons = document.querySelectorAll('.btn-primary[data-bs-toggle="modal"]');
    const pendingApprovalsModal = document.getElementById('pendingApprovalsModal');

    reviewButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Populate modal with data attributes from the button
            document.getElementById('modalRequestId').textContent = this.dataset.id;
            document.getElementById('modalEmployee').textContent = this.dataset.employee;
            document.getElementById('modalDate').textContent = this.dataset.date;
            document.getElementById('modalType').textContent = this.dataset.type;
            document.getElementById('modalOldTime').textContent = this.dataset.oldtime;
            document.getElementById('modalNewTime').textContent = this.dataset.newtime;
            document.getElementById('modalReason').textContent = this.dataset.reason;
        });
    });

    // Optional: Auto-hide the alert message after a few seconds
    const alertMessage = document.querySelector('.alert');
    if (alertMessage) {
        setTimeout(() => {
            alertMessage.style.display = 'none';
        }, 5000); // Hide after 5 seconds
    }
});
</script>
<?php
ob_end_flush();
?>