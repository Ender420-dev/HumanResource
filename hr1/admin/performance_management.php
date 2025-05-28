<?php
include '../connections.php';

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

$performanceDocTargetDir = "../../uploads/performance_documents/";
if (!is_dir($performanceDocTargetDir)) {
    mkdir($performanceDocTargetDir, 0777, true);
}


// --- PHP Logic for CRUD Operations ---
$alert = "";

if (isset($_POST['addGoalKpi'])) {
    $stmt = $connections->prepare("INSERT INTO goals (employee_name, goal_description, kpi, target_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param(
        "ssss",
        $_POST['employee_name'],
        $_POST['goal_description'],
        $_POST['kpi_metric'],
        $_POST['target_date']
    );
    if ($stmt->execute()) {
        header("Location: performance_management.php?success_message=Goal added successfully!&tab=goal_setting");
        exit();
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error adding goal: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
    $stmt->close();
}

if (isset($_POST['editGoalKpi'])) {
    $stmt = $connections->prepare("UPDATE goals SET employee_name=?, goal_description=?, kpi=?, target_date=? WHERE id=?");
    $stmt->bind_param(
        "ssssi",
        $_POST['employee_name'],
        $_POST['goal_description'],
        $_POST['kpi_metric'],
        $_POST['target_date'],
        $_POST['goal_id']
    );
    if ($stmt->execute()) {
        header("Location: performance_management.php?success_message=Goal updated successfully!&tab=goal_setting");
        exit();
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error updating goal: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
    $stmt->close();
}

if (isset($_GET['delete_goal'])) {
    $id = intval($_GET['delete_goal']);
    $stmt = $connections->prepare("DELETE FROM goals WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: performance_management.php?success_message=Goal deleted successfully!&tab=goal_setting");
        exit();
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error deleting goal: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
    $stmt->close();
}

if (isset($_POST['addAppraisal'])) {
    $stmt = $connections->prepare("INSERT INTO appraisals (employee_name, review_period, performance_rating, comments) VALUES (?, ?, ?, ?)");
    $stmt->bind_param(
        "ssss",
        $_POST['employee_name'],
        $_POST['review_period'],
        $_POST['performance_rating'],
        $_POST['comments']
    );
    if ($stmt->execute()) {
        header("Location: performance_management.php?success_message=Appraisal added successfully!&tab=performance_appraisal");
        exit();
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error adding appraisal: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
    $stmt->close();
}

if (isset($_POST['editAppraisal'])) {
    $stmt = $connections->prepare("UPDATE appraisals SET employee_name=?, review_period=?, performance_rating=?, comments=? WHERE id=?");
    $stmt->bind_param(
        "ssssi",
        $_POST['employee_name'],
        $_POST['review_period'],
        $_POST['performance_rating'],
        $_POST['comments'],
        $_POST['appraisal_id']
    );
    if ($stmt->execute()) {
        header("Location: performance_management.php?success_message=Appraisal updated successfully!&tab=performance_appraisal");
        exit();
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error updating appraisal: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
    $stmt->close();
}

if (isset($_GET['delete_appraisal'])) {
    $id = intval($_GET['delete_appraisal']);
    $stmt = $connections->prepare("DELETE FROM appraisals WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: performance_management.php?success_message=Appraisal deleted successfully!&tab=performance_appraisal");
        exit();
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error deleting appraisal: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
    $stmt->close();
}

if (isset($_POST['addFeedback'])) {
    $stmt = $connections->prepare("INSERT INTO performance_feedback (employee_name, feedback_date, feedback_text) VALUES (?, ?, ?)");
    $stmt->bind_param(
        "sss",
        $_POST['employee_name'],
        $_POST['feedback_date'],
        $_POST['feedback_text']
    );
    if ($stmt->execute()) {
        header("Location: performance_management.php?success_message=Feedback added successfully!&tab=continuous_feedback");
        exit();
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error adding feedback: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
    $stmt->close();
}

if (isset($_POST['editFeedback'])) {
    $stmt = $connections->prepare("UPDATE performance_feedback SET employee_name=?, feedback_date=?, feedback_text=? WHERE id=?");
    $stmt->bind_param(
        "sssi",
        $_POST['employee_name'],
        $_POST['feedback_date'],
        $_POST['feedback_text'],
        $_POST['feedback_id']
    );
    if ($stmt->execute()) {
        header("Location: performance_management.php?success_message=Feedback updated successfully!&tab=continuous_feedback");
        exit();
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error updating feedback: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
    $stmt->close();
}

if (isset($_GET['delete_feedback'])) {
    $id = intval($_GET['delete_feedback']);
    $stmt = $connections->prepare("DELETE FROM performance_feedback WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: performance_management.php?success_message=Feedback deleted successfully!&tab=continuous_feedback");
        exit();
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error deleting feedback: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
    $stmt->close();
}


// Determine the active tab based on GET parameter or default
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'goal_setting';

// Fetch data for Goal Setting & KPI Tracking
$goals_kpis_data = $connections->query("SELECT id, employee_name, goal_description, kpi, target_date FROM goals ORDER BY target_date DESC");

// Fetch data for Performance Appraisal
$appraisals_data = $connections->query("SELECT id, employee_name, review_period, performance_rating, comments FROM appraisals ORDER BY review_period DESC");

// Fetch data for Continuous Feedback
$feedback_data = $connections->query("SELECT id, employee_name, feedback_date, feedback_text FROM performance_feedback ORDER BY feedback_date DESC");

// Fetch employee names for dropdowns
$employee_names_query = $connections->query("SELECT DISTINCT FullName FROM employeeprofilesetup ORDER BY FullName ASC");
$employee_names = [];
if ($employee_names_query) {
    while ($row = $employee_names_query->fetch_assoc()) {
        $employee_names[] = $row['FullName'];
    }
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
            <h4 class="text-center section-title">Performance Management</h4>
            <?= $alert ?>

            <ul class="nav nav-tabs mb-3" id="performanceTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $active_tab == 'goal_setting' ? 'active' : '' ?>" id="goal-setting-tab" data-bs-toggle="tab" data-bs-target="#goal_setting" type="button" role="tab" aria-controls="goal_setting" aria-selected="<?= $active_tab == 'goal_setting' ? 'true' : 'false' ?>">Goal Setting & KPI Tracking</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $active_tab == 'performance_appraisal' ? 'active' : '' ?>" id="performance-appraisal-tab" data-bs-toggle="tab" data-bs-target="#performance_appraisal" type="button" role="tab" aria-controls="performance_appraisal" aria-selected="<?= $active_tab == 'performance_appraisal' ? 'true' : 'false' ?>">Performance Appraisal</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $active_tab == 'continuous_feedback' ? 'active' : '' ?>" id="continuous-feedback-tab" data-bs-toggle="tab" data-bs-target="#continuous_feedback" type="button" role="tab" aria-controls="continuous_feedback" aria-selected="<?= $active_tab == 'continuous_feedback' ? 'true' : 'false' ?>">Continuous Feedback</button>
                </li>
            </ul>

            <div class="tab-content" id="performanceTabsContent">
                <div class="tab-pane fade <?= $active_tab == 'goal_setting' ? 'show active' : '' ?>" id="goal_setting" role="tabpanel" aria-labelledby="goal-setting-tab">
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addGoalKpiModal">
                            <i class="fa fa-plus"></i> Add New Goal/KPI
                        </button>
                    </div>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        <?php if ($goals_kpis_data && $goals_kpis_data->num_rows > 0): $goals_kpis_data->data_seek(0); while ($goal = $goals_kpis_data->fetch_assoc()): ?>
                            <div class="col">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($goal['goal_description']) ?></h5>
                                        <h6 class="card-subtitle mb-2 text-muted">Employee: <?= htmlspecialchars($goal['employee_name']) ?></h6>
                                        <p class="card-text mb-1"><strong>KPI:</strong> <?= htmlspecialchars($goal['kpi']) ?></p>
                                        <p class="card-text mb-1"><strong>Target Date:</strong> <?= htmlspecialchars($goal['target_date']) ?></p>
                                        <?php
                                            $status_text = "N/A";
                                            $status_class = "bg-secondary";
                                            // Simple status logic based on target_date
                                            if (strtotime($goal['target_date']) < time()) {
                                                $status_text = "Overdue";
                                                $status_class = "bg-danger";
                                            } else {
                                                $status_text = "On Track";
                                                $status_class = "bg-success";
                                            }
                                        ?>
                                        <p class="card-text"><strong>Status:</strong> <span class="badge <?= $status_class ?>"><?= $status_text ?></span></p>
                                        <div class="d-flex justify-content-end gap-2">
                                            <button class="action-icon-btn btn-edit" data-bs-toggle="modal" data-bs-target="#editGoalKpiModal"
                                                data-id="<?= $goal['id'] ?>"
                                                data-employee_name="<?= htmlspecialchars($goal['employee_name']) ?>"
                                                data-goal_description="<?= htmlspecialchars($goal['goal_description']) ?>"
                                                data-kpi="<?= htmlspecialchars($goal['kpi']) ?>"
                                                data-target_date="<?= htmlspecialchars($goal['target_date']) ?>">
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                            <a href="performance_management.php?delete_goal=<?= $goal['id'] ?>&tab=goal_setting" class="action-icon-btn btn-delete" onclick="return confirm('Are you sure you want to delete this goal?')">
                                                <i class="fa fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; else: ?>
                            <div class="col-12 text-center">
                                <p>No goals or KPIs defined yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="modal fade" id="addGoalKpiModal" tabindex="-1" aria-labelledby="addGoalKpiModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <form method="POST" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addGoalKpiModalLabel">Add New Goal/KPI</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="add_employeeNameGoal" class="form-label">Employee Name</label>
                                        <input type="text" class="form-control" id="add_employeeNameGoal" name="employee_name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="add_goalDescription" class="form-label">Goal</label>
                                        <textarea class="form-control" id="add_goalDescription" name="goal_description" rows="3" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="add_kpiMetric" class="form-label">KPI</label>
                                        <input type="text" class="form-control" id="add_kpiMetric" name="kpi_metric" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="add_targetDateGoal" class="form-label">Target Date</label>
                                        <input type="date" class="form-control" id="add_targetDateGoal" name="target_date" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="addGoalKpi" class="btn btn-primary">Save Goal/KPI</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="modal fade" id="editGoalKpiModal" tabindex="-1" aria-labelledby="editGoalKpiModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <form method="POST" class="modal-content">
                                <input type="hidden" name="goal_id" id="edit_goal_id">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editGoalKpiModalLabel">Edit Goal/KPI</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="edit_employeeNameGoal" class="form-label">Employee Name</label>
                                        <input type="text" class="form-control" id="edit_employeeNameGoal" name="employee_name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_goalDescription" class="form-label">Goal</label>
                                        <textarea class="form-control" id="edit_goalDescription" name="goal_description" rows="3" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_kpiMetric" class="form-label">KPI</label>
                                        <input type="text" class="form-control" id="edit_kpiMetric" name="kpi_metric" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_targetDateGoal" class="form-label">Target Date</label>
                                        <input type="date" class="form-control" id="edit_targetDateGoal" name="target_date" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="editGoalKpi" class="btn btn-primary">Update Goal/KPI</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade <?= $active_tab == 'performance_appraisal' ? 'show active' : '' ?>" id="performance_appraisal" role="tabpanel" aria-labelledby="performance-appraisal-tab">
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAppraisalModal">
                            <i class="fa fa-plus"></i> Add New Appraisal
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Review Period</th>
                                    <th>Overall Rating</th>
                                    <th>Comments</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($appraisals_data && $appraisals_data->num_rows > 0): $appraisals_data->data_seek(0); while($appraisal = $appraisals_data->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($appraisal['employee_name']) ?></td>
                                    <td><?= htmlspecialchars($appraisal['review_period']) ?></td>
                                    <td><?= htmlspecialchars($appraisal['performance_rating']) ?></td>
                                    <td><?= nl2br(htmlspecialchars(substr($appraisal['comments'], 0, 100))) . (strlen($appraisal['comments']) > 100 ? '...' : '') ?></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button class="action-icon-btn btn-view" data-bs-toggle="modal" data-bs-target="#viewAppraisalModal"
                                                data-id="<?= $appraisal['id'] ?>"
                                                data-employee_name="<?= htmlspecialchars($appraisal['employee_name']) ?>"
                                                data-review_period="<?= htmlspecialchars($appraisal['review_period']) ?>"
                                                data-performance_rating="<?= htmlspecialchars($appraisal['performance_rating']) ?>"
                                                data-comments="<?= htmlspecialchars($appraisal['comments']) ?>">
                                                <i class="fa fa-eye"></i> View
                                            </button>
                                            <button class="action-icon-btn btn-edit" data-bs-toggle="modal" data-bs-target="#editAppraisalModal"
                                                data-id="<?= $appraisal['id'] ?>"
                                                data-employee_name="<?= htmlspecialchars($appraisal['employee_name']) ?>"
                                                data-review_period="<?= htmlspecialchars($appraisal['review_period']) ?>"
                                                data-performance_rating="<?= htmlspecialchars($appraisal['performance_rating']) ?>"
                                                data-comments="<?= htmlspecialchars($appraisal['comments']) ?>">
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                            <a href="performance_management.php?delete_appraisal=<?= $appraisal['id'] ?>&tab=performance_appraisal" class="action-icon-btn btn-delete" onclick="return confirm('Are you sure you want to delete this appraisal?')">
                                                <i class="fa fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No performance appraisals recorded yet.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="modal fade" id="addAppraisalModal" tabindex="-1" aria-labelledby="addAppraisalModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <form method="POST" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addAppraisalModalLabel">Add New Appraisal</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="add_appraisal_employeeName" class="form-label">Employee Name</label>
                                        <select class="form-select" id="add_appraisal_employeeName" name="employee_name" required>
                                            <option value="">-- Select Employee --</option>
                                            <?php foreach ($employee_names as $name): ?>
                                                <option value="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($name) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="add_appraisal_reviewPeriod" class="form-label">Review Period (YYYY-MM)</label>
                                        <input type="month" class="form-control" id="add_appraisal_reviewPeriod" name="review_period" pattern="\d{4}-\d{2}" placeholder="e.g., 2025-05" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="add_appraisal_rating" class="form-label">Performance Rating</label>
                                        <select class="form-select" id="add_appraisal_rating" name="performance_rating" required>
                                            <option value="">-- Select Rating --</option>
                                            <option value="5 = Exceptional">5 = Exceptional</option>
                                            <option value="4 = Proficient">4 = Proficient</option>
                                            <option value="3 = Developing">3 = Developing</option>
                                            <option value="2 = Below Expectations">2 = Below Expectations</option>
                                            <option value="1 = Unacceptable">1 = Unacceptable</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="add_appraisal_comments" class="form-label">Comments</label>
                                        <textarea class="form-control" id="add_appraisal_comments" name="comments" rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="addAppraisal" class="btn btn-primary">Save Appraisal</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="modal fade" id="viewAppraisalModal" tabindex="-1" aria-labelledby="viewAppraisalModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewAppraisalModalLabel">View Appraisal Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Employee Name:</strong> <span id="view_appraisal_employeeName"></span></p>
                                    <p><strong>Review Period:</strong> <span id="view_appraisal_reviewPeriod"></span></p>
                                    <p><strong>Performance Rating:</strong> <span id="view_appraisal_rating"></span></p>
                                    <p><strong>Comments:</strong> <span id="view_appraisal_comments"></span></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editAppraisalModal" tabindex="-1" aria-labelledby="editAppraisalModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <form method="POST" class="modal-content">
                                <input type="hidden" name="appraisal_id" id="edit_appraisal_id">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editAppraisalModalLabel">Edit Appraisal</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="edit_appraisal_employeeName" class="form-label">Employee Name</label>
                                        <select class="form-select" id="edit_appraisal_employeeName" name="employee_name" required>
                                            <option value="">-- Select Employee --</option>
                                            <?php foreach ($employee_names as $name): ?>
                                                <option value="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($name) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_appraisal_reviewPeriod" class="form-label">Review Period (YYYY-MM)</label>
                                        <input type="month" class="form-control" id="edit_appraisal_reviewPeriod" name="review_period" pattern="\d{4}-\d{2}" placeholder="e.g., 2025-05" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_appraisal_rating" class="form-label">Performance Rating</label>
                                        <select class="form-select" id="edit_appraisal_rating" name="performance_rating" required>
                                            <option value="">-- Select Rating --</option>
                                            <option value="5 = Exceptional">5 = Exceptional</option>
                                            <option value="4 = Proficient">4 = Proficient</option>
                                            <option value="3 = Developing">3 = Developing</option>
                                            <option value="2 = Below Expectations">2 = Below Expectations</option>
                                            <option value="1 = Unacceptable">1 = Unacceptable</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_appraisal_comments" class="form-label">Comments</label>
                                        <textarea class="form-control" id="edit_appraisal_comments" name="comments" rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="editAppraisal" class="btn btn-primary">Update Appraisal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade <?= $active_tab == 'continuous_feedback' ? 'show active' : '' ?>" id="continuous_feedback" role="tabpanel" aria-labelledby="continuous-feedback-tab">
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
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($feedback_data && $feedback_data->num_rows > 0): $feedback_data->data_seek(0); while($feedback = $feedback_data->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($feedback['employee_name']) ?></td>
                                    <td><?= htmlspecialchars($feedback['feedback_date']) ?></td>
                                    <td><?= nl2br(htmlspecialchars(substr($feedback['feedback_text'], 0, 100))) . (strlen($feedback['feedback_text']) > 100 ? '...' : '') ?></td>
                                    <td>
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
                                                data-feedback_text="<?= htmlspecialchars($feedback['feedback_text']) ?>">
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                            <a href="performance_management.php?delete_feedback=<?= $feedback['id'] ?>&tab=continuous_feedback" class="action-icon-btn btn-delete" onclick="return confirm('Are you sure you want to delete this feedback?')">
                                                <i class="fa fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">No continuous feedback recorded yet.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="modal fade" id="addFeedbackModal" tabindex="-1" aria-labelledby="addFeedbackModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <form method="POST" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addFeedbackModalLabel">Add New Feedback</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="add_feedback_employeeName" class="form-label">Employee Name</label>
                                        <input type="text" class="form-control" id="add_feedback_employeeName" name="employee_name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="add_feedback_date" class="form-label">Feedback Date</label>
                                        <input type="date" class="form-control" id="add_feedback_date" name="feedback_date" value="<?= date('Y-m-d') ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="add_feedback_text" class="form-label">Feedback Details</label>
                                        <textarea class="form-control" id="add_feedback_text" name="feedback_text" rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="addFeedback" class="btn btn-primary">Save Feedback</button>
                                </div>
                            </form>
                        </div>
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
                            <form method="POST" class="modal-content">
                                <input type="hidden" name="feedback_id" id="edit_feedback_id">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editFeedbackModalLabel">Edit Feedback</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="edit_feedback_employeeName" class="form-label">Employee Name</label>
                                        <input type="text" class="form-control" id="edit_feedback_employeeName" name="employee_name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_feedback_date" class="form-label">Feedback Date</label>
                                        <input type="date" class="form-control" id="edit_feedback_date" name="feedback_date" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_feedback_text" class="form-label">Feedback Details</label>
                                        <textarea class="form-control" id="edit_feedback_text" name="feedback_text" rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="editFeedback" class="btn btn-primary">Update Feedback</button>
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