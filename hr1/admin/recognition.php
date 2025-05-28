<?php
include '../connections.php';

$recognitionDocTargetDir = "../../uploads/recognition_documents/";
if (!is_dir($recognitionDocTargetDir)) {
    mkdir($recognitionDocTargetDir, 0777, true);
}

function handleFileUpload($fileInputName, $targetDir, $conn, $currentDocumentPath = '') {
    $documentPath = $currentDocumentPath;
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] == UPLOAD_ERR_OK) {
        $fileName = uniqid() . "_" . basename($_FILES[$fileInputName]["name"]);
        $targetFile = $targetDir . $fileName;
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        if (move_uploaded_file($_FILES[$fileInputName]["tmp_name"], $targetFile)) {
            if (!empty($currentDocumentPath) && file_exists($currentDocumentPath)) {
                unlink($currentDocumentPath);
            }
            $documentPath = $targetFile;
        } else {
            error_log("Failed to move uploaded file: " . $_FILES[$fileInputName]["name"]);
        }
    }
    return $documentPath;
}

// --- PHP Logic for CRUD Operations (Placeholders for future implementation) ---
$alert = "";

if (isset($_POST['addProgram'])) {
    $stmt = $connections->prepare("INSERT INTO program (name, description, reward_type, status, start_date, end_date, target_department) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "sssssss",
        $_POST['program_name'],
        $_POST['program_description'],
        $_POST['reward_type'],
        $_POST['status'],
        $_POST['start_date'],
        $_POST['end_date'],
        $_POST['target_department']
    );
    if ($stmt->execute()) {
        header("Location: recognition.php?success_message=Program added successfully!&tab=program");
        exit();
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error adding program: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
    $stmt->close();
}

if (isset($_POST['editProgram'])) {
    $stmt = $connections->prepare("UPDATE program SET name=?, description=?, reward_type=?, status=?, start_date=?, end_date=?, target_department=? WHERE id=?");
    $stmt->bind_param(
        "sssssssi",
        $_POST['program_name'],
        $_POST['program_description'],
        $_POST['reward_type'],
        $_POST['status'],
        $_POST['start_date'],
        $_POST['end_date'],
        $_POST['target_department'],
        $_POST['program_id']
    );
    if ($stmt->execute()) {
        header("Location: recognition.php?success_message=Program updated successfully!&tab=program");
        exit();
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error updating program: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
    $stmt->close();
}

if (isset($_GET['delete_program'])) {
    $id = intval($_GET['delete_program']);
    $stmt = $connections->prepare("DELETE FROM program WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: recognition.php?success_message=Program deleted successfully!&tab=program");
        exit();
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error deleting program: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
    $stmt->close();
}

if (isset($_POST['addRecognition'])) {
    $employee_image_path = handleFileUpload('employee_image', $recognitionDocTargetDir, $connections);

    $stmt = $connections->prepare("INSERT INTO recognitions (employee_image_path, employee_name, department, reward_type, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "sssss",
        $employee_image_path,
        $_POST['employee_name_rec'],
        $_POST['department_rec'],
        $_POST['reward_type_rec'],
        $_POST['message_rec']
    );
    if ($stmt->execute()) {
        header("Location: recognition.php?success_message=Recognition added successfully!&tab=recognition_awards");
        exit();
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error adding recognition: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
    $stmt->close();
}


if (isset($_POST['editRecognition'])) {
    $currentRecognitionID = $_POST['recognition_id'];
    $currentDocumentQuery = $connections->prepare("SELECT employee_image_path FROM recognitions WHERE id = ?");
    $currentDocumentQuery->bind_param("i", $currentRecognitionID);
    $currentDocumentQuery->execute();
    $currentDocumentResult = $currentDocumentQuery->get_result();
    $currentDocumentRow = $currentDocumentResult->fetch_assoc();
    $oldDocumentPath = $currentDocumentRow['employee_image_path'];

    $employee_image_path = handleFileUpload('edit_employee_image', $recognitionDocTargetDir, $connections, $oldDocumentPath);

    $stmt = $connections->prepare("UPDATE recognitions SET employee_image_path=?, employee_name=?, department=?, reward_type=?, message=? WHERE id=?");
    $stmt->bind_param(
        "sssssi",
        $employee_image_path,
        $_POST['employee_name_rec'],
        $_POST['department_rec'],
        $_POST['reward_type_rec'],
        $_POST['message_rec'],
        $_POST['recognition_id']
    );
    if ($stmt->execute()) {
        header("Location: recognition.php?success_message=Recognition updated successfully!&tab=recognition_awards");
        exit();
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error updating recognition: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
    $stmt->close();
}

if (isset($_GET['delete_recognition'])) {
    $id = intval($_GET['delete_recognition']);
    $documentToDeleteQuery = $connections->prepare("SELECT employee_image_path FROM recognitions WHERE id = ?");
    $documentToDeleteQuery->bind_param("i", $id);
    $documentToDeleteQuery->execute();
    $documentToDeleteResult = $documentToDeleteQuery->get_result();
    $documentToDeleteRow = $documentToDeleteResult->fetch_assoc();
    $fileToDelete = $documentToDeleteRow['employee_image_path'];

    $stmt = $connections->prepare("DELETE FROM recognitions WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        if (!empty($fileToDelete) && file_exists($fileToDelete)) {
            unlink($fileToDelete);
        }
        header("Location: recognition.php?success_message=Recognition deleted successfully!&tab=recognition_awards");
        exit();
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error deleting recognition: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
    $stmt->close();
}

if (isset($_POST['addFeedback'])) {
    $employee_id = $_POST['employee_id'];
    $feedback_date = $_POST['feedback_date'];
    $feedback_text = $_POST['feedback_text'];
    $recognition_id = !empty($_POST['recognition_id']) ? $_POST['recognition_id'] : NULL;
    $rating = 5;

    $stmt = $connections->prepare("INSERT INTO feedback (employee_id, feedback_text, rating, timestamp, recognition_id) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("isssi", $employee_id, $feedback_text, $rating, $feedback_date, $recognition_id);
        if ($stmt->execute()) {
            header("Location: recognition.php?success_message=Feedback added successfully!&tab=feedback_view");
            exit();
        } else {
            $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error adding feedback: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        }
        $stmt->close();
    } else {
        error_log("Recognition: Failed to prepare statement for adding feedback: " . $connections->error);
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error preparing statement to add feedback.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
}

if (isset($_POST['editFeedback'])) {
    $feedback_id = $_POST['feedback_id'];
    $employee_id = $_POST['employee_id'];
    $feedback_date = $_POST['feedback_date'];
    $feedback_text = $_POST['feedback_text'];
    $recognition_id = !empty($_POST['recognition_id']) ? $_POST['recognition_id'] : NULL;

    $stmt = $connections->prepare("UPDATE feedback SET employee_id=?, feedback_text=?, timestamp=?, recognition_id=? WHERE id=?");
    if ($stmt) {
        $stmt->bind_param("issii", $employee_id, $feedback_text, $feedback_date, $recognition_id, $feedback_id);
        if ($stmt->execute()) {
            header("Location: recognition.php?success_message=Feedback updated successfully!&tab=feedback_view");
            exit();
        } else {
            $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error updating feedback: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        }
        $stmt->close();
    } else {
        error_log("Recognition: Failed to prepare statement for editing feedback: " . $connections->error);
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error preparing statement to edit feedback.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
}

if (isset($_GET['delete_feedback'])) {
    $id = intval($_GET['delete_feedback']);
    $stmt = $connections->prepare("DELETE FROM feedback WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: recognition.php?success_message=Feedback deleted successfully!&tab=feedback_view");
            exit();
        } else {
            $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error deleting feedback: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        }
        $stmt->close();
    } else {
        error_log("Recognition: Failed to prepare statement for deleting feedback: " . $connections->error);
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error preparing statement to delete feedback.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
}


$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'program';

// Fetch data for Programs
$programs_data = $connections->query("SELECT id, name, description, reward_type, status, start_date, end_date, target_department, created_at FROM program ORDER BY created_at DESC");

// Fetch data for Recognitions/Awards
$recognitions_data = $connections->query("SELECT id, employee_image_path, employee_name, department, reward_type, message, created_at FROM recognitions ORDER BY created_at DESC");

// Fetch recognitions for linking in feedback forms
$recognitions_for_feedback_form = $connections->query("SELECT id, employee_name, reward_type, created_at FROM recognitions ORDER BY created_at DESC");

// Fetch all employees for dropdowns
$employees_for_feedback_form = $connections->query("SELECT EmployeeID, FullName FROM employeeprofilesetup ORDER BY FullName ASC");

// Fetch data for Feedback (from the 'feedback' table in hr1)
$feedback_data = $connections->query("
    SELECT f.id, f.employee_id, eps.FullName AS employee_name, f.timestamp AS feedback_date, f.feedback_text,
           r.employee_name AS recognition_employee_name, r.reward_type, f.recognition_id
    FROM feedback f
    LEFT JOIN employeeprofilesetup eps ON f.employee_id = eps.EmployeeID
    LEFT JOIN recognitions r ON f.recognition_id = r.id
    ORDER BY f.timestamp DESC
");


// Alert messages consolidation
if (isset($_GET['success_message'])) {
    $alert .= '<div id="alertBox" class="alert alert-success alert-dismissible fade show" role="alert">' . htmlspecialchars($_GET['success_message']) . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
} elseif (isset($_GET['error_message'])) {
    $alert .= '<div id="alertBox" class="alert alert-danger alert-dismissible fade show" role="alert">' . htmlspecialchars($_GET['error_message']) . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin - HM</title>
  <link rel="shortcut icon" href="../logo.png" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
  <link rel="stylesheet" href="../tm.css"/>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <nav class="col-md-2 d-none d-md-block sidebar py-4">
    <div class="text-center mb-4">
        <img src="../logo.png" width="100" alt="Logo"/>
        <h5>Hospital Management</h5>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item mb-2">
        <a class="nav-link <?= $active_tab == 'recruitment' || strpos($_SERVER['REQUEST_URI'], 'recruitment.php') !== false ? 'active' : '' ?>" href="recruitment.php">
            <i class="fa-solid fa-briefcase"></i> Recruitment
        </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'employee_profile_setup.php') !== false ? 'active' : '' ?>" href="employee_profile_setup.php">
                <i class="fa-solid fa-user-plus"></i> New Hired Onboarding
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'performance_management.php') !== false ? 'active' : '' ?>" href="performance_management.php">
                <i class="fa-solid fa-chart-line"></i> Performance Management
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'recognition.php') !== false ? 'active' : '' ?>" href="recognition.php">
                <i class="fa-solid fa-trophy"></i> Recognition
            </a>
        </li>
    </ul>
    <div class="admin-indicator">
        Admin Panel
    </div>
    </nav>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
        <div class="white-bg card-panel">
            <h4 class="text-center section-title">Recognition Management</h4>
            <?= $alert ?>

            <ul class="nav nav-tabs mb-3" id="recognitionTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $active_tab == 'program' ? 'active' : '' ?>" id="program-tab" data-bs-toggle="tab" data-bs-target="#program" type="button" role="tab" aria-controls="program" aria-selected="<?= $active_tab == 'program' ? 'true' : 'false' ?>">Programs</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $active_tab == 'recognition_awards' ? 'active' : '' ?>" id="recognition-awards-tab" data-bs-toggle="tab" data-bs-target="#recognition_awards" type="button" role="tab" aria-controls="recognition_awards" aria-selected="<?= $active_tab == 'recognition_awards' ? 'true' : 'false' ?>">Recognitions/Awards</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $active_tab == 'feedback_view' ? 'active' : '' ?>" id="feedback-view-tab" data-bs-toggle="tab" data-bs-target="#feedback_view" type="button" role="tab" aria-controls="feedback_view" aria-selected="<?= $active_tab == 'feedback_view' ? 'true' : 'false' ?>">Feedback</button>
                </li>
            </ul>

            <div class="tab-content" id="recognitionTabsContent">
                <div class="tab-pane fade <?= $active_tab == 'program' ? 'show active' : '' ?>" id="program" role="tabpanel" aria-labelledby="program-tab">
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProgramModal">
                            <i class="fa fa-plus"></i> Add New Program
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Program Name</th>
                                    <th>Description</th>
                                    <th>Reward Type</th>
                                    <th>Status</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Target Department</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($programs_data && $programs_data->num_rows > 0): $programs_data->data_seek(0); while ($program = $programs_data->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($program['name']) ?></td>
                                    <td><?= nl2br(htmlspecialchars(substr($program['description'], 0, 100))) . (strlen($program['description']) > 100 ? '...' : '') ?></td>
                                    <td><?= htmlspecialchars($program['reward_type'] ?? 'N/A') ?></td>
                                    <td><span class="badge bg-info"><?= htmlspecialchars(ucfirst($program['status'])) ?></span></td>
                                    <td><?= htmlspecialchars($program['start_date'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($program['end_date'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($program['target_department']) ?></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button class="action-icon-btn btn-edit" data-bs-toggle="modal" data-bs-target="#editProgramModal"
                                                data-id="<?= $program['id'] ?>"
                                                data-name="<?= htmlspecialchars($program['name']) ?>"
                                                data-description="<?= htmlspecialchars($program['description']) ?>"
                                                data-reward_type="<?= htmlspecialchars($program['reward_type'] ?? '') ?>"
                                                data-status="<?= htmlspecialchars($program['status']) ?>"
                                                data-start_date="<?= htmlspecialchars($program['start_date'] ?? '') ?>"
                                                data-end_date="<?= htmlspecialchars($program['end_date'] ?? '') ?>"
                                                data-target_department="<?= htmlspecialchars($program['target_department']) ?>">
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                            <a href="recognition.php?delete_program=<?= $program['id'] ?>&tab=program" class="action-icon-btn btn-delete" onclick="return confirm('Are you sure you want to delete this program?')">
                                                <i class="fa fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">No programs defined yet.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="modal fade" id="addProgramModal" tabindex="-1" aria-labelledby="addProgramModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <form method="POST" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addProgramModalLabel">Add New Program</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="add_program_name" class="form-label">Program Name</label>
                                        <input type="text" class="form-control" id="add_program_name" name="program_name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="add_program_description" class="form-label">Description</label>
                                        <textarea class="form-control" id="add_program_description" name="program_description" rows="3" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="add_reward_type" class="form-label">Reward Type</label>
                                        <input type="text" class="form-control" id="add_reward_type" name="reward_type" placeholder="e.g., Monetary, Certificate, Trophy">
                                    </div>
                                    <div class="mb-3">
                                        <label for="add_status" class="form-label">Status</label>
                                        <select class="form-select" id="add_status" name="status" required>
                                            <option value="draft">Draft</option>
                                            <option value="active">Active</option>
                                            <option value="completed">Completed</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="add_start_date" class="form-label">Start Date</label>
                                            <input type="date" class="form-control" id="add_start_date" name="start_date">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="add_end_date" class="form-label">End Date</label>
                                            <input type="date" class="form-control" id="add_end_date" name="end_date">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="add_target_department" class="form-label">Target Department</label>
                                        <input type="text" class="form-control" id="add_target_department" name="target_department" value="All Departments">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="addProgram" class="btn btn-primary">Save Program</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="modal fade" id="editProgramModal" tabindex="-1" aria-labelledby="editProgramModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <form method="POST" class="modal-content">
                                <input type="hidden" name="program_id" id="edit_program_id">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editProgramModalLabel">Edit Program</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="edit_program_name" class="form-label">Program Name</label>
                                        <input type="text" class="form-control" id="edit_program_name" name="program_name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_program_description" class="form-label">Description</label>
                                        <textarea class="form-control" id="edit_program_description" name="program_description" rows="3" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_reward_type" class="form-label">Reward Type</label>
                                        <input type="text" class="form-control" id="edit_reward_type" name="reward_type">
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_status" class="form-label">Status</label>
                                        <select class="form-select" id="edit_status" name="status" required>
                                            <option value="draft">Draft</option>
                                            <option value="active">Active</option>
                                            <option value="completed">Completed</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="edit_start_date" class="form-label">Start Date</label>
                                            <input type="date" class="form-control" id="edit_start_date" name="start_date">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="edit_end_date" class="form-label">End Date</label>
                                            <input type="date" class="form-control" id="edit_end_date" name="end_date">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_target_department" class="form-label">Target Department</label>
                                        <input type="text" class="form-control" id="edit_target_department" name="target_department">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="editProgram" class="btn btn-primary">Update Program</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade <?= $active_tab == 'recognition_awards' ? 'show active' : '' ?>" id="recognition_awards" role="tabpanel" aria-labelledby="recognition-awards-tab">
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRecognitionModal">
                            <i class="fa fa-plus"></i> Add New Recognition
                        </button>
                    </div>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        <?php if($recognitions_data && $recognitions_data->num_rows > 0): $recognitions_data->data_seek(0); while($recognition = $recognitions_data->fetch_assoc()): ?>
                            <div class="col">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <?php if (!empty($recognition['employee_image_path'])): ?>
                                            <img src="<?= htmlspecialchars($recognition['employee_image_path']) ?>" class="card-img-top mb-3" alt="Employee Image" style="max-height: 150px; object-fit: contain;">
                                        <?php endif; ?>
                                        <h5 class="card-title"><?= htmlspecialchars($recognition['employee_name']) ?></h5>
                                        <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($recognition['department']) ?></h6>
                                        <p class="card-text mb-1"><strong>Reward:</strong> <?= htmlspecialchars($recognition['reward_type']) ?></p>
                                        <p class="card-text mb-1"><strong>Message:</strong> <?= nl2br(htmlspecialchars(substr($recognition['message'], 0, 100))) . (strlen($recognition['message']) > 100 ? '...' : '') ?></p>
                                        <p class="card-text"><strong>Date:</strong> <?= htmlspecialchars(date("Y-m-d", strtotime($recognition['created_at']))) ?></p>
                                        <div class="d-flex justify-content-end gap-2">
                                            <button class="action-icon-btn btn-edit" data-bs-toggle="modal" data-bs-target="#editRecognitionModal"
                                                data-id="<?= $recognition['id'] ?>"
                                                data-employee_name="<?= htmlspecialchars($recognition['employee_name']) ?>"
                                                data-department="<?= htmlspecialchars($recognition['department']) ?>"
                                                data-reward_type="<?= htmlspecialchars($recognition['reward_type']) ?>"
                                                data-message="<?= htmlspecialchars($recognition['message']) ?>"
                                                data-image_path="<?= htmlspecialchars($recognition['employee_image_path'] ?? '') ?>">
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                            <a href="recognition.php?delete_recognition=<?= $recognition['id'] ?>&tab=recognition_awards" class="action-icon-btn btn-delete" onclick="return confirm('Are you sure you want to delete this recognition?')">
                                                <i class="fa fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; else: ?>
                            <div class="col-12 text-center">
                                <p>No recognitions or awards recorded yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="modal fade" id="addRecognitionModal" tabindex="-1" aria-labelledby="addRecognitionModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <form method="POST" class="modal-content" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addRecognitionModalLabel">Add New Recognition</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="add_employee_image" class="form-label">Employee Image (Optional)</label>
                                        <input type="file" class="form-control" id="add_employee_image" name="employee_image" accept="image/*">
                                    </div>
                                    <div class="mb-3">
                                        <label for="add_employee_name_rec" class="form-label">Employee Name</label>
                                        <input type="text" class="form-control" id="add_employee_name_rec" name="employee_name_rec" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="add_department_rec" class="form-label">Department</label>
                                        <input type="text" class="form-control" id="add_department_rec" name="department_rec" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="add_reward_type_rec" class="form-label">Reward Type</label>
                                        <input type="text" class="form-control" id="add_reward_type_rec" name="reward_type_rec" placeholder="e.g., Certificate, Bonus, Promotion" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="add_message_rec" class="form-label">Message</label>
                                        <textarea class="form-control" id="add_message_rec" name="message_rec" rows="3" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="addRecognition" class="btn btn-primary">Save Recognition</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="modal fade" id="editRecognitionModal" tabindex="-1" aria-labelledby="editRecognitionModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <form method="POST" class="modal-content" enctype="multipart/form-data">
                                <input type="hidden" name="recognition_id" id="edit_recognition_id">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editRecognitionModalLabel">Edit Recognition</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="edit_employee_image" class="form-label">Employee Image (re-upload to change)</label>
                                        <input type="file" class="form-control" id="edit_employee_image" name="edit_employee_image" accept="image/*">
                                        <small class="form-text text-muted">Current image: <span id="current_image_path"></span></small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_employee_name_rec" class="form-label">Employee Name</label>
                                        <input type="text" class="form-control" id="edit_employee_name_rec" name="employee_name_rec" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_department_rec" class="form-label">Department</label>
                                        <input type="text" class="form-control" id="edit_department_rec" name="department_rec" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_reward_type_rec" class="form-label">Reward Type</label>
                                        <input type="text" class="form-control" id="edit_reward_type_rec" name="reward_type_rec" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_message_rec" class="form-label">Message</label>
                                        <textarea class="form-control" id="edit_message_rec" name="message_rec" rows="3" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="editRecognition" class="btn btn-primary">Update Recognition</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade <?= $active_tab == 'feedback_view' ? 'show active' : '' ?>" id="feedback_view" role="tabpanel" aria-labelledby="feedback-view-tab">
                    <h5>Feedbacks can be added for the listed employees from Employee Profile Setup.</h5>
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addFeedbackModal">
                            <i class="fa fa-plus"></i> Add New Feedback
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Feedback Date</th>
                                    <th>Details</th>
                                    <th>Linked Recognition</th> <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($feedback_data && $feedback_data->num_rows > 0): $feedback_data->data_seek(0); while($feedback = $feedback_data->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($feedback['employee_name']) ?></td>
                                    <td><?= htmlspecialchars($feedback['feedback_date']) ?></td>
                                    <td><?= nl2br(htmlspecialchars(substr($feedback['feedback_text'], 0, 150))) . (strlen($feedback['feedback_text']) > 150 ? '...' : '') ?></td>
                                    <td>
                                        <?php if (!empty($feedback['recognition_employee_name'])): ?>
                                            <?= htmlspecialchars($feedback['recognition_employee_name']) ?> (<?= htmlspecialchars($feedback['reward_type']) ?>)
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td> <td>
                                        <div class="d-flex gap-2">
                                            <button class="action-icon-btn btn-view" data-bs-toggle="modal" data-bs-target="#viewFeedbackModal"
                                                data-id="<?= $feedback['id'] ?>"
                                                data-employee_name="<?= htmlspecialchars($feedback['employee_name']) ?>"
                                                data-feedback_date="<?= htmlspecialchars($feedback['feedback_date']) ?>"
                                                data-feedback_text="<?= htmlspecialchars($feedback['feedback_text']) ?>">
                                                <i class="fa fa-eye"></i> View
                                            </button>
                                            <button class="action-icon-btn btn-edit" data-bs-toggle="modal" data-bs-target="#editFeedbackModal"
                                                data-id="<?= $feedback['id'] ?>"
                                                data-employee_name="<?= htmlspecialchars($feedback['employee_name']) ?>"
                                                data-feedback_date="<?= htmlspecialchars($feedback['feedback_date']) ?>"
                                                data-feedback_text="<?= htmlspecialchars($feedback['feedback_text']) ?>"
                                                data-recognition_id="<?= htmlspecialchars($feedback['recognition_id'] ?? '') ?>"
                                                data-employee_id="<?= htmlspecialchars($feedback['employee_id']) ?>"> <i class="fa fa-edit"></i> Edit
                                            </button>
                                             <a href="recognition.php?delete_feedback=<?= $feedback['id'] ?>&tab=feedback_view" class="action-icon-btn btn-delete" onclick="return confirm('Are you sure you want to delete this feedback?');">
                                                <i class="fa fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No feedback recorded yet.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal fade" id="viewFeedbackModal" tabindex="-1" aria-labelledby="viewFeedbackModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewFeedbackModalLabel">View Feedback Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Employee Name:</strong> <span id="view_feedback_employeeName"></span></p>
                                    <p><strong>Feedback Date:</strong> <span id="view_feedback_date"></span></p>
                                    <p><strong>Feedback Details:</strong> <span id="view_feedback_text"></span></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editFeedbackModal" tabindex="-1" aria-labelledby="editFeedbackModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <form method="POST" class="modal-content" action="recognition.php">
                                <input type="hidden" name="feedback_id" id="edit_feedback_id">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editFeedbackModalLabel">Edit Feedback</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="edit_feedback_employee_id" class="form-label">Employee</label>
                                        <select class="form-select" id="edit_feedback_employee_id" name="employee_id" required>
                                            <option value="">-- Select Employee --</option>
                                            <?php
                                            if ($employees_for_feedback_form && $employees_for_feedback_form->num_rows > 0) {
                                                $employees_for_feedback_form->data_seek(0); // Reset pointer
                                                while ($emp_row = $employees_for_feedback_form->fetch_assoc()) {
                                                    echo '<option value="' . htmlspecialchars($emp_row['EmployeeID']) . '">' . htmlspecialchars($emp_row['FullName']) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_feedback_date" class="form-label">Feedback Date</label>
                                        <input type="date" class="form-control" id="edit_feedback_date" name="feedback_date" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_feedback_text" class="form-label">Feedback Details</label>
                                        <textarea class="form-control" id="edit_feedback_text" name="feedback_text" rows="4"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_recognition_id" class="form-label">Link to Recognition/Award (Optional)</label>
                                        <select class="form-select" id="edit_recognition_id" name="recognition_id">
                                            <option value="">-- No Link --</option>
                                            <?php
                                            if ($recognitions_for_feedback_form && $recognitions_for_feedback_form->num_rows > 0) {
                                                $recognitions_for_feedback_form->data_seek(0); // Reset pointer
                                                while ($rec_row = $recognitions_for_feedback_form->fetch_assoc()) {
                                                    echo '<option value="' . htmlspecialchars($rec_row['id']) . '">' . htmlspecialchars($rec_row['employee_name']) . ' (' . htmlspecialchars($rec_row['reward_type']) . ' - ' . date("Y-m-d", strtotime($rec_row['created_at'])) . ')</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="editFeedback" class="btn btn-primary">Update Feedback</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="modal fade" id="addFeedbackModal" tabindex="-1" aria-labelledby="addFeedbackModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <form method="POST" class="modal-content" action="recognition.php">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addFeedbackModalLabel">Add New Feedback</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="add_feedback_employee_id" class="form-label">Employee</label>
                                        <select class="form-select" id="add_feedback_employee_id" name="employee_id" required>
                                            <option value="">-- Select Employee --</option>
                                            <?php
                                            if ($employees_for_feedback_form && $employees_for_feedback_form->num_rows > 0) {
                                                $employees_for_feedback_form->data_seek(0); // Reset pointer
                                                while ($emp_row = $employees_for_feedback_form->fetch_assoc()) {
                                                    echo '<option value="' . htmlspecialchars($emp_row['EmployeeID']) . '">' . htmlspecialchars($emp_row['FullName']) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="add_feedback_date" class="form-label">Feedback Date</label>
                                        <input type="date" class="form-control" id="add_feedback_date" name="feedback_date" value="<?= date('Y-m-d') ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="add_feedback_text" class="form-label">Feedback Details</label>
                                        <textarea class="form-control" id="add_feedback_text" name="feedback_text" rows="4"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="add_recognition_id" class="form-label">Link to Recognition/Award (Optional)</label>
                                        <select class="form-select" id="add_recognition_id" name="recognition_id">
                                            <option value="">-- No Link --</option>
                                            <?php
                                            // Ensure this query is re-run or its result is passed
                                            if ($recognitions_for_feedback_form && $recognitions_for_feedback_form->num_rows > 0) {
                                                $recognitions_for_feedback_form->data_seek(0);
                                                while ($rec_row = $recognitions_for_feedback_form->fetch_assoc()) {
                                                    echo '<option value="' . htmlspecialchars($rec_row['id']) . '">' . htmlspecialchars($rec_row['employee_name']) . ' (' . htmlspecialchars($rec_row['reward_type']) . ' - ' . date("Y-m-d", strtotime($rec_row['created_at'])) . ')</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="addFeedback" class="btn btn-primary">Save Feedback</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="admin.js"></script>
</body>
</html>