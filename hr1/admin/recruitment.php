<?php
include '../connections.php';

// Check HR4 connection
$hr4_conn_error = false; $hr4_error_message = "";
if (!isset($connections_hr4) || !$connections_hr4) {
    $hr4_conn_error = true; $hr4_error_message = "HR4 DB connection object not available. Check connections.php.";
    error_log("recruitment.php: HR4 connection object (\$connections_hr4) from include is not valid or not set.");
}

// Check HR2 connection
$hr2_conn_error = false; $hr2_error_message = "";
if (!isset($connections_hr2) || !$connections_hr2) {
    $hr2_conn_error = true; $hr2_error_message = "HR2 DB connection object not available. Skill features may be limited.";
}

// Set the default active tab to 'job'
$alert = "";
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'job'; // Default to Job Postings (HR1) tab

// Handle Add Job for HR1 (either manually or from HR4 request)
if (isset($_POST['addHr1Job'])) {
    $stmt = $connections->prepare("INSERT INTO jobposting (title, description, jobtype, department, postingdate, status) VALUES (?, ?, ?, ?, ?, ?)");
    $title = $_POST['title'] ?? 'N/A'; $description = $_POST['description'] ?? 'No description';
    $jobtype = $_POST['jobtype'] ?? 'N/A';
    $department = $_POST['department'] ?? 'N/A'; $postingdate = $_POST['postingdate'] ?? date('Y-m-d');
    $status = $_POST['status'] ?? 'Open';
    $stmt->bind_param("ssssss", $title, $description, $jobtype, $department, $postingdate, $status);
    if ($stmt->execute()) {
        if (isset($_POST['hr4_request_id_source']) && !empty($_POST['hr4_request_id_source']) && !$hr4_conn_error && $connections_hr4) {
            $hr4_req_id = (int)$_POST['hr4_request_id_source'];
            $update_hr4_status_sql = "UPDATE recruitment_requests SET status = 'Posted in HR1' WHERE request_id = ?";
            $hr4_stmt = mysqli_prepare($connections_hr4, $update_hr4_status_sql);
            if ($hr4_stmt) { mysqli_stmt_bind_param($hr4_stmt, "i", $hr4_req_id); mysqli_stmt_execute($hr4_stmt); mysqli_stmt_close($hr4_stmt); }
            else { error_log("Failed to prepare HR4 status update: " . mysqli_error($connections_hr4));}
        }
        header("Location: recruitment.php?hr1_job_added=1&tab=job"); exit();
    } else { $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error adding HR1 job: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';}
    $stmt->close();
}

// Handle Edit Job Posting for HR1
if (isset($_POST['editJob'])) {
    $stmt = $connections->prepare("UPDATE jobposting SET title=?, description=?, jobtype=?, department=?, postingdate=?, status=? WHERE jobpostingID=?");
    $stmt->bind_param("ssssssi", $_POST['title'], $_POST['description'], $_POST['jobtype'], $_POST['department'], $_POST['postingdate'], $_POST['status'], $_POST['jobpostingID']);
    $stmt->execute();
    header("Location: recruitment.php?edited=1&tab=job");
    exit();
}

// Handle Add Applicant
if (isset($_POST['addApplicant'])) {
    $jobpostingID = !empty($_POST['jobpostingID']) ? $_POST['jobpostingID'] : NULL;
    $stmt = $connections->prepare("INSERT INTO applicant (jobpostingID, name, email, contactnumber, applied_at, age, sex, application_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "isssssss",
        $jobpostingID,
        $_POST['name'],
        $_POST['email'],
        $_POST['contactnumber'],
        $_POST['applied_at'],
        $_POST['age'],
        $_POST['sex'],
        $_POST['application_status']
    );
    if ($stmt->execute()) {
        header("Location: recruitment.php?added=1&tab=applicant_registration");
        exit();
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error adding applicant: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
    $stmt->close();
}

// Handle Edit Applicant
if (isset($_POST['editApplicant'])) {
    $stmt = $connections->prepare("UPDATE applicant SET name=?, email=?, contactnumber=?, applied_at=?, age=?, sex=?, application_status=? WHERE applicantID=?");
    $stmt->bind_param(
        "sssssssi",
        $_POST['name'],
        $_POST['email'],
        $_POST['contactnumber'],
        $_POST['applied_at'],
        $_POST['age'],
        $_POST['sex'],
        $_POST['application_status'],
        $_POST['applicantID']
    );
    $stmt->execute();
    header("Location: recruitment.php?applicant_edited=1&tab=applicant");
    exit();
}

// Handle Delete Applicant
if (isset($_GET['delete_applicant'])) {
    $id = intval($_GET['delete_applicant']);
    $connections->query("DELETE FROM compliancedocument WHERE applicantID = $id");
    $connections->query("DELETE FROM applicant WHERE applicantID = $id");
    header("Location: recruitment.php?deleted=1&tab=applicant");
    exit();
}

// Handle Delete Job Posting for HR1
if (isset($_GET['delete_job'])) {
    $id = intval($_GET['delete_job']);
    $stmt = $connections->prepare("DELETE FROM jobposting WHERE jobpostingID = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: recruitment.php?deleted_job=1&tab=job");
        exit();
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error deleting job posting: ' . $stmt->error . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
    $stmt->close();
}

// Fetch existing data for HR1 Job Postings
$hr1_jobs_sql = "SELECT jobpostingID, title, description, jobtype, department, postingdate, status FROM jobposting ORDER BY postingdate DESC";
$hr1_jobs = $connections->query($hr1_jobs_sql);

// Fetch open job postings for the registration form
$job_postings_for_form_sql = "SELECT jobpostingID, title FROM jobposting WHERE status = 'Open' ORDER BY title ASC";
$job_postings_for_form = $connections->query($job_postings_for_form_sql);


// Fetch other HR1 data (offers, compliance, applicants)
$hr1_offers_sql = "SELECT o.offerID, o.salary, o.status AS offer_status, o.position, o.created_at, a.name, a.email FROM offerapproval o LEFT JOIN applicant a ON o.applicantID = a.applicantID ORDER BY o.created_at DESC";
$hr1_offers = $connections->query($hr1_offers_sql);

$hr1_complianceDocs_sql = "SELECT cd.complianceID, cd.file_path, cd.document_name, cd.submissionDate, cd.document, cd.status AS doc_status, a.name AS applicant_name_doc FROM compliancedocument cd LEFT JOIN applicant a ON cd.applicantID = a.applicantID ORDER BY cd.submissionDate DESC";
$hr1_complianceDocs = $connections->query($hr1_complianceDocs_sql);

$hr1_applicants_sql = "SELECT a.applicantID, a.name AS applicant_name, a.email AS applicant_email, a.contactnumber, jp.title AS position_applied, a.application_status, oa.status AS applicant_offer_status, a.applied_at, a.age, a.sex FROM applicant a LEFT JOIN offerapproval oa ON a.applicantID = oa.applicantID LEFT JOIN jobposting jp ON a.jobpostingID = jp.jobpostingID ORDER BY a.applied_at DESC";
$hr1_applicants = $connections->query($hr1_applicants_sql);


if (isset($_POST['editOffer'])) {
    $stmt = $connections->prepare("UPDATE offerapproval SET position=?, salary=?, status=? WHERE offerID=?");
    $stmt->bind_param( "sssi", $_POST['position'], $_POST['salary'], $_POST['status'], $_POST['offerID']);
    $stmt->execute();
    header("Location: recruitment.php?offer_edited=1&tab=offer"); exit();
}


// Alert messages consolidation
if (isset($_GET['hr1_job_added'])) { $alert .= '<div id="alertBox" class="alert alert-success alert-dismissible fade show" role="alert">HR1 Job Posting added successfully!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';}
elseif (isset($_GET['edited'])) { $alert .= '<div id="alertBox" class="alert alert-info alert-dismissible fade show" role="alert">HR1 Job Posting updated successfully!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';}
elseif (isset($_GET['offer_edited'])) { $alert .= '<div id="alertBox" class="alert alert-info alert-dismissible fade show" role="alert">HR1 Offer updated successfully!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';}
elseif (isset($_GET['applicant_edited'])) { $alert .= '<div id="alertBox" class="alert alert-info alert-dismissible fade show" role="alert">HR1 Applicant updated successfully!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';}
elseif (isset($_GET['added'])) { $alert .= '<div id="alertBox" class="alert alert-success alert-dismissible fade show" role="alert">Applicant added successfully!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';}
elseif (isset($_GET['deleted'])) { $alert .= '<div id="alertBox" class="alert alert-danger alert-dismissible fade show" role="alert">Applicant deleted successfully!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';}
elseif (isset($_GET['deleted_job'])) { $alert .= '<div id="alertBox" class="alert alert-danger alert-dismissible fade show" role="alert">HR1 Job Posting deleted successfully!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';}


// Fetch HR4 requests that are 'Pending' or 'Approved' (to be processed into HR1 jobs)
$hr4_requests_to_process_result = null;
$display_hr4_message = "";
if ($hr4_conn_error || !$connections_hr4) {
    $display_hr4_message = $hr4_error_message ?: "Could not connect to HR4 database to fetch recruitment requests.";
} else {
    $hr4_requests_sql = "SELECT rr.request_id, rr.status AS hr4_status, rr.request_date,
                                p.position_name, d.department_name, rr.number_of_vacancies,
                                e.first_name AS requester_first_name, e.last_name AS requester_last_name
                         FROM recruitment_requests rr
                         JOIN positions p ON rr.position_id = p.position_id
                         LEFT JOIN departments d ON p.department_id = d.department_id
                         LEFT JOIN employees e ON rr.requester_id = e.employee_id
                         WHERE rr.status = 'Pending' OR rr.status = 'Approved'
                         ORDER BY rr.request_date DESC";
    $hr4_requests_to_process_result = mysqli_query($connections_hr4, $hr4_requests_sql);
    if (!$hr4_requests_to_process_result) {
        error_log("HR4 Recruitment Requests Query Failed: " . mysqli_error($connections_hr4));
        $display_hr4_message = "Error fetching HR4 recruitment requests: " . mysqli_error($connections_hr4);
    } elseif (mysqli_num_rows($hr4_requests_to_process_result) == 0) {
        $display_hr4_message = "No pending or approved employee requests found in HR4 to process.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin - HM </title>
  <link rel="shortcut icon" href="../logo.png" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
  <link rel="stylesheet" href="../tm.css"/> </head>
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
            <div class="white-bg hr4-requests-section card-panel">
                <h4 class="text-center section-title">HR4 Recruitment Requests</h4>
                <?php if (!empty($display_hr4_message)): ?>
                    <div class="alert <?= (strpos(strtolower($display_hr4_message), "error") !== false || strpos(strtolower($display_hr4_message), "unavailable") !== false || strpos(strtolower($display_hr4_message), "failed") !== false) ? 'alert-danger' : 'alert-info' ?>">
                        <?= htmlspecialchars($display_hr4_message) ?>
                    </div>
                <?php elseif ($hr4_requests_to_process_result && mysqli_num_rows($hr4_requests_to_process_result) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover align-middle">
                            <thead class="table-info"><tr><th>HR4 Req. ID</th><th>Requester (HR4)</th><th>Position</th><th>HR4 Department</th><th>Vacancies</th><th>HR4 Req. Date</th><th>HR4 Status</th><th>Action</th></tr></thead>
                            <tbody>
                            <?php while ($req = mysqli_fetch_assoc($hr4_requests_to_process_result)): ?>
                            <tr>
                                <td><?= htmlspecialchars($req['request_id']) ?></td>
                                <td><?= htmlspecialchars(($req['requester_first_name'] ?? '') . ' ' . ($req['requester_last_name'] ?? 'N/A')) ?></td>
                                <td><?= htmlspecialchars($req['position_name']) ?></td>
                                <td><?= htmlspecialchars($req['department_name'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($req['number_of_vacancies']) ?></td>
                                <td><?= htmlspecialchars(date('Y-m-d', strtotime($req['request_date']))) ?></td>
                                <td><span class="badge bg-primary"><?= htmlspecialchars($req['hr4_status']) ?></span></td>
                                <td>
                                    <button type="button" class="btn btn-success btn-sm create-hr1-job-from-request"
                                            data-bs-toggle="modal" data-bs-target="#addHr1JobModal"
                                            data-position_name="<?= htmlspecialchars($req['position_name']) ?>"
                                            data-department_name="<?= htmlspecialchars($req['department_name'] ?? '') ?>"
                                            data-number_of_vacancies="<?= htmlspecialchars($req['number_of_vacancies']) ?>"
                                            data-hr4_request_id="<?= htmlspecialchars($req['request_id']) ?>">
                                        <i class="fa fa-plus"></i> Create HR1 Job
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                <?php if ($hr4_requests_to_process_result) mysqli_free_result($hr4_requests_to_process_result); ?>
            </div>

            <div class="white-bg mt-4 card-panel">
                <h4 class="text-center section-title">HR1 Recruitment Management</h4>
                <?php if (!empty($alert)): ?>
                    <div id="alertBox" class="alert alert-dismissible fade show" role="alert">
                        <?= $alert ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <ul class="nav nav-tabs mb-3" id="recruitmentTabs" role="tablist">
                    <li class="nav-item" role="presentation"><button class="nav-link <?= $active_tab == 'job' ? 'active' : '' ?>" id="job-tab" data-bs-toggle="tab" data-bs-target="#job" type="button" role="tab" aria-controls="job" aria-selected="<?= $active_tab == 'job' ? 'true' : 'false' ?>">Job Postings (HR1)</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link <?= $active_tab == 'offer' ? 'active' : '' ?>" id="offer-tab" data-bs-toggle="tab" data-bs-target="#offer" type="button" role="tab" aria-controls="offer" aria-selected="<?= $active_tab == 'offer' ? 'true' : 'false' ?>">Offers & Approvals (HR1)</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link <?= $active_tab == 'compliance' ? 'active' : '' ?>" id="compliance-tab" data-bs-toggle="tab" data-bs-target="#compliance" type="button" role="tab" aria-controls="compliance" aria-selected="<?= $active_tab == 'compliance' ? 'true' : 'false' ?>">Compliance Docs (HR1)</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link <?= $active_tab == 'applicant' ? 'active' : '' ?>" id="applicant-tab" data-bs-toggle="tab" data-bs-target="#applicant" type="button" role="tab" aria-controls="applicant" aria-selected="<?= $active_tab == 'applicant' ? 'true' : 'false' ?>">Applicants (HR1)</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link <?= $active_tab == 'applicant_registration' ? 'active' : '' ?>" id="applicant-registration-tab" data-bs-toggle="tab" data-bs-target="#applicant_registration" type="button" role="tab" aria-controls="applicant_registration" aria-selected="<?= $active_tab == 'applicant_registration' ? 'true' : 'false' ?>">Register New Applicant</button></li>
                </ul>
                <div class="tab-content" id="recruitmentTabsContent">
                    <div class="tab-pane fade <?= $active_tab == 'job' ? 'show active' : '' ?>" id="job" role="tabpanel" aria-labelledby="job-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3"><h5>Current HR1 Job Postings</h5><button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addHr1JobModal"><i class="fa fa-plus"></i> Add New HR1 Job Manually</button></div>
                        <div class="table-responsive"><table class="table table-bordered table-hover align-middle"><thead class="table-dark"><tr><th>Title</th><th>Description</th><th>Job Type</th><th>Department</th><th>Posting Date</th><th>Status</th><th>Action</th></tr></thead><tbody>
                        <?php if ($hr1_jobs && $hr1_jobs->num_rows > 0): $hr1_jobs->data_seek(0); while ($row = $hr1_jobs->fetch_assoc()): ?>
                        <tr><td><?= htmlspecialchars($row['title']) ?></td><td><?= substr(htmlspecialchars($row['description']), 0, 50) . (strlen($row['description']) > 50 ? '...' : '') ?></td><td><?= htmlspecialchars($row['jobtype']) ?></td><td><?= htmlspecialchars($row['department']) ?></td><td><?= htmlspecialchars($row['postingdate']) ?></td><td><?= htmlspecialchars($row['status']) ?></td><td>
                            <div class="d-flex gap-2">
                                <button class="action-icon-btn btn-edit" data-bs-toggle="modal" data-bs-target="#editJobModal<?= $row['jobpostingID'] ?>"><i class="fa fa-edit"></i> Edit</button>
                                <a href="recruitment.php?delete_job=<?= $row['jobpostingID'] ?>&tab=job" class="action-icon-btn btn-delete" onclick="return confirm('Are you sure you want to delete this job posting?')">
                                    <i class="fa fa-trash"></i> Delete
                                </a>
                            </div>
                        </td></tr>
                        <?php endwhile; else: echo '<tr><td colspan="8" class="text-center">No job postings found in HR1.</td></tr>'; endif; ?>
                        </tbody></table></div>
                    </div>
                    <div class="tab-pane fade <?= $active_tab == 'offer' ? 'show active' : '' ?>" id="offer" role="tabpanel" aria-labelledby="offer-tab"> <h5>HR1 Offers & Approvals</h5>
                        <div class="table-responsive"><table class="table table-bordered table-hover align-middle"><thead class="table-dark"><tr><th>Applicant Name</th><th>Email</th><th>Position</th><th>Salary</th><th>Status</th><th>Date Offered</th><th>Action</th></tr></thead><tbody>
                        <?php if($hr1_offers && $hr1_offers->num_rows > 0): $hr1_offers->data_seek(0); while($row = $hr1_offers->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['position']) ?></td>
                            <td><?= htmlspecialchars($row['salary']) ?></td>
                            <td><?= htmlspecialchars($row['offer_status'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                            <td><button class="action-icon-btn btn-edit" data-bs-toggle="modal" data-bs-target="#editOfferModal<?= $row['offerID'] ?>"><i class="fa fa-edit"></i> Edit</button></td>
                        </tr>
                        <?php endwhile; else: ?><tr><td colspan="7" class="text-center">No offers recorded in HR1.</td></tr><?php endif; ?>
                        </tbody></table></div>
                    </div>
                    <div class="tab-pane fade <?= $active_tab == 'compliance' ? 'show active' : '' ?>" id="compliance" role="tabpanel" aria-labelledby="compliance-tab"> <h5>HR1 Compliance Documents</h5>
                        <div class="table-responsive"><table class="table table-bordered table-hover align-middle"><thead class="table-dark"><tr><th>Applicant Name</th><th>Document Name</th><th>File Path/Link</th><th>Submission Date</th><th>Status</th></tr></thead><tbody>
                        <?php if($hr1_complianceDocs && $hr1_complianceDocs->num_rows > 0): $hr1_complianceDocs->data_seek(0); while($doc = $hr1_complianceDocs->fetch_assoc()): ?>
                        <tr><td><?= htmlspecialchars($doc['applicant_name_doc']) ?></td><td><?= htmlspecialchars($doc['document_name']) ?></td><td><?php if ($doc['document']):?><a href="<?= htmlspecialchars($doc['document']) ?>" target="_blank">View Document</a><?php else: echo htmlspecialchars($doc['file_path'] ?: "N/A"); endif; ?></td><td><?= htmlspecialchars($doc['submissionDate']) ?></td><td><?= htmlspecialchars($doc['doc_status']) ?></td></tr>
                        <?php endwhile; else: ?><tr><td colspan="5" class="text-center">No compliance documents in HR1.</td></tr><?php endif; ?>
                        </tbody></table></div>
                    </div>
                     <div class="tab-pane fade <?= $active_tab == 'applicant' ? 'show active' : '' ?>" id="applicant" role="tabpanel" aria-labelledby="applicant-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>HR1 Applicants List</h5>
                            </div>
                        <div class="table-responsive"><table class="table table-bordered table-hover align-middle"><thead class="table-dark"><tr><th>Applicant Name</th><th>Email</th><th>Contact Number</th><th>Position Applied</th><th>Application Status</th><th>Offer Status</th><th>Date Applied</th><th>Action</th></tr></thead><tbody>
                        <?php if($hr1_applicants && $hr1_applicants->num_rows > 0): $hr1_applicants->data_seek(0); while($row = $hr1_applicants->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['applicant_name']) ?></td>
                            <td><?= htmlspecialchars($row['applicant_email']) ?></td>
                            <td><?= htmlspecialchars($row['contactnumber']) ?></td>
                            <td><?= htmlspecialchars($row['position_applied'] ?? 'N/A') ?></td>
                            <td>
                                <?php $app_status = $row['application_status'] ?? 'Pending'; $app_status_class = 'bg-secondary';
                                if ($app_status == 'Screened') $app_status_class = 'bg-info text-dark'; elseif ($app_status == 'Interviewing') $app_status_class = 'bg-primary';
                                elseif ($app_status == 'Offered') $app_status_class = 'bg-warning text-dark'; elseif ($app_status == 'Hired') $app_status_class = 'bg-success';
                                elseif ($app_status == 'Rejected') $app_status_class = 'bg-danger'; echo '<span class="badge ' . $app_status_class . '">' . strtoupper(htmlspecialchars($app_status)) . '</span>'; ?>
                            </td>
                            <td>
                                <?php $offer_stat = $row['applicant_offer_status'] ?? null;
                                if ($offer_stat == 'Approved') { echo '<span class="badge bg-success">OFFER APPROVED</span>'; } elseif ($offer_stat == 'Pending') { echo '<span class="badge bg-warning text-dark">OFFER PENDING</span>'; }
                                elseif ($offer_stat == 'Declined') { echo '<span class="badge bg-danger">OFFER DECLINED</span>'; } else { echo '<span class="badge bg-light text-dark">NO OFFER</span>'; } ?>
                            </td>
                            <td><?= htmlspecialchars($row['applied_at']) ?></td> <td>
                                <div class="d-flex gap-2">
                                    <button class="action-icon-btn btn-edit" data-bs-toggle="modal" data-bs-target="#editApplicantModal<?= $row['applicantID'] ?>"><i class="fa fa-edit"></i> Edit</button>
                                    <a href="recruitment.php?delete_applicant=<?= $row['applicantID'] ?>" class="action-icon-btn btn-delete" onclick="return confirm('Delete this applicant?')">
                                        <i class="fa fa-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; else: ?><tr><td colspan="8" class="text-center">No applicants found in HR1.</td></tr><?php endif; ?>
                        </tbody></table></div>
                    </div>

                    <div class="tab-pane fade <?= $active_tab == 'applicant_registration' ? 'show active' : '' ?>" id="applicant_registration" role="tabpanel" aria-labelledby="applicant-registration-tab">
                        <h5>Register New Applicant</h5>
                        <form method="POST" action="recruitment.php" class="p-3 border rounded shadow-sm">
                            <input type="hidden" name="tab" value="applicant_registration"> <div class="mb-3">
                                <label for="jobpostingID" class="form-label">Position Applied For</label>
                                <select name="jobpostingID" id="jobpostingID" class="form-control">
                                    <option value="" disabled selected>-- Select Job Posting --</option>
                                    <?php
                                    if ($job_postings_for_form && $job_postings_for_form->num_rows > 0) {
                                        while ($job_row = $job_postings_for_form->fetch_assoc()) {
                                            echo '<option value="' . htmlspecialchars($job_row['jobpostingID']) . '">' . htmlspecialchars($job_row['title']) . '</option>';
                                        }
                                        $job_postings_for_form->data_seek(0); // Reset pointer for potential reuse
                                    } else {
                                        echo '<option value="" disabled>No open job postings available</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="applicant_name" class="form-label">Full Name</label>
                                <input type="text" name="name" id="applicant_name" class="form-control" placeholder="Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="applicant_email" class="form-label">Email</label>
                                <input type="email" name="email" id="applicant_email" class="form-control" placeholder="Email" required>
                            </div>
                            <div class="mb-3">
                                <label for="applicant_contactnumber" class="form-label">Contact Number</label>
                                <input type="text" name="contactnumber" id="applicant_contactnumber" class="form-control" placeholder="Contact Number" required>
                            </div>
                            <div class="mb-3">
                                <label for="applicant_applied_at" class="form-label">Date Applied</label>
                                <input type="date" name="applied_at" id="applicant_applied_at" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="applicant_age" class="form-label">Age</label>
                                <input type="number" name="age" id="applicant_age" class="form-control" placeholder="Age" required>
                            </div>
                            <div class="mb-3">
                                <label for="applicant_sex" class="form-label">Sex</label>
                                <select name="sex" id="applicant_sex" class="form-control" required>
                                    <option value="" disabled selected>-- Select Sex --</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="applicant_application_status" class="form-label">Application Status</label>
                                <select name="application_status" id="applicant_application_status" class="form-control" required>
                                    <option value="pending">Pending</option>
                                    <option value="reviewed">Reviewed</option>
                                    <option value="accepted">Accepted</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" name="addApplicant" class="btn btn-primary">Submit Registration</button>
                            </div>
                        </form>
                    </div>
                </div>

                <?php if ($hr1_jobs && $hr1_jobs->num_rows > 0): $hr1_jobs->data_seek(0); while ($row = $hr1_jobs->fetch_assoc()): ?>
                <div class="modal fade" id="editJobModal<?= $row['jobpostingID'] ?>" tabindex="-1"><div class="modal-dialog modal-lg"><form method="POST" action="recruitment.php" class="modal-content">
                    <input type="hidden" name="jobpostingID" value="<?= $row['jobpostingID'] ?>"><div class="modal-header"><h5 class="modal-title">Edit HR1 Job Posting</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">
                    <div class="mb-2"><label class="form-label">Title</label><input type="text" name="title" class="form-control" value="<?= htmlspecialchars($row['title']) ?>" required></div>
                    <div class="mb-2"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3" required><?= htmlspecialchars($row['description']) ?></textarea></div>
                    <div class="mb-2"><label class="form-label">Job Type</label><input type="text" name="jobtype" class="form-control" value="<?= htmlspecialchars($row['jobtype']) ?>" required></div><div class="mb-2"><label class="form-label">Department</label><input type="text" name="department" class="form-control" value="<?= htmlspecialchars($row['department']) ?>" required></div><div class="mb-2"><label class="form-label">Posting Date</label><input type="date" name="postingdate" class="form-control" value="<?= htmlspecialchars($row['postingdate']) ?>" required></div><div class="mb-2"><label class="form-label">Status</label><select name="status" class="form-select" required><option value="Open" <?= $row['status'] == 'Open' ? 'selected' : '' ?>>Open</option><option value="Closed" <?= $row['status'] == 'Closed' ? 'selected' : '' ?>>Closed</option><option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option></select></div></div>
                    <div class="modal-footer"><button type="submit" name="editJob" class="btn btn-success">Update</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button></div>
                </form></div></div>
                <?php endwhile; endif; ?>

                <?php if ($hr1_offers && $hr1_offers->num_rows > 0) : $hr1_offers->data_seek(0); while ($row = $hr1_offers->fetch_assoc()) : ?>
                <div class="modal fade" id="editOfferModal<?= $row['offerID'] ?>" tabindex="-1"><div class="modal-dialog"><form method="POST" action="recruitment.php" class="modal-content">
                <input type="hidden" name="offerID" value="<?= $row['offerID'] ?>"><div class="modal-header"><h5 class="modal-title">Edit HR1 Offer</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">
                <div class="mb-2"><label class="form-label">Position</label><input type="text" name="position" class="form-control" value="<?= htmlspecialchars($row['position']) ?>" required></div>
                <div class="mb-2"><label class="form-label">Salary</label><input type="number" step="0.01" name="salary" class="form-control" value="<?= htmlspecialchars($row['salary']) ?>" required></div>
                <div class="mb-2"><label class="form-label">Status</label><select name="status" class="form-select" required><option value="Pending" <?= ($row['offer_status'] ?? '') == 'Pending' ? 'selected' : ''; ?>>Pending</option><option value="Approved" <?= ($row['offer_status'] ?? '') == 'Approved' ? 'selected' : ''; ?>>Approved</option><option value="Declined" <?= ($row['offer_status'] ?? '') == 'Declined' ? 'selected' : ''; ?>>Declined</option></select></div>
                </div><div class="modal-footer"><button type="submit" name="editOffer" class="btn btn-success">Update</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button></div></form></div></div>
                <?php endwhile; endif; ?>

                <?php if ($hr1_applicants && $hr1_applicants->num_rows > 0) : $hr1_applicants->data_seek(0); while ($row = $hr1_applicants->fetch_assoc()) : ?>
                <div class="modal fade" id="editApplicantModal<?= htmlspecialchars($row['applicantID']) ?>" tabindex="-1"><div class="modal-dialog"><form method="POST" action="recruitment.php" class="modal-content">
                <input type="hidden" name="applicantID" value="<?= htmlspecialchars($row['applicantID']) ?>"><div class="modal-header"><h5 class="modal-title">Edit HR1 Applicant</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">
                <div class="mb-2"><label class="form-label">Name</label><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['applicant_name']) ?>" required></div>
                <div class="mb-2"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row['applicant_email']) ?>" required></div>
                <div class="mb-2"><label class="form-label">Contact Number</label><input type="text" name="contactnumber" class="form-control" value="<?= htmlspecialchars($row['contactnumber']) ?>" required></div>
                <div class="mb-2"><label class="form-label">Date Applied</label><input type="datetime-local" name="applied_at" class="form-control" value="<?= htmlspecialchars(date('Y-m-d\TH:i', strtotime($row['applied_at']))) ?>" required></div>
                <div class="mb-2"><label class="form-label">Age</label><input type="number" name="age" class="form-control" value="<?= htmlspecialchars($row['age'] ?? '') ?>"></div>
                <div class="mb-2"><label class="form-label">Sex</label><select name="sex" class="form-select"><option value="">Select</option><option value="Male" <?= ($row['sex'] ?? '') == 'Male' ? 'selected' : '' ?>>Male</option><option value="Female" <?= ($row['sex'] ?? '') == 'Female' ? 'selected' : '' ?>>Female</option><option value="Other" <?= ($row['sex'] ?? '') == 'Other' ? 'selected' : '' ?>>Other</option></select></div>
                <div class="mb-2"><label class="form-label">Application Status</label><select name="application_status" class="form-select" required>
                    <option value="Pending" <?= ($row['application_status'] ?? 'Pending') == 'Pending' ? 'selected' : '' ?>>Pending</option><option value="Screened" <?= ($row['application_status'] ?? '') == 'Screened' ? 'selected' : '' ?>>Screened</option>
                    <option value="Interviewing" <?= ($row['application_status'] ?? '') == 'Interviewing' ? 'selected' : '' ?>>Interviewing</option><option value="Offered" <?= ($row['application_status'] ?? '') == 'Offered' ? 'selected' : '' ?>>Offered</option>
                    <option value="Hired" <?= ($row['application_status'] ?? '') == 'Hired' ? 'selected' : '' ?>>Hired</option><option value="Rejected" <?= ($row['application_status'] ?? '') == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                </select></div>
                </div><div class="modal-footer"><button type="submit" name="editApplicant" class="btn btn-success">Update</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button></div></form></div></div>
                <?php endwhile; endif; ?>

                <div class="modal fade" id="addHr1JobModal" tabindex="-1" aria-labelledby="addHr1JobModalLabel"><div class="modal-dialog modal-lg"><form method="POST" action="recruitment.php" class="modal-content">
                    <input type="hidden" name="hr4_request_id_source" id="hr4_request_id_source_modal_input">
                    <div class="modal-header"><h5 class="modal-title" id="addHr1JobModalLabel">Add/Create HR1 Job Posting</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <div class="mb-3"><label for="add_hr1_title" class="form-label">Title</label><input type="text" name="title" id="add_hr1_title" class="form-control" required></div>
                        <div class="mb-3"><label for="add_hr1_description" class="form-label">Description</label><textarea name="description" id="add_hr1_description" class="form-control" rows="4" required></textarea></div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label for="add_hr1_jobtype" class="form-label">Job Type</label><input type="text" name="jobtype" id="add_hr1_jobtype" class="form-control" placeholder="e.g., Full-Time, Part-Time" required></div>
                            <div class="col-md-6 mb-3"><label for="add_hr1_department" class="form-label">Department</label><input type="text" name="department" id="add_hr1_department" class="form-control" required></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label for="add_hr1_postingdate" class="form-label">Posting Date</label><input type="date" name="postingdate" id="add_hr1_postingdate" class="form-control" required value="<?= date('Y-m-d') ?>"></div>
                            <div class="col-md-6 mb-3"><label for="add_hr1_status" class="form-label">Status</label><select name="status" id="add_hr1_status" class="form-select" required><option value="Open" selected>Open</option><option value="Closed">Closed</option><option value="Pending">Pending</option></select></div>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" name="addHr1Job" class="btn btn-primary">Save Job Posting</button></div>
                </form></div></div>

            </div> </main>
    </div></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="admin.js"></script> </body>
</html>