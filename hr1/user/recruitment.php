<?php
include '../connections.php';

// Fetch job postings for HR1
$jobs = $connections->query("SELECT * FROM jobposting ORDER BY postingdate DESC");

// Handle applicant registration
if (isset($_POST['registerApplicant'])) {
    $jobpostingID = !empty($_POST['jobpostingID']) ? $_POST['jobpostingID'] : NULL;
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contactnumber = $_POST['contactnumber'];
    $applied_at_input = $_POST['applied_at'];
    $applied_at = !empty($applied_at_input) ? date('Y-m-d H:i:s', strtotime($applied_at_input)) : date('Y-m-d H:i:s');
    
    $age = $_POST['age'];
    $sex = $_POST['sex'];

    $stmt = $connections->prepare("INSERT INTO applicant (jobpostingID, name, email, contactnumber, applied_at, age, sex) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "issssss",
        $jobpostingID,
        $name,
        $email,
        $contactnumber,
        $applied_at,
        $age,
        $sex
    );
    
    if ($stmt->execute()) {
        $applicantID = $connections->insert_id;

        
        if (isset($connections_hr2) && $connections_hr2) {
            
            $position_title = "Unknown";
            if ($jobpostingID) {
                $job_title_stmt = $connections->prepare("SELECT title FROM jobposting WHERE jobpostingID = ?");
                if ($job_title_stmt) {
                    $job_title_stmt->bind_param("i", $jobpostingID);
                    $job_title_stmt->execute();
                    $job_title_result = $job_title_stmt->get_result();
                    if ($job_title_row = $job_title_result->fetch_assoc()) {
                        $position_title = $job_title_row['title'];
                    }
                    $job_title_stmt->close();
                } else {
                    error_log("HR1 New Employee Recruitment: Failed to prepare statement to get job title from hr1.jobposting: " . $connections->error);
                }
            }

           
            $stmt_hr2_employee = $connections_hr2->prepare("INSERT INTO employe_table (EMPLOYEE_ID, FULLNAME, GENDER, POSITION) VALUES (?, ?, ?, ?)");
            if ($stmt_hr2_employee) {
                $stmt_hr2_employee->bind_param(
                    "isss",
                    $applicantID, 
                    $name,           
                    $sex,            
                    $position_title  
                );
                if (!$stmt_hr2_employee->execute()) {
                    error_log("HR1 New Employee Recruitment: Failed to insert into hr2.employe_table: " . $stmt_hr2_employee->error . " (ApplicantID: " . $applicantID . ")");
                }
                $stmt_hr2_employee->close();
            } else {
                error_log("HR1 New Employee Recruitment: Failed to prepare statement for hr2.employe_table: " . $connections_hr2->error);
            }

           
            $default_course_program_id = 4; 
            $default_trainer_id = 6;       
            $default_status_enrollment = "Pending Approval";

            $stmt_hr2_enroll = $connections_hr2->prepare("INSERT INTO trainee_enrollment_approval (TRAINEE_ID, EMPLOYEE_ID, COURSE_PROGRAM, TRAINER, STATUS) VALUES (?, ?, ?, ?, ?)");
            if ($stmt_hr2_enroll) {
                $stmt_hr2_enroll->bind_param(
                    "iiiss",
                    $applicantID,                   // TRAINEE_ID (from hr1.applicantID)
                    $applicantID,                   // EMPLOYEE_ID (from hr1.applicantID, referencing entry in hr2.employe_table)
                    $default_course_program_id,     // COURSE_PROGRAM
                    $default_trainer_id,            // TRAINER
                    $default_status_enrollment      // STATUS
                );
                if (!$stmt_hr2_enroll->execute()) {
                    error_log("HR1 New Employee Recruitment: Failed to insert into hr2.trainee_enrollment_approval: " . $stmt_hr2_enroll->error . " (ApplicantID: " . $applicantID . ")");
                }
                $stmt_hr2_enroll->close();
            } else {
                error_log("HR1 New Employee Recruitment: Failed to prepare statement for hr2.trainee_enrollment_approval: " . $connections_hr2->error);
            }

        } else {
            error_log("HR1 New Employee Recruitment: HR2 database connection (\$connections_hr2) is not available.");
        }
        // ---- END: Integration with HR2 ----

        // Handle compliance document upload (existing logic)
        $complianceDocumentPath = '';
        if (isset($_FILES['compliance_document']) && $_FILES['compliance_document']['error'] == UPLOAD_ERR_OK) {
            $targetDir = "uploads/compliance/"; 
            if (!is_dir($targetDir)) {
                if (!mkdir($targetDir, 0777, true) && !is_dir($targetDir)) {
                    error_log('Failed to create upload directory: ' . $targetDir);
                }
            }
            
            if (is_dir($targetDir) && is_writable($targetDir)) {
                $fileName = uniqid() . "_" . basename($_FILES["compliance_document"]["name"]);
                $targetFile = $targetDir . $fileName;
                if (move_uploaded_file($_FILES["compliance_document"]["tmp_name"], $targetFile)) {
                    $complianceDocumentPath = $targetFile;
                } else {
                    error_log('Failed to move uploaded file: ' . $_FILES["compliance_document"]["name"]);
                }
            } else {
                 error_log('Upload directory is not writable or does not exist: ' . $targetDir);
            }
        }

        if (!empty($_POST['compliance_document_name'])) {
            $defaultStatus = 'Submitted'; 
            $stmt2 = $connections->prepare("INSERT INTO compliancedocument (applicantID, document_name, file_path, submissionDate, document, status) VALUES (?, ?, ?, NOW(), ?, ?)");
            if ($stmt2) {
                $stmt2->bind_param(
                    "issss",
                    $applicantID,
                    $_POST['compliance_document_name'],
                    $_POST['compliance_file_path'], 
                    $complianceDocumentPath,       
                    $defaultStatus
                );
                if(!$stmt2->execute()){
                    error_log("Error inserting compliance document: " . $stmt2->error);
                }
                $stmt2->close();
            } else {
                error_log("Error preparing statement for compliance document: " . $connections->error);
            }
        }
        header("Location: recruitment.php?registered=1");
        exit();
    } else {
        error_log("Error inserting applicant into hr1: " . $stmt->error);
        header("Location: recruitment.php?registration_error=1&msg=" . urlencode($stmt->error));
        exit();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Applicant - HM</title>
    <link rel="shortcut icon" href="../logo.png" type="image/x-icon">
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    />
    <link rel="stylesheet" href="../tm.css"/>
    <link rel="stylesheet" href="user.css"/>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar py-4">
          <div class="text-center mb-4">
            <img src="../logo.png" width="100" alt="Logo" />
            <h5>Hospital Management</h5>
          </div>
          <ul class="nav flex-column">
            <li class="nav-item mb-2">
              <a class="nav-link active" href="user.php"> <i class="fa-solid fa-briefcase"></i> Register / Apply
              </a>
            </li>
            <li class="nav-item mb-2">
              <a class="nav-link" href="job_role_briefing.php">
                <i class="fa-solid fa-info-circle"></i> Job Role Briefing
              </a>
            </li>
            <li class="nav-item mb-2">
              <a class="nav-link" href="application_status.php"> <i class="fa-solid fa-clipboard-list"></i> My Application Status
              </a>
            </li>
          </ul>
          <div class="user-indicator">
            User Panel
          </div>
        </nav>
        <main class="col-md-10 ms-sm-auto px-md-4 py-4">
          <?php if (isset($_GET['registered']) && $_GET['registered'] == 1): ?>
            <div id="alertBox" class="alert alert-success alert-dismissible fade show" role="alert">
              Applicant registered successfully! You will be contacted soon.
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php elseif (isset($_GET['registration_error'])): ?>
             <div id="alertBox" class="alert alert-danger alert-dismissible fade show" role="alert">
              Error during registration: <?= htmlspecialchars($_GET['msg'] ?? 'Unknown error.') ?> Please try again.
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>

          <div class="white-bg">
            <h3 class="mb-4 text-center">Available Job Postings</h3>
            <ul class="nav nav-tabs mb-3" id="recruitmentTabs" role="tablist">
              <li class="nav-item" role="presentation">
                <button
                  class="nav-link active"
                  id="job-tab"
                  data-bs-toggle="tab"
                  data-bs-target="#job"
                  type="button"
                  role="tab"
                >
                  View Job Postings
                </button>
              </li>
              </ul>
            <div class="tab-content" id="recruitmentTabsContent">
              <div class="tab-pane fade show active" id="job" role="tabpanel">
                <div class="d-flex justify-content-end align-items-center mb-3"> <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registerApplicantModal">
                    <i class="fas fa-user-plus me-1"></i> Register as Applicant / Apply
                  </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Job Type</th>
                        <th>Department</th>
                        <th>Posting Date</th>
                        <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($jobs && $jobs->num_rows > 0): ?>
                            <?php $jobs->data_seek(0); while ($row = $jobs->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td><?= nl2br(htmlspecialchars($row['description'])) ?></td>
                                <td><?= htmlspecialchars($row['jobtype']) ?></td>
                                <td><?= htmlspecialchars($row['department']) ?></td>
                                <td><?= htmlspecialchars(date("F j, Y", strtotime($row['postingdate']))) ?></td>
                                <td>
                                    <?php 
                                        $status = strtolower($row['status']);
                                        $badge_class = 'bg-secondary';
                                        if ($status == 'open') $badge_class = 'bg-success';
                                        elseif ($status == 'closed') $badge_class = 'bg-danger';
                                        elseif ($status == 'pending') $badge_class = 'bg-warning text-dark';
                                    ?>
                                    <span class="badge <?= $badge_class ?>"><?= htmlspecialchars(ucfirst($row['status'])) ?></span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center">No job postings currently available.</td></tr>
                        <?php endif; ?>
                    </tbody>
                    </table>
                </div>
                <div class="modal fade" id="registerApplicantModal" tabindex="-1" aria-labelledby="registerApplicantModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-lg">
                    <form method="POST" action="recruitment.php" enctype="multipart/form-data" class="modal-content" style="max-height: 90vh; overflow-y: auto;">
                      <div class="modal-header">
                        <h5 class="modal-title" id="registerApplicantModalLabel">Apply for a Position / Register</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <div class="mb-3">
                          <label for="jobpostingID_modal" class="form-label">Select Job Posting to Apply For <span class="text-danger">*</span></label>
                          <select name="jobpostingID" id="jobpostingID_modal" class="form-select" required>
                            <option value="" disabled selected>-- Select Job --</option>
                            <?php
                              // Re-fetch or reset pointer if $jobs was used above
                              if ($jobs && $jobs->num_rows > 0) {
                                  $jobs->data_seek(0); // Reset pointer
                                  while ($job_modal = $jobs->fetch_assoc()):
                                    if (strtolower($job_modal['status']) == 'open'):
                            ?>
                              <option value="<?= htmlspecialchars($job_modal['jobpostingID']) ?>"><?= htmlspecialchars($job_modal['title']) ?></option>
                            <?php   
                                    endif;
                                  endwhile;
                              }
                            ?>
                          </select>
                        </div>
                        <hr>
                        <h6>Personal Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name_modal" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name_modal" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email_modal" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email_modal" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contactnumber_modal" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                <input type="text" name="contactnumber" id="contactnumber_modal" class="form-control" required>
                            </div>
                             <div class="col-md-6 mb-3">
                                <label for="applied_at_modal" class="form-label">Date Applied <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="applied_at" id="applied_at_modal" class="form-control" value="<?= date('Y-m-d\TH:i') ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="age_modal" class="form-label">Age</label>
                                <input type="number" name="age" id="age_modal" class="form-control" min="18">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sex_modal" class="form-label">Sex <span class="text-danger">*</span></label>
                                <select name="sex" id="sex_modal" class="form-select" required>
                                    <option value="" disabled selected>-- Select --</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <h6>Compliance Document (Optional)</h6>
                        <div class="mb-3">
                            <label for="compliance_document_name_modal" class="form-label">Document Name (e.g., Resume, NBI Clearance)</label>
                            <input type="text" name="compliance_document_name" id="compliance_document_name_modal" class="form-control" placeholder="e.g. Resume">
                        </div>
                        <div class="mb-3">
                            <label for="compliance_file_path_modal" class="form-label">File Link (e.g., Google Drive link to your resume) </label>
                            <input type="url" name="compliance_file_path" id="compliance_file_path_modal" class="form-control" placeholder="https://docs.google.com/...">
                        </div>
                        <div class="mb-3">
                            <label for="compliance_document_modal" class="form-label">Or Upload Document (PDF, DOC, DOCX, JPG, PNG)</label>
                            <input type="file" name="compliance_document" id="compliance_document_modal" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="registerApplicant" class="btn btn-primary">Submit Application</button>
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
    <script src="user.js"></script> </body>
</html>