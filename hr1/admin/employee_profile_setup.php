<?php
include '../connections.php'; // This already includes $connections_hr2 and $connections_hr3

$targetDir = "../../uploads/employee_documents/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

function handleFileUpload($fileInputName, $targetDir, $conn, $currentDocumentPath = '') {
    $documentPath = $currentDocumentPath;
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] == UPLOAD_ERR_OK) {
        $fileName = uniqid() . "_" . basename($_FILES[$fileInputName]["name"]);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES[$fileInputName]["tmp_name"], $targetFile)) {
            if (!empty($currentDocumentPath) && file_exists($currentDocumentPath)) {
                unlink($currentDocumentPath);
            }
            $documentPath = $targetFile;
        }
    }
    return $documentPath;
}

$alert = "";

// Fetch applicants not yet in employeeprofilesetup for the dropdown
$potential_employees_data = [];
$sql_applicants = "SELECT a.applicantID, a.name, a.sex AS gender, jp.title AS position_title
                   FROM applicant a
                   LEFT JOIN jobposting jp ON a.jobpostingID = jp.jobpostingID
                   WHERE a.applicantID NOT IN (SELECT CAST(EmployeeID AS UNSIGNED) FROM employeeprofilesetup WHERE EmployeeID REGEXP '^[0-9]+$')
                   ORDER BY a.name ASC";
$result_applicants = $connections->query($sql_applicants);
if ($result_applicants) {
    while ($applicant_row = $result_applicants->fetch_assoc()) {
        $potential_employees_data[] = $applicant_row;
    }
} else {
    // $alert .= '<div class="alert alert-danger">Error fetching applicants: ' . $connections->error . '</div>';
    error_log("Error fetching applicants for dropdown: " . $connections->error);
}


if (isset($_POST['addEmployee'])) {
    $documentPath = handleFileUpload('DocumentSubmitted', $targetDir, $connections);
    $acquiredSkills = $_POST['AcquiredSkillsOrQualifications'] ?? NULL;
    $employeeID = $_POST['EmployeeID']; 
    $fullName = $_POST['FullName'];
    $gender = $_POST['Gender'];
    $position = $_POST['Position'];
    $birthday = $_POST['Birthday'];
    $applicationDate = $_POST['ApplicationDate'];


    $stmt = $connections->prepare("INSERT INTO employeeprofilesetup (EmployeeID, FullName, Gender, Position, Birthday, ApplicationDate, DocumentSubmitted, AcquiredSkillsOrQualifications) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "ssssssss",
        $employeeID,
        $fullName,
        $gender,
        $position,
        $birthday,
        $applicationDate,
        $documentPath,
        $acquiredSkills
    );

    if ($stmt->execute()) {
        // HR1 employeeprofilesetup record is successfully inserted.
        
        // START: Added code to insert placeholder into hr3.employee_schedules
        if (isset($connections_hr3) && $connections_hr3) {
            $hr1_employee_id_for_hr3 = $employeeID; // EmployeeID from HR1
            
            // Define placeholder values for the HR3 schedule
            $default_shift_id_hr3 = 1; // Assuming shift_id = 1 is a valid default (e.g., 'Morning')
            $default_schedule_date_hr3 = $applicationDate; // Using ApplicationDate as placeholder schedule_date
            $default_is_published_hr3 = 0; // Assuming 0 for not published

            // department_id in hr3.employee_schedules is nullable, so we can omit it or explicitly set to NULL.
            // For this example, it's omitted, and the DB default (likely NULL) will apply if not specified.

            $stmt_hr3_schedule = $connections_hr3->prepare(
                "INSERT INTO employee_schedules (employee_id, shift_id, schedule_date, is_published) 
                 VALUES (?, ?, ?, ?)"
            );

            if ($stmt_hr3_schedule) {
                // Ensure $hr1_employee_id_for_hr3 is treated as an integer for binding if hr3.employee_schedules.employee_id is INT
                $int_employee_id_for_hr3 = (int)$hr1_employee_id_for_hr3;
                
                $stmt_hr3_schedule->bind_param("iisi", 
                    $int_employee_id_for_hr3, 
                    $default_shift_id_hr3, 
                    $default_schedule_date_hr3,
                    $default_is_published_hr3
                );

                if (!$stmt_hr3_schedule->execute()) {
                    error_log("Failed to insert placeholder schedule into hr3.employee_schedules for EmployeeID " . $hr1_employee_id_for_hr3 . ": " . $stmt_hr3_schedule->error);
                    // Optionally, set an alert for the user about this partial failure
                    // $_SESSION['alert_hr3_fail'] = "HR1 profile created, but failed to create placeholder HR3 schedule.";
                }
                $stmt_hr3_schedule->close();
            } else {
                error_log("Failed to prepare statement for hr3.employee_schedules: " . $connections_hr3->error);
            }
        } else {
            error_log("HR3 database connection (\$connections_hr3) is not available. Cannot insert placeholder schedule.");
        }
        // END: Added code

        header("Location: employee_profile_setup.php?employee_added=1");
        exit();
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error adding employee to HR1: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
    $stmt->close();
}

if (isset($_POST['editEmployee'])) {
    $currentEmployeeID = $_POST['EmployeeID'];
    $currentDocumentQuery = $connections->prepare("SELECT DocumentSubmitted FROM employeeprofilesetup WHERE EmployeeID = ?");
    $currentDocumentQuery->bind_param("s", $currentEmployeeID);
    $currentDocumentQuery->execute();
    $currentDocumentResult = $currentDocumentQuery->get_result();
    $currentDocumentRow = $currentDocumentResult->fetch_assoc();
    $oldDocumentPath = $currentDocumentRow['DocumentSubmitted'] ?? '';

    $documentPath = handleFileUpload('editDocumentSubmitted', $targetDir, $connections, $oldDocumentPath);
    $acquiredSkills = $_POST['AcquiredSkillsOrQualifications'] ?? NULL;

    $stmt = $connections->prepare("UPDATE employeeprofilesetup SET FullName=?, Gender=?, Position=?, Birthday=?, ApplicationDate=?, DocumentSubmitted=?, AcquiredSkillsOrQualifications=? WHERE EmployeeID=?");
    $stmt->bind_param(
        "ssssssss",
        $_POST['FullName'],
        $_POST['Gender'],
        $_POST['Position'],
        $_POST['Birthday'],
        $_POST['ApplicationDate'],
        $documentPath,
        $acquiredSkills,
        $_POST['EmployeeID']
    );
    if ($stmt->execute()) {
        header("Location: employee_profile_setup.php?employee_edited=1");
        exit();
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error updating employee: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
    $stmt->close();
}

if (isset($_GET['delete_document_id'])) {
    $employeeID = $_GET['delete_document_id'];
    $documentQuery = $connections->prepare("SELECT DocumentSubmitted FROM employeeprofilesetup WHERE EmployeeID = ?");
    $documentQuery->bind_param("s", $employeeID);
    $documentQuery->execute();
    $documentResult = $documentQuery->get_result();
    if ($documentResult && $documentResult->num_rows > 0) {
        $documentRow = $documentResult->fetch_assoc();
        $fileToDelete = $documentRow['DocumentSubmitted'];

        $updateStmt = $connections->prepare("UPDATE employeeprofilesetup SET DocumentSubmitted = NULL WHERE EmployeeID = ?");
        $updateStmt->bind_param("s", $employeeID);
        if ($updateStmt->execute()) {
            if (!empty($fileToDelete) && file_exists($fileToDelete)) {
                unlink($fileToDelete);
            }
            header("Location: employee_profile_setup.php?employee_edited=1&tab=details"); // Example tab
            exit();
        } else {
            $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error deleting document record: ' . $updateStmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        }
        $updateStmt->close();
    } else {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">Document record not found.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
}


if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $documentToDeleteQuery = $connections->prepare("SELECT DocumentSubmitted FROM employeeprofilesetup WHERE EmployeeID = ?");
    $documentToDeleteQuery->bind_param("s", $id);
    $documentToDeleteQuery->execute();
    $documentToDeleteResult = $documentToDeleteQuery->get_result();
    $fileToDelete = null;
    if($documentToDeleteResult && $documentToDeleteRow = $documentToDeleteResult->fetch_assoc()){
        $fileToDelete = $documentToDeleteRow['DocumentSubmitted'];
    }

    $stmt = $connections->prepare("DELETE FROM employeeprofilesetup WHERE EmployeeID = ?");
    $stmt->bind_param("s", $id);
    if ($stmt->execute()) {
        if (!empty($fileToDelete) && file_exists($fileToDelete)) {
            unlink($fileToDelete);
        }
        header("Location: employee_profile_setup.php?employee_deleted=1");
        exit();
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error deleting employee: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
    $stmt->close();
}

$employees_result = $connections->query("SELECT * FROM employeeprofilesetup ORDER BY EmployeeID DESC");
$employees_data = [];
if ($employees_result) {
    while ($row = $employees_result->fetch_assoc()) {
        $employees_data[] = $row;
    }
}

// Fetch ALL Learning Progress from HR2 for general display
$learning_progress_map = [];
if (isset($connections_hr2) && $connections_hr2) {
    $lp_sql_all = "SELECT lp.EMPLOYEE_ID, lp.PROGRESS, lp.STATUS, tp.PROGRAM_NAME
                   FROM learning_progress lp
                   JOIN training_program tp ON lp.COURSE = tp.PROGRAM_ID
                   ORDER BY lp.EMPLOYEE_ID, lp.END DESC";
    $lp_result_all = $connections_hr2->query($lp_sql_all);
    if ($lp_result_all) {
        while ($lp_row_all = $lp_result_all->fetch_assoc()) {
            $learning_progress_map[(string)$lp_row_all['EMPLOYEE_ID']][] = $lp_row_all;
        }
    } else {
        error_log("Error fetching all learning progress from HR2: " . $connections_hr2->error);
        $alert .= '<div class="alert alert-warning alert-dismissible fade show" role="alert">Could not fetch learning progress from HR2. ' . $connections_hr2->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
} else {
    $alert .= '<div class="alert alert-warning alert-dismissible fade show" role="alert">HR2 Database connection not available. Learning progress cannot be displayed.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

// Fetch COMPLETED Learning Progress from HR2 for modal reference
$completed_learning_map = [];
if (isset($connections_hr2) && $connections_hr2) {
    $clp_sql = "SELECT lp.EMPLOYEE_ID, tp.PROGRAM_NAME
                FROM learning_progress lp
                JOIN training_program tp ON lp.COURSE = tp.PROGRAM_ID
                WHERE lp.STATUS = 'Complete' OR lp.STATUS = 'Completed' 
                ORDER BY lp.EMPLOYEE_ID, lp.END DESC";
    $clp_result = $connections_hr2->query($clp_sql);
    if ($clp_result) {
        while ($clp_row = $clp_result->fetch_assoc()) {
            $completed_learning_map[(string)$clp_row['EMPLOYEE_ID']][] = $clp_row['PROGRAM_NAME'];
        }
    } else {
        error_log("Error fetching completed learning progress from HR2: " . $connections_hr2->error);
    }
}

// Fetch Employee Schedules from HR3
$employee_schedules_map = [];
if (isset($connections_hr3) && $connections_hr3) {
    $schedule_sql = "SELECT es.employee_id, es.schedule_date, s.shift_name, s.start_time, s.end_time, dep.department_name AS schedule_department_name
                     FROM employee_schedules es
                     LEFT JOIN shifts s ON es.shift_id = s.shift_id
                     LEFT JOIN departments dep ON es.department_id = dep.department_id
                     ORDER BY es.employee_id, es.schedule_date DESC";
    $schedule_result = $connections_hr3->query($schedule_sql);
    if ($schedule_result) {
        while ($schedule_row = $schedule_result->fetch_assoc()) {
            $employee_schedules_map[(string)$schedule_row['employee_id']][] = $schedule_row;
        }
    } else {
        error_log("Error fetching employee schedules from HR3: " . $connections_hr3->error);
        $alert .= '<div class="alert alert-warning alert-dismissible fade show" role="alert">Could not fetch employee schedules from HR3. ' . $connections_hr3->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
} else {
    $alert .= '<div class="alert alert-warning alert-dismissible fade show" role="alert">HR3 Database connection not available. Employee schedules cannot be displayed.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}


if (isset($_GET['employee_added'])) { $alert .= '<div id="alertBox" class="alert alert-success alert-dismissible fade show" role="alert">Employee added successfully!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';}
elseif (isset($_GET['employee_edited'])) { $alert .= '<div id="alertBox" class="alert alert-info alert-dismissible fade show" role="alert">Employee updated successfully!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';}
elseif (isset($_GET['employee_deleted'])) { $alert .= '<div id="alertBox" class="alert alert-danger alert-dismissible fade show" role="alert">Employee deleted successfully!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';}

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
        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'recruitment.php') !== false ? 'active' : '' ?>" href="recruitment.php">
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
            <h4 class="text-center section-title">Employee Profile Setup</h4>
            <?= $alert ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Employee List</h5>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                    <i class="fa fa-plus"></i> Add Employee from Applicant Pool
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Employee ID</th>
                            <th>Full Name</th>
                            <th>Gender</th>
                            <th>Position</th>
                            <th>Birthday</th>
                            <th>Application Date</th>
                            <th>Document Submitted</th>
                            <th>Acquired Skills/Qualifications (HR1)</th>
                            <th>Learning Progress (HR2)</th>
                            <th>Employee Schedules (HR3)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employees_data as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['EmployeeID']) ?></td>
                            <td><?= htmlspecialchars($row['FullName']) ?></td>
                            <td><?= htmlspecialchars($row['Gender']) ?></td>
                            <td><?= htmlspecialchars($row['Position']) ?></td>
                            <td><?= htmlspecialchars($row['Birthday']) ?></td>
                            <td><?= htmlspecialchars($row['ApplicationDate']) ?></td>
                            <td>
                            <?php if (!empty($row['DocumentSubmitted'])): ?>
                                <a href="<?= htmlspecialchars($row['DocumentSubmitted']) ?>" target="_blank">View Document</a>
                            <?php else: ?>
                                No Document
                            <?php endif; ?>
                            </td>
                            <td><?= nl2br(htmlspecialchars($row['AcquiredSkillsOrQualifications'] ?? 'N/A')) ?></td>
                            <td>
                                <?php
                                $currentEmployeeMapID_lp = (string)$row['EmployeeID'];
                                if (isset($learning_progress_map[$currentEmployeeMapID_lp])) {
                                    foreach ($learning_progress_map[$currentEmployeeMapID_lp] as $progress_item) {
                                        echo htmlspecialchars($progress_item['PROGRAM_NAME']) . ': ' .
                                             htmlspecialchars($progress_item['PROGRESS']) . '% (' .
                                             htmlspecialchars($progress_item['STATUS']) . ')<br>';
                                    }
                                } else {
                                    echo 'No learning progress found.';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $currentEmployeeIdStr_schedule = (string)$row['EmployeeID'];
                                if (isset($employee_schedules_map[$currentEmployeeIdStr_schedule]) && !empty($employee_schedules_map[$currentEmployeeIdStr_schedule])) {
                                    $schedules_to_show = array_slice($employee_schedules_map[$currentEmployeeIdStr_schedule], 0, 2); // Show first 2
                                    foreach ($schedules_to_show as $schedule_item) {
                                        echo htmlspecialchars($schedule_item['schedule_date']) . ": " .
                                             htmlspecialchars($schedule_item['shift_name'] ?? 'N/A') .
                                             (isset($schedule_item['start_time']) ? " (" . htmlspecialchars(date("g:i A", strtotime($schedule_item['start_time']))) . " - " . htmlspecialchars(date("g:i A", strtotime($schedule_item['end_time']))) . ")" : "") .
                                             (isset($schedule_item['schedule_department_name']) && $schedule_item['schedule_department_name'] ? " @ " . htmlspecialchars($schedule_item['schedule_department_name']) : "") .
                                             "<br>";
                                    }
                                    if (count($employee_schedules_map[$currentEmployeeIdStr_schedule]) > 2) {
                                        echo "<small><em>(more...)</em></small>";
                                    }
                                } else {
                                    echo 'No schedules found in HR3.';
                                }
                                ?>
                            </td>
                            <td>
                            <button class="action-icon-btn btn-view" data-bs-toggle="modal" data-bs-target="#viewEmployeeModal<?= htmlspecialchars($row['EmployeeID']) ?>">
                                <i class="fa fa-eye"></i> View
                            </button>
                            <button class="action-icon-btn btn-edit" data-bs-toggle="modal" data-bs-target="#editEmployeeModal<?= htmlspecialchars($row['EmployeeID']) ?>">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                            <a href="employee_profile_setup.php?delete_id=<?= htmlspecialchars($row['EmployeeID']) ?>" class="action-icon-btn btn-delete" onclick="return confirm('Are you sure you want to delete this employee?')">
                                <i class="fa fa-trash"></i> Delete
                            </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                         <?php if (empty($employees_data)): ?>
                            <tr><td colspan="11" class="text-center">No employees found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" class="modal-content" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEmployeeModalLabel">Add New Employee from Applicant Pool</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label for="EmployeeID_selector" class="form-label">Select Applicant (Employee ID)</label>
                                <select name="EmployeeID" id="EmployeeID_selector" class="form-select" required>
                                    <option value="">-- Select Applicant --</option>
                                    <?php foreach ($potential_employees_data as $applicant): ?>
                                        <option value="<?= htmlspecialchars($applicant['applicantID']) ?>"
                                                data-fullname="<?= htmlspecialchars($applicant['name']) ?>"
                                                data-gender="<?= htmlspecialchars($applicant['gender']) ?>"
                                                data-position="<?= htmlspecialchars($applicant['position_title'] ?? 'N/A') ?>">
                                            <?= htmlspecialchars($applicant['applicantID']) . ' - ' . htmlspecialchars($applicant['name']) . ($applicant['position_title'] ? ' (' . htmlspecialchars($applicant['position_title']) . ')' : '') ?>
                                        </option>
                                    <?php endforeach; ?>
                                     <?php if (empty($potential_employees_data)): ?>
                                        <option value="" disabled>No new applicants found to onboard.</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="add_FullName" class="form-label">Full Name</label>
                                <input type="text" name="FullName" id="add_FullName" class="form-control" required readonly>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="add_Gender" class="form-label">Gender</label>
                                <select name="Gender" id="add_Gender" class="form-select" required disabled>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="add_Position" class="form-label">Position</label>
                                <input type="text" name="Position" id="add_Position" class="form-control" required readonly>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="add_Birthday" class="form-label">Birthday</label>
                                <input type="date" name="Birthday" id="add_Birthday" class="form-control">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="add_ApplicationDate" class="form-label">Application Date (Hire Date)</label>
                            <input type="date" name="ApplicationDate" id="add_ApplicationDate" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="mb-2">
                            <label for="add_DocumentSubmitted" class="form-label">Submit Document (e.g., Contract, ID)</label>
                            <input type="file" name="DocumentSubmitted" id="add_DocumentSubmitted" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
                        </div>
                        <hr>
                        <h6>Training & Qualifications</h6>
                        <div class="mb-2">
                            <label for="add_AcquiredSkillsOrQualifications" class="form-label">Acquired Skills/Qualifications (Summary for HR1)</label>
                            <textarea name="AcquiredSkillsOrQualifications" id="add_AcquiredSkillsOrQualifications" class="form-control" rows="3" placeholder="e.g., Certified in Advanced Cardiac Life Support (ACLS), Proficient in MS Excel"></textarea>
                            <small class="form-text text-muted">Summarize key skills/qualifications. Refer to completed courses from HR2 if applicable for the selected applicant (shown below if available).</small>
                        </div>
                        <div class="mb-2" id="completed_courses_reference_add_modal">
                            <strong>Reference: Completed Courses in HR2 for selected applicant</strong>
                            <ul class="list-group list-group-flush" id="completed_courses_list_add">
                                <li class="list-group-item py-1 text-muted">Select an applicant to see completed courses.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="addEmployee" class="btn btn-primary">Add Employee to Profile</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <?php foreach ($employees_data as $row): ?>
        <div class="modal fade" id="viewEmployeeModal<?= htmlspecialchars($row['EmployeeID']) ?>" tabindex="-1" aria-labelledby="viewEmployeeModalLabel<?= htmlspecialchars($row['EmployeeID']) ?>" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewEmployeeModalLabel<?= htmlspecialchars($row['EmployeeID']) ?>">View Employee Details: <?= htmlspecialchars($row['FullName']) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>HR1 Profile Information</h6>
                                <p><strong>Employee ID:</strong> <?= htmlspecialchars($row['EmployeeID']) ?></p>
                                <p><strong>Full Name:</strong> <?= htmlspecialchars($row['FullName']) ?></p>
                                <p><strong>Gender:</strong> <?= htmlspecialchars($row['Gender']) ?></p>
                                <p><strong>Position:</strong> <?= htmlspecialchars($row['Position']) ?></p>
                                <p><strong>Birthday:</strong> <?= htmlspecialchars($row['Birthday'] ? date("F j, Y", strtotime($row['Birthday'])) : 'N/A') ?></p>
                                <p><strong>Application Date:</strong> <?= htmlspecialchars($row['ApplicationDate'] ? date("F j, Y", strtotime($row['ApplicationDate'])) : 'N/A') ?></p>
                                <p><strong>Document Submitted:</strong>
                                    <?php if (!empty($row['DocumentSubmitted'])): ?>
                                        <a href="<?= htmlspecialchars($row['DocumentSubmitted']) ?>" target="_blank">View Document</a>
                                    <?php else: ?>
                                        No Document
                                    <?php endif; ?>
                                </p>
                                <hr>
                                <h6>Acquired Skills/Qualifications (HR1 Record)</h6>
                                <p><?= nl2br(htmlspecialchars($row['AcquiredSkillsOrQualifications'] ?? 'N/A')) ?></p>
                            </div>
                            <div class="col-md-6">
                                <h6>HR2 Learning Progress</h6>
                                <div class="mb-2" style="max-height: 200px; overflow-y: auto; border: 1px solid #eee; padding:10px;">
                                    <?php
                                    $currentEmployeeMapID_lp_view = (string)$row['EmployeeID'];
                                    if (isset($learning_progress_map[$currentEmployeeMapID_lp_view]) && !empty($learning_progress_map[$currentEmployeeMapID_lp_view])) {
                                        echo '<ul class="list-unstyled mb-0">';
                                        foreach ($learning_progress_map[$currentEmployeeMapID_lp_view] as $progress_item) {
                                            echo '<li>' . htmlspecialchars($progress_item['PROGRAM_NAME']) . ': ' .
                                                 htmlspecialchars($progress_item['PROGRESS']) . '% (' .
                                                 htmlspecialchars($progress_item['STATUS']) . ')</li>';
                                        }
                                        echo '</ul>';
                                    } else {
                                        echo '<p class="text-muted mb-0">No learning progress found in HR2.</p>';
                                    }
                                    ?>
                                </div>
                                <hr>
                                <h6>HR3 Employee Schedules</h6>
                                <div class="mb-2" style="max-height: 200px; overflow-y: auto; border: 1px solid #eee; padding:10px;">
                                    <?php
                                    $currentEmployeeIdStr_view_schedule = (string)$row['EmployeeID'];
                                    if (isset($employee_schedules_map[$currentEmployeeIdStr_view_schedule]) && !empty($employee_schedules_map[$currentEmployeeIdStr_view_schedule])) {
                                        echo '<ul class="list-unstyled mb-0">';
                                        foreach ($employee_schedules_map[$currentEmployeeIdStr_view_schedule] as $schedule_item) {
                                            echo '<li>' .
                                                 htmlspecialchars(date("M d, Y", strtotime($schedule_item['schedule_date']))) . ": " .
                                                 htmlspecialchars($schedule_item['shift_name'] ?? 'N/A') .
                                                 (isset($schedule_item['start_time']) ? " (" . htmlspecialchars(date("g:i A", strtotime($schedule_item['start_time']))) . " - " . htmlspecialchars(date("g:i A", strtotime($schedule_item['end_time']))) . ")" : "") .
                                                 (isset($schedule_item['schedule_department_name']) && $schedule_item['schedule_department_name'] ? " @ " . htmlspecialchars($schedule_item['schedule_department_name']) : "") .
                                                 '</li>';
                                        }
                                        echo '</ul>';
                                    } else {
                                        echo '<p class="text-muted mb-0">No schedules found in HR3.</p>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editEmployeeModal<?= htmlspecialchars($row['EmployeeID']) ?>" tabindex="-1" aria-labelledby="editEmployeeModalLabel<?= htmlspecialchars($row['EmployeeID']) ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" class="modal-content" enctype="multipart/form-data">
                    <input type="hidden" name="EmployeeID" value="<?= htmlspecialchars($row['EmployeeID']) ?>">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editEmployeeModalLabel<?= htmlspecialchars($row['EmployeeID']) ?>">Edit Employee: <?= htmlspecialchars($row['FullName']) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                           <div class="col-md-6 mb-2">
                                <label class="form-label">Employee ID</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row['EmployeeID']) ?>" readonly disabled>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="editFullName<?= htmlspecialchars($row['EmployeeID']) ?>" class="form-label">Full Name</label>
                                <input type="text" name="FullName" id="editFullName<?= htmlspecialchars($row['EmployeeID']) ?>" class="form-control" value="<?= htmlspecialchars($row['FullName']) ?>" required>
                            </div>
                        </div>
                        <div class="row">
                             <div class="col-md-6 mb-2">
                                <label for="editGender<?= htmlspecialchars($row['EmployeeID']) ?>" class="form-label">Gender</label>
                                <select name="Gender" id="editGender<?= htmlspecialchars($row['EmployeeID']) ?>" class="form-select" required>
                                    <option value="Male" <?= ($row['Gender'] == 'Male') ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= ($row['Gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
                                    <option value="Other" <?= ($row['Gender'] == 'Other') ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="editPosition<?= htmlspecialchars($row['EmployeeID']) ?>" class="form-label">Position</label>
                                <input type="text" name="Position" id="editPosition<?= htmlspecialchars($row['EmployeeID']) ?>" class="form-control" value="<?= htmlspecialchars($row['Position']) ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="editBirthday<?= htmlspecialchars($row['EmployeeID']) ?>" class="form-label">Birthday</label>
                                <input type="date" name="Birthday" id="editBirthday<?= htmlspecialchars($row['EmployeeID']) ?>" class="form-control" value="<?= htmlspecialchars($row['Birthday']) ?>">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="editApplicationDate<?= htmlspecialchars($row['EmployeeID']) ?>" class="form-label">Application Date</label>
                                <input type="date" name="ApplicationDate" id="editApplicationDate<?= htmlspecialchars($row['EmployeeID']) ?>" class="form-control" value="<?= htmlspecialchars($row['ApplicationDate']) ?>">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="editDocumentSubmitted<?= htmlspecialchars($row['EmployeeID']) ?>" class="form-label">Document (re-upload or clear)</label>
                            <?php if (!empty($row['DocumentSubmitted'])): ?>
                            <p>Current Document: <a href="<?= htmlspecialchars($row['DocumentSubmitted']) ?>" target="_blank">View</a>
                                <a href="employee_profile_setup.php?delete_document_id=<?= htmlspecialchars($row['EmployeeID']) ?>" class="action-icon-btn btn-delete ms-2" onclick="return confirm('Are you sure you want to delete this document?');"><i class="fa fa-trash"></i> Delete Document</a>
                            </p>
                            <?php else: ?>
                            <p>No document currently uploaded.</p>
                            <?php endif; ?>
                            <input type="file" name="editDocumentSubmitted" id="editDocumentSubmitted<?= htmlspecialchars($row['EmployeeID']) ?>" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
                        </div>
                        <hr>
                        <h6>Training & Qualifications</h6>
                        <div class="mb-2">
                            <label for="edit_AcquiredSkillsOrQualifications<?= htmlspecialchars($row['EmployeeID']) ?>" class="form-label">Acquired Skills/Qualifications (Summary for HR1)</label>
                            <textarea name="AcquiredSkillsOrQualifications" id="edit_AcquiredSkillsOrQualifications<?= htmlspecialchars($row['EmployeeID']) ?>" class="form-control" rows="4"><?= htmlspecialchars($row['AcquiredSkillsOrQualifications'] ?? '') ?></textarea>
                            <small class="form-text text-muted">Update this summary based on the employee's completed training and other qualifications.</small>
                        </div>
                        <div class="mb-2">
                            <strong>Reference: Completed Courses in HR2</strong>
                            <?php
                                $currentEmployeeMapID_edit_cl = (string)$row['EmployeeID'];
                                if (isset($completed_learning_map[$currentEmployeeMapID_edit_cl]) && !empty($completed_learning_map[$currentEmployeeMapID_edit_cl])) {
                                    echo '<ul class="list-group list-group-flush">';
                                    foreach ($completed_learning_map[$currentEmployeeMapID_edit_cl] as $completed_course_name) {
                                        echo '<li class="list-group-item py-1">' . htmlspecialchars($completed_course_name) . '</li>';
                                    }
                                    echo '</ul>';
                                } else {
                                    echo '<p class="text-muted">No "Complete" courses found in HR2 for this employee.</p>';
                                }
                            ?>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="editEmployee" class="btn btn-success">Update Employee Profile</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const completedLearningMapFromPHP = <?= json_encode($completed_learning_map) ?>;
</script>
<script src="admin.js"></script> 
</body>
</html>