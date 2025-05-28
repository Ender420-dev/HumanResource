<?php
session_start();
ob_start();

// This should establish $conn_hr3 and $conn_hr4
include_once '../connections.php';

// Handle AJAX: Fetch details
if (isset($_GET['ajax']) && $_GET['ajax'] === 'get_details' && isset($_GET['request_id'])) {
    $request_id = $_GET['request_id'];
    try {
        $stmt = $conn_hr3->prepare("
            SELECT
                lr.*,
                e.first_name,
                e.last_name,
                lt.leave_type_name, -- Added to fetch leave type name
                e_approver.first_name AS approver_first_name,
                e_approver.last_name AS approver_last_name
            FROM hr3.LeaveRequests lr
            JOIN hr4.Employees e ON lr.employee_id = e.employee_id
            JOIN hr3.LeaveTypes lt ON lr.leave_type_id = lt.leave_type_id -- Added join for leave type
            LEFT JOIN hr4.Employees e_approver ON lr.approved_by = e_approver.employee_id
            WHERE lr.request_id = :request_id
        ");
        $stmt->execute([':request_id' => $request_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            echo json_encode(['success' => true, 'data' => $data]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Leave request not found.']);
        }
    } catch (PDOException $e) {
        error_log("DB Error fetching leave request details: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'DB Error: ' . $e->getMessage()]);
    }
    exit;
}

// Handle AJAX: Update status
if (isset($_POST['ajax']) && $_POST['ajax'] === 'update_status') {
    $request_id = $_POST['request_id'] ?? '';
    $status = $_POST['status'] ?? '';
    $comments = $_POST['approver_comments'] ?? null;
    // Assuming employee_id is available in session for the approver
    $approved_by = $_SESSION['employee_id'] ?? null;

    if (!$request_id || !$status) {
        echo json_encode(['success' => false, 'message' => 'Missing request ID or status.']);
        exit;
    }

    try {
        $stmt = $conn_hr3->prepare("
            UPDATE hr3.LeaveRequests
            SET status = :status,
                approved_by = :approved_by,
                approved_at = NOW(),
                approver_comments = :comments
            WHERE request_id = :request_id
        ");
        $stmt->execute([
            ':status' => $status,
            ':approved_by' => $approved_by,
            ':comments' => $comments,
            ':request_id' => $request_id
        ]);
        echo json_encode(['success' => true, 'message' => 'Leave request updated successfully.']);
    } catch (PDOException $e) {
        error_log("DB Error updating leave request status: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'DB Error: ' . $e->getMessage()]);
    }
    exit;
}

// === MAIN PAGE VIEW BELOW ===

$title = "Manage Leave Requests"; // Used by admin.php layout

// Status message logic (for non-AJAX operations, e.g., after a redirect)
$status_message = $_SESSION['status_message'] ?? null;
unset($_SESSION['status_message']);

// --- Filtering logic ---
$filter_status = $_GET['status_filter'] ?? '';
$filter_employee_name = trim($_GET['employee_name_filter'] ?? '');

$sql_where = " WHERE 1=1 ";
$bind_params = [];
$param_index = 1;

if (!empty($filter_status) && $filter_status !== 'All') {
    $sql_where .= " AND lr.status = :status_filter ";
    $bind_params[':status_filter'] = $filter_status;
}

if (!empty($filter_employee_name)) {
    $name_parts = explode(' ', $filter_employee_name);
    $name_search_conditions = [];
    foreach ($name_parts as $part) {
        $name_search_conditions[] = "(e.first_name LIKE :name_part_" . $param_index . " OR e.last_name LIKE :name_part_" . $param_index . ")";
        $bind_params[':name_part_' . $param_index] = '%' . $part . '%';
        $param_index++;
    }
    if (!empty($name_search_conditions)) {
        $sql_where .= " AND (" . implode(" AND ", $name_search_conditions) . ")";
    }
}
// --- End Filtering Logic ---

// --- Fetch Leave Requests ---
$leave_requests = [];
try {
    $stmt_requests = $conn_hr3->prepare("
        SELECT
            lr.request_id,
            e.first_name,
            e.last_name,
            e.employee_id,
            lt.leave_type_name,
            lr.start_date,
            lr.end_date,
            lr.total_days,
            lr.reason,
            lr.status,
            lr.requested_at,
            e_approver.first_name AS approver_first_name,
            e_approver.last_name AS approver_last_name,
            lr.approved_at,
            lr.approver_comments,
            lr.document_path
        FROM hr3.LeaveRequests lr
        JOIN hr4.Employees e ON lr.employee_id = e.employee_id
        JOIN hr3.LeaveTypes lt ON lr.leave_type_id = lt.leave_type_id
        LEFT JOIN hr4.Employees e_approver ON lr.approved_by = e_approver.employee_id
        {$sql_where}
        ORDER BY lr.requested_at DESC
    ");
    $stmt_requests->execute($bind_params);
    $leave_requests = $stmt_requests->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $status_message = ['type' => 'danger', 'message' => 'Error fetching leave requests: ' . $e->getMessage()];
    error_log("Error fetching leave requests: " . $e->getMessage());
}

// Load page layout (render HTML) - assuming admin.php includes header, nav, etc.
include_once 'admin.php';
?>
<style>
    .employee {
        color: var(--br-dark);
    }
</style>

<div class="p-2 gap-3">
    <div class="d-flex col">
        <h6 class="text-muted pe-none mb-0">
            <a class="text-decoration-none text-muted" href="">Home</a> >
            <a class="text-decoration-none text-muted" href="manage_leave_requests.php">Manage Leave Requests</a>
        </h6>
    </div>
    <hr>
    <div class="nav col-12 d-flex justify-content-around">
        <?php include('nav/leave management/nav.php'); // Adjust path if needed ?>
    </div>
    <hr>

    <div class="container-fluid shadow-lg col p-4">
        <?php
        if ($status_message) {
            $msg_type = $status_message['type'];
            $msg_content = $status_message['message'];
            echo "<div class='alert alert-$msg_type alert-dismissible fade show' role='alert'>";
            echo $msg_content;
            echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
            echo "</div>";
        }
        ?>

        <h3 class="text-center">Manage Leave Requests</h3>
        <hr>

        <form class="mb-4" method="GET" action="manage_leave_requests.php">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="status_filter" class="form-label">Filter by Status:</label>
                    <select class="form-select" id="status_filter" name="status_filter">
                        <option value="All" <?= $filter_status == 'All' ? 'selected' : '' ?>>All</option>
                        <option value="Pending" <?= $filter_status == 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="Approved" <?= $filter_status == 'Approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="Rejected" <?= $filter_status == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                        <option value="Cancelled" <?= $filter_status == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        <option value="Revisions Requested" <?= $filter_status == 'Revisions Requested' ? 'selected' : '' ?>>Revisions Requested</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="manage_leave_requests.php" class="btn btn-secondary">Clear Filters</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Employee Name</th>
                        <th>Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Total Days</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Requested At</th>
                        <th>Approved By</th>
                        <th>Approved At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($leave_requests)): ?>
                        <?php foreach ($leave_requests as $request): ?>
                            <tr>
                                <td><?= htmlspecialchars($request['request_id']) ?></td>
                                <td><?= htmlspecialchars($request['first_name'] . ' ' . $request['last_name']) ?></td>
                                <td><?= htmlspecialchars($request['leave_type_name']) ?></td>
                                <td><?= htmlspecialchars($request['start_date']) ?></td>
                                <td><?= htmlspecialchars($request['end_date']) ?></td>
                                <td><?= htmlspecialchars($request['total_days']) ?></td>
                                <td><?= htmlspecialchars(substr($request['reason'], 0, 50)) . (strlen($request['reason']) > 50 ? '...' : '') ?></td>
                                <td><?= htmlspecialchars($request['status']) ?></td>
                                <td><?= htmlspecialchars($request['requested_at']) ?></td>
                                <td><?= htmlspecialchars($request['approver_first_name'] ? $request['approver_first_name'] . ' ' . $request['approver_last_name'] : 'N/A') ?></td>
                                <td><?= htmlspecialchars($request['approved_at'] ?? 'N/A') ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info view-manage-btn" data-bs-toggle="modal" data-bs-target="#leaveRequestModal" data-request-id="<?= htmlspecialchars($request['request_id']) ?>">
                                        View/Manage
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="12" class="text-center">No leave requests found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="leaveRequestModal" tabindex="-1" aria-labelledby="leaveRequestModalLabel" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="leaveRequestModalLabel">Leave Request Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="leaveRequestForm">
                    <input type="hidden" id="modalRequestId" name="request_id">
                    <input type="hidden" id="modalCurrentStatus" name="current_status">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="modalEmployeeName" class="form-label">Employee Name:</label>
                            <input type="text" class="form-control" id="modalEmployeeName" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modalLeaveType" class="form-label">Leave Type:</label>
                            <input type="text" class="form-control" id="modalLeaveType" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="modalStartDate" class="form-label">Start Date:</label>
                            <input type="date" class="form-control" id="modalStartDate" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modalEndDate" class="form-label">End Date:</label>
                            <input type="date" class="form-control" id="modalEndDate" readonly>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="modalTotalDays" class="form-label">Total Days:</label>
                        <input type="text" class="form-control" id="modalTotalDays" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="modalReason" class="form-label">Reason:</label>
                        <textarea class="form-control" id="modalReason" rows="3" readonly></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="modalRequestedAt" class="form-label">Requested At:</label>
                        <input type="text" class="form-control" id="modalRequestedAt" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="modalDocumentPath" class="form-label">Document:</label>
                        <span id="modalDocumentLink">N/A</span> </div>

                    <hr>
                    <div id="statusManagementSection">
                        <h5>Approve/Reject Leave Request</h5>
                        <div class="mb-3">
                            <label for="modalNewStatus" class="form-label">Change Status To:</label>
                            <select class="form-select" id="modalNewStatus" name="new_status">
                                <option value="">Select Action</option>
                                <option value="Approved">Approve</option>
                                <option value="Rejected">Reject</option>
                                <option value="Revisions Requested">Request Revisions</option>
                                <option value="Cancelled">Cancel</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="modalApproverComments" class="form-label">Approver Comments (Optional):</label>
                            <textarea class="form-control" id="modalApproverComments" name="approver_comments" rows="2"></textarea>
                        </div>
                        <div class="alert mt-3" role="alert" id="modalStatusMessage" style="display: none;"></div>
                    </div>

                    <div id="readOnlyStatusSection">
                        <h5>Current Status:</h5>
                        <div class="mb-3">
                            <label for="modalDisplayStatus" class="form-label">Status:</label>
                            <input type="text" class="form-control" id="modalDisplayStatus" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="modalDisplayApprovedBy" class="form-label">Approved By:</label>
                            <input type="text" class="form-control" id="modalDisplayApprovedBy" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="modalDisplayApprovedAt" class="form-label">Approved At:</label>
                            <input type="text" class="form-control" id="modalDisplayApprovedAt" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="modalDisplayApproverComments" class="form-label">Approver Comments:</label>
                            <textarea class="form-control" id="modalDisplayApproverComments" rows="2" readonly></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveStatusBtn">Save Changes</button> </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // Event listener for "View/Manage" buttons
    $('.view-manage-btn').on('click', function() {
        var requestId = $(this).data('request-id');
        $('#modalRequestId').val(requestId); // Set hidden input for update
        $('#modalStatusMessage').hide().removeClass('alert-success alert-danger'); // Hide and clear any previous status message
        
        // Reset modal fields for a clean display
        $('#modalEmployeeName, #modalLeaveType, #modalStartDate, #modalEndDate, #modalTotalDays, #modalReason, #modalRequestedAt, #modalApproverComments, #modalDisplayStatus, #modalDisplayApprovedBy, #modalDisplayApprovedAt, #modalDisplayApproverComments').val('');
        $('#modalDocumentLink').html('N/A');
        $('#modalNewStatus').val(''); // Reset dropdown
        
        // AJAX call to fetch request details
        $.ajax({
            url: 'manage_leave_requests.php?ajax=get_details', // Corrected URL to point to itself
            type: 'GET',
            data: { request_id: requestId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var data = response.data;
                    $('#modalEmployeeName').val(data.first_name + ' ' + data.last_name);
                    $('#modalLeaveType').val(data.leave_type_name); // This will now be populated
                    $('#modalStartDate').val(data.start_date);
                    $('#modalEndDate').val(data.end_date);
                    $('#modalTotalDays').val(data.total_days);
                    $('#modalReason').val(data.reason);
                    $('#modalRequestedAt').val(data.requested_at);

                    if (data.document_path) {
                        $('#modalDocumentLink').html('<a href="' + data.document_path + '" target="_blank">View Document</a>');
                    } else {
                        $('#modalDocumentLink').text('N/A');
                    }

                    // Store current status in a hidden field for potential future use
                    $('#modalCurrentStatus').val(data.status);

                    // --- Logic to show/hide sections and populate fields based on status ---
                    if (data.status === 'Pending') {
                        $('#statusManagementSection').show();
                        $('#readOnlyStatusSection').hide();
                        // Populate editable comments if they exist from previous 'Revisions Requested' etc.
                        $('#modalApproverComments').val(data.approver_comments ? data.approver_comments : '');
                        $('#modalNewStatus').val(''); // Ensure 'Select Action' is default for Pending
                    } else {
                        $('#statusManagementSection').hide();
                        $('#readOnlyStatusSection').show();
                        // Populate read-only fields
                        $('#modalDisplayStatus').val(data.status);
                        $('#modalDisplayApprovedBy').val(data.approver_first_name ? data.approver_first_name + ' ' + data.approver_last_name : 'N/A');
                        $('#modalDisplayApprovedAt').val(data.approved_at ? data.approved_at : 'N/A');
                        $('#modalDisplayApproverComments').val(data.approver_comments ? data.approver_comments : 'N/A');
                    }

                } else {
                    $('#modalStatusMessage').text(response.message).addClass('alert-danger').show();
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: " + status + " - " + error);
                $('#modalStatusMessage').text('Error fetching request details. Please try again.').addClass('alert-danger').show();
            }
        });
    });

    // Event listener for "Save Changes" button in the modal
    $('#saveStatusBtn').on('click', function() {
        var requestId = $('#modalRequestId').val();
        var newStatus = $('#modalNewStatus').val(); // Corrected target to modalNewStatus
        var approverComments = $('#modalApproverComments').val();
        var statusMessageDiv = $('#modalStatusMessage');

        if (!requestId || !newStatus) {
            statusMessageDiv.text('Please select an action and ensure request ID is available.').addClass('alert-danger').show();
            return;
        }

        $.ajax({
            url: 'manage_leave_requests.php', // Corrected URL to point to itself
            type: 'POST',
            data: {
                ajax: 'update_status', // Added ajax flag
                request_id: requestId,
                status: newStatus,
                approver_comments: approverComments // Send comments
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    statusMessageDiv.text(response.message).removeClass('alert-danger').addClass('alert-success').show();
                    // Reload the page after a short delay to reflect changes in the table
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    statusMessageDiv.text(response.message).removeClass('alert-success').addClass('alert-danger').show();
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: " + status + " - " + error);
                statusMessageDiv.text('Error saving status. Please try again.').addClass('alert-danger').show();
            }
        });
    });
});
</script>

<?php ob_end_flush(); ?>