<?php
include '../connections.php';

$applicants_query_result = $connections->query(" 
    SELECT 
      a.name,
      a.email,
      jp.title AS position,
      oa.status AS offer_status, /* aliased status to avoid ambiguity */
      a.applied_at
    FROM applicant a
    LEFT JOIN offerapproval oa ON a.applicantID = oa.applicantID
    LEFT JOIN jobposting jp ON a.jobpostingID = jp.jobpostingID
    ORDER BY a.applied_at DESC
");

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
              <a class="nav-link" href="recruitment.php">
                <i class="fa-solid fa-briefcase"></i> Register / Apply
              </a>
            </li>
            <li class="nav-item mb-2">
              <a class="nav-link" href="job_role_briefing.php">
                <i class="fa-solid fa-info-circle"></i> Job Role Briefing
              </a>
            </li>
            <li class="nav-item mb-2">
              <a class="nav-link active" href="my_application_status.php">
                <i class="fa-solid fa-clipboard-list"></i> My Application Status
              </a>
            </li>
          </ul>
          <div class="user-indicator">
            User Panel
          </div>
        </nav>
        <main class="col-md-10 ms-sm-auto px-md-4 py-4">
          <div class="white-bg card-panel">
            <h3 class="mb-4 text-center section-title">My Application Status</h3>
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Applicant Name</th> <th>Position Applied</th>
                        <th>Offer Status</th>
                        <th>Date Applied</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($applicants_query_result && $applicants_query_result->num_rows > 0) {
                         $applicants_query_result->data_seek(0); 
                        while ($row = $applicants_query_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td> <td><?= htmlspecialchars($row['position']) ?></td>
                            <td>
                            <?php
                                $offer_stat = $row['offer_status'] ?? null;
                                if ($offer_stat == 'Approved') { echo '<span class="badge bg-success">OFFER APPROVED</span>';} 
                                elseif ($offer_stat == 'Pending') { echo '<span class="badge bg-warning text-dark">OFFER PENDING</span>';} 
                                elseif ($offer_stat == 'Rejected') { echo '<span class="badge bg-danger">OFFER REJECTED</span>';} 
                                else { echo '<span class="badge bg-info">APPLIED</span>';} 
                            ?>
                            </td>
                            <td><?= htmlspecialchars($row['applied_at']) ?></td>
                        </tr>
                        <?php endwhile; 
                    } else {
                        echo '<tr><td colspan="4" class="text-center">No application status to display.</td></tr>';
                    }
                    ?>
                </tbody>
                </table>
            </div>
          </div>
        </main>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="user.js"></script> </body>
</html>