<?php
include '../connections.php';

$jobs_query = $connections->query("SELECT * FROM jobposting ORDER BY postingdate DESC");
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
              <a class="nav-link active" href="job_role_briefing.php">
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
          <div class="white-bg card-panel">
            <h3 class="mb-4 text-center section-title">Job Role Briefing</h3>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php if ($jobs_query && $jobs_query->num_rows > 0): ?>
                    <?php while ($job = $jobs_query->fetch_assoc()): ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?= htmlspecialchars($job['title']) ?></h5>
                                    <h6 class="card-subtitle mb-3 text-muted">Department: <?= htmlspecialchars($job['department']) ?></h6>
                                    <p class="card-text flex-grow-1">
                                        <strong>Description:</strong> <?= nl2br(htmlspecialchars($job['description'])) ?>
                                    </p>
                                    <p class="card-text">
                                        <strong>Job Type:</strong> <?= htmlspecialchars($job['jobtype']) ?>
                                    </p>
                                    <p class="card-text">
                                        <strong>Posting Date:</strong> <?= htmlspecialchars(date("F j, Y", strtotime($job['postingdate']))) ?>
                                    </p>
                                    <p class="card-text">
                                        <strong>Status:</strong>
                                        <?php
                                            $status = strtolower($job['status']);
                                            $badge_class = 'bg-secondary';
                                            if ($status == 'open') $badge_class = 'bg-success';
                                            elseif ($status == 'closed') $badge_class = 'bg-danger';
                                            elseif ($status == 'pending') $badge_class = 'bg-warning text-dark';
                                        ?>
                                        <span class="badge <?= $badge_class ?>"><?= htmlspecialchars(ucfirst($job['status'])) ?></span>
                                    </p>

                                    <hr class="my-3">

                                    <h6 class="mt-auto">Responsibilities:</h6>
                                    <ul class="card-text-list">
                                        <li>Performing duties as outlined in the job description for a <?= htmlspecialchars($job['title']) ?>.</li>
                                        <li>Collaborating with team members within the <?= htmlspecialchars($job['department']) ?> department.</li>
                                        <li>Adhering to hospital policies and procedures.</li>
                                        <li>Maintaining a high standard of work performance.</li>
                                    </ul>

                                    <h6>Qualifications:</h6>
                                    <ul class="card-text-list">
                                        <li>Relevant educational background and certifications for a <?= htmlspecialchars($job['title']) ?>.</li>
                                        <li>Prior experience in a similar role or healthcare setting is preferred.</li>
                                        <li>Strong communication and interpersonal skills.</li>
                                        <li>Ability to work effectively in a dynamic environment.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p>No job role briefings currently available.</p>
                    </div>
                <?php endif; ?>
            </div>
          </div>
        </main>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="user.js"></script>
  </body>
</html>