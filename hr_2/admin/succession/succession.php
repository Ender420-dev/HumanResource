<?php
session_start();
include '../../../phpcon/conn.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Succession Planning</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.2/css/all.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../logo.png">   

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../../../tm.css?v=4.7">
</head>


<body>
<div class="container-fluid">
    <div class="row flex-nowrap">
        <!-- Sidebar -->
        <div class="col-auto col-md-2 col-xl-2 px-sm-1 px-0 min-vh-100 bg-side">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white">
                <a href="#!" class="d-flex text-decoration-none align-items-center mb-md-0 text-white">
                    <span class="fs-5 d-none d-sm-inline" style="color: white;">
                        <div class="media align-items-center">
                            <img src="logo.png" width="100" class="mr-3" alt="">
                        </div>
                        <h3>Hospital Management</h3>
                    </span>
                </a>
                <ul class="nav nav-link flex-column mb-sm-auto mb-0 align-items-center align-items-sm-center" id="menu">
                <li class="nav-item mt-2">
                        <a href="../training_management/training_management.php" class="nav-link  align-middle px-0 text-start">
                            <i class="fa-solid fa-chalkboard-user"></i>
                            <span class="ms-1 d-none d-sm-inline">Training Management</span>
                        </a>
                    </li>

                    <li class="nav-item mt-2">
                        <a href="../learning/learning.php" class="nav-link align-middle px-0 text-start">
                            <i class="fa-solid fa-book-medical"></i>
                            <span class="ms-1 d-none d-sm-inline">Learning Management</span>
                        </a>
                    </li>
                    <li class="nav-item mt-2">
                        <a href="../competency/competency.php" class="nav-link align-middle px-0 text-start">
                            <i class="fa-solid fa-ranking-star"></i>
                            <span class="ms-1 d-none d-sm-inline">Competency Management</span>
                        </a>
                    </li>
                    <li class="nav-item mt-2">
                        <a href="../succession/succession.php" class="nav-link active align-middle px-0 text-start">
                            <i class="fa-solid fa-chart-line"></i>
                            <span class="ms-1 d-none d-sm-inline">Succession Planning</span>
                        </a>
                    </li>
                    <li class="nav-item mt-2">
                        <a href="../ESS/ess.php" class="nav-link  align-middle px-0 text-start">
                            <i class="fa-solid fa-user"></i>
                            <span class="ms-1 d-none d-sm-inline">Employee Self-Services</span>
                        </a>
                    </li>
                </ul>
<hr>
                <div class="dropdown pb-4">
                  <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="ddUser" data-bs-toggle="dropdown" aria-expanded="false">
                  <img src="" width="30" height="30" alt="" class="rounded-circle">  
                  <span class="user" id="user">User</span></a>

                  <ul class="dropdown-menu dropdown-menu-bg text-small shadow" aria-labelledby="ddUser">
    <li><a href="#" class="dropdown-item"><i class="fa-solid fa-user-tie"></i> Profile</a></li>
    <li><a href="#" class="dropdown-item"><i class="fa-solid fa-gears"></i> Settings</a></li>
    <li><a href="#" class="dropdown-item"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>

                  </ul>
                </div>


            </div>
        </div>

        <!-- Main Content -->
        <div class="col main-content py-4">
            <div class="row">
                <div class="col">
                <nav class="navbar navbar-expand-lg">

<ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
  <li class="nav-item">
    <button class="nav-link  active" id="pillsTI-tab" data-bs-toggle="pill" data-bs-target="#pillsTI" aria-controls="pillsTPM" aria-selected="true">Talent Identification</button>
  </li>

  <li class="nav-item">
    <button class="nav-link for-pills" id="pillsCPM-tab" data-bs-toggle="pill" data-bs-target="#pillsCPM" aria-controls="pillsCPM" aria-selected="false">Critical Position Management

    </button>
  </li>

  <li class="nav-item">
    <button class="nav-link for-pills" id="pillsSPD-tab" data-bs-toggle="pill" data-bs-target="#pillsSPD" aria-controls="pillsEnrollment" aria-selected="false">Succession Plan Development
    </button>
  </li>

  <li class="nav-item">
    <button class="nav-link for-pills" id="pillsSI-tab" data-bs-toggle="pill" data-bs-target="#pillsSI" aria-controls="pillsFaculty" aria-selected="false">Successor Identification
    </button>
  </li>

  <li class="nav-item">
    <button class="nav-link for-pills" id="pillsSPM-tab" data-bs-toggle="pill" data-bs-target="#pillsSPM" aria-controls="pillsFaculty" aria-selected="false">Succession Plan Monitor
    </button>
  </li>

  
</ul>

</nav>
              
                <div class="card" style="height: 700px; overflow: hidden;">

                

  <div class="card-content" style="height: 100%;">
    <div class="card-body" style="height: 100%; display: flex; flex-direction: column;">
      <div class="container" style="flex: 1; overflow: hidden;">


      



<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pillsTI" role="tabpanel" >

  
        <div style="overflow-y: auto; height: 100%;">
        <div class="container mt-4">
  <h2 class="text-center mb-4">Talent Identification</h2>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTalentModal">Add Talent</button>

  <!-- Filters -->
  <div class="row mb-3">
    <div class="col-md-4">
      <select class="form-select" id="departmentFilter">
        <option selected>Filter by Department</option>
        <option>Human Resources</option>
        <option>Finance</option>
        <option>IT</option>
        <option>Operations</option>
      </select>
    </div>
    <div class="col-md-4">
      <select class="form-select" id="readinessFilter">
        <option selected>Readiness Level</option>
        <option>High</option>
        <option>Medium</option>
        <option>Low</option>
      </select>
    </div>
    <div class="col-md-4 text-end">
      <button class="btn btn-primary">Export List</button>
    </div>
  </div>

  <!-- Talent Table -->
  <div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th scope="col">Employee</th>
          <th scope="col">Department</th>
          <th scope="col">Current Role</th>
          <th scope="col">Successor For</th>
          <th scope="col">Readiness</th>
          <th scope="col">Potential</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php
              

              $sql = "SELECT * FROM talent_identification ORDER BY TALENT_ID DESC";
              $result = mysqli_query($connection, $sql);

              if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($row['EMPLOYEE']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['DEPARTMENT']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['CURRENT_ROLE']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['SUCCESSOR']) . "</td>";
                  echo "<td><span class='badge bg-" . 
                        ($row['READINESS'] == 'High' ? 'success' : ($row['READINESS'] == 'Medium' ? 'warning text-dark' : 'danger')) . "'>" . 
                        htmlspecialchars($row['READINESS']) . "</span></td>";
                  echo "<td>" . htmlspecialchars($row['POTENTIAL']) . "</td>";
                  echo "<td>
                          <button class='btn btn-sm btn-outline-info'>View</button>
                          <button class='btn btn-sm btn-outline-danger' onclick='deleteTalent(" . $row['TALENT_ID'] . ")'>Delete</button>
                        </td>";
                  echo "</tr>";
                }
              } else {
                echo "<tr><td colspan='7' class='text-center'>No talent records found.</td></tr>";
              }

              mysqli_close($connection);
            ?>
        <!-- Add more rows -->
      </tbody>
    </table>
  </div>
</div>

        </div>
      </div>


      <div class="tab-pane fade" id="pillsCPM" role="tabpanel"> 
        <div style="overflow-y: auto; height: 100%;">
          <div class="row">
            <!-- <div class="col-sm-3">
              <div class="card"  >
                <div class="container">
                  <div class="card-body">
                    <h5 class="card-title">Schedule</h5>

                    <div class="">

                    </div>
                  </div>
                </div>
              </div>
            </div> -->

            <div class="col-sm">
              <div class="card" style="height:700px; overflow:hidden;">
                <div class="card-body">
                 
                <div class="container mt-4">
  <h2 class="text-center mb-4">Critical Position Management</h2>

  <!-- Filters and Controls -->
  <div class="row mb-3">
    <div class="col-md-4">
      <select class="form-select" id="departmentFilter">
        <option selected>Filter by Department</option>
        <option>Executive</option>
        <option>IT</option>
        <option>Operations</option>
        <option>Finance</option>
      </select>
    </div>
    <div class="col-md-4">
      <select class="form-select" id="riskFilter">
        <option selected>Risk Level</option>
        <option>High</option>
        <option>Medium</option>
        <option>Low</option>
      </select>

    </div>
    <div class="col-md-4 text-end">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCritPositionModal">Add Critical Position</button>
          </div>
  </div>

  <!-- Table of Critical Positions -->
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead class="table-dark">
        <tr>
          <th>Position Title</th>
          <th>Department</th>
          <th>Incumbent</th>
          <th>Successors Identified</th>
          <th>Risk Level</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php
             include '../../../phpcon/conn.php';

              $result = mysqli_query($connection, "SELECT * FROM critical_position ORDER BY DEPARTMENT, POSITION_TITLE");

              while ($Critrow = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($Critrow['POSITION_TITLE']) . "</td>";
                echo "<td>" . htmlspecialchars($Critrow['DEPARTMENT']) . "</td>";
                echo "<td>" . htmlspecialchars($Critrow['INCUMBERT']) . "</td>";
                echo "<td>" . htmlspecialchars($Critrow['SUCCESSORS']) . "</td>";
                echo "<td>";

                if ($Critrow['RISKLEVEL'] === 'High') {
                  echo '<span class="badge bg-danger">High</span>';
                } elseif ($Critrow['RISKLEVEL'] === 'Medium') {
                  echo '<span class="badge bg-warning text-dark">Medium</span>';
                } else {
                  echo '<span class="badge bg-success">Low</span>';
                }

                echo "</td>";
                echo "<td>";
                echo ($Critrow['STATUS'] === 'Vacant') ? '<span class="text-danger">Vacant</span>' : 'Occupied';
                echo "</td>";
                echo "<td>
                        <button class='btn btn-sm btn-outline-info'>View</button>
                        <button class='btn btn-sm btn-outline-warning'>Update Risk</button>
                      </td>";
                echo "</tr>";
              }
              ?>
        <!-- Add more rows -->
      </tbody>
    </table>
  </div>
</div>

                </div>
              </div>
            </div>
          </div>
        </div></div>

        <div class="tab-pane fade" id="pillsSPD" role="tabpanel"> 

        
        <br>
        <div style="overflow-y: auto; height: 100%;">
        <div class="row">
  <div class="container mt-4">
  <h2 class="text-center mb-4">Succession Plan Development</h2>

  <!-- Position Selection -->
  <div class="row mb-3">
    <div class="col-md-6">
      <label for="positionSelect" class="form-label">Select Critical Position</label>
      <select class="form-select" id="positionSelect">
        <option selected>Select Position</option>
        <option>Chief Financial Officer</option>
        <option>Operations Director</option>
        <option>Head of HR</option>
      </select>
    </div>
    <div class="col-md-6 text-end">
      <button class="btn btn-success mt-4">Create New Plan</button>
    </div>
  </div>

  <!-- Succession Plan Table -->
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead class="table-dark">
        <tr>
          <th>Successor Name</th>
          <th>Current Role</th>
          <th>Readiness Level</th>
          <th>Development Actions</th>
          <th>Target Readiness Date</th>
          <th>Mentor Assigned</th>
          <th>Progress</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Mary Johnson</td>
          <td>Finance Manager</td>
          <td><span class="badge bg-warning text-dark">12-18 months</span></td>
          <td>Leadership Training, Shadowing CFO</td>
          <td>2026-01-15</td>
          <td>Mark Lee</td>
          <td>
            <div class="progress" style="height: 20px;">
              <div class="progress-bar bg-info" role="progressbar" style="width: 50%">50%</div>
            </div>
          </td>
          <td>
            <button class="btn btn-sm btn-outline-primary">Edit</button>
            <button class="btn btn-sm btn-outline-success">Update Progress</button>
          </td>
        </tr>
        <tr>
          <td>John Doe</td>
          <td>Finance Manager</td>
          <td><span class="badge bg-warning text-dark">12-18 months</span></td>
          <td>Leadership Training, Shadowing CFO</td>
          <td>2026-01-15</td>
          <td>Mark Lee</td>
          <td>
            <div class="progress" style="height: 20px;">
              <div class="progress-bar bg-info" role="progressbar" style="width: 45%">45%</div>
            </div>
          </td>
          <td>
            <button class="btn btn-sm btn-outline-primary">Edit</button>
            <button class="btn btn-sm btn-outline-success">Update Progress</button>
          </td>
        </tr>
        <!-- Add more successor rows -->
      </tbody>
    </table>
  </div>
</div>

        </div>
        </div>
        </div>

 <div class="tab-pane fade" id="pillsSI" role="tabpanel"> 
          
        <br>
        <div style="overflow-y: auto; height: 100%;">
        <div class="container mt-4">
  <h2 class="text-center mb-4">Successor Identification</h2>

  <!-- Filter Section -->
  <div class="row mb-3">
    <div class="col-md-4">
      <label for="criticalPosition" class="form-label">Critical Position</label>
      <select class="form-select" id="criticalPosition">
        <option selected>-- Select Position --</option>
        <option>Chief Operating Officer</option>
        <option>IT Director</option>
        <option>Head of Nursing</option>
      </select>
    </div>
    <div class="col-md-4">
      <label for="readinessFilter" class="form-label">Readiness</label>
      <select class="form-select" id="readinessFilter">
        <option selected>All</option>
        <option>Ready Now</option>
        <option>1-2 Years</option>
        <option>3+ Years</option>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label d-block">&nbsp;</label>
      <button class="btn btn-primary w-100">Search Candidates</button>
    </div>
  </div>

  <!-- Successor Candidates Table -->
  <div class="table-responsive">
    <table class="table table-hover table-bordered">
      <thead class="table-dark">
        <tr>
          <th>Candidate Name</th>
          <th>Current Position</th>
          <th>Performance</th>
          <th>Potential</th>
          <th>Readiness</th>
          <th>Manager Recommendation</th>
          <th>Status</th>
          <th>Nominate</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Angela Rivera</td>
          <td>Senior Project Manager</td>
          <td>Exceeds</td>
          <td>High</td>
          <td><span class="badge bg-success">Ready Now</span></td>
          <td>Yes</td>
          <td><span class="badge bg-warning text-dark">Pending</span></td>
          <td>
            <button class="btn btn-sm btn-success">Nominate</button>
          </td>
        </tr>
        <tr>
          <td>Leo Tran</td>
          <td>Operations Supervisor</td>
          <td>Meets</td>
          <td>Moderate</td>
          <td><span class="badge bg-secondary">1-2 Years</span></td>
          <td>No</td>
          <td><span class="badge bg-secondary">Unnominated</span></td>
          <td>
            <button class="btn btn-sm btn-outline-primary">View Profile</button>
          </td>
        </tr>
        <!-- More candidates -->
      </tbody>
    </table>
  </div>
</div>

        </div>
        
        </div>
        <div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show " id="pillsSPM" role="tabpanel" >

  
        <div style="overflow-y: auto; height: 100%;">
        <div class="container mt-5">
  <h3 class="text-center mb-4">Succession Plan Evaluation Monitor</h3>

  <!-- Evaluation Table -->
  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>Evaluation ID</th>
        <th>Plan ID</th>
        <th>Evaluation Date</th>
        <th>Adjustment Needed</th>
        <th>Effectiveness Rating</th>
        <th>Next Review Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>EVAL-001</td>
        <td>SPM-101</td>
        <td>2025-04-15</td>
        <td>Yes - Successor training lagging</td>
        <td>Moderate</td>
        <td>2025-07-15</td>
        <td>
          <button class="btn btn-sm btn-primary">Edit</button>
          <button class="btn btn-sm btn-info">View</button>
        </td>
      </tr>
      <!-- More rows as needed -->
    </tbody>
  </table>
</div>

  </div>
</div>


        <div class="tab-pane fade" id="pillsRecord" role="tabpanel"> 
          
          <h3 class="white-text card-title text-center">Training Record and Certificate

          </h3>
        <br>
        <div style="overflow-y: auto; height: 100%;">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Trainee ID</th>
                <th>Trainee Name</th>
                <th>Program</th>
               
                <th>Progress</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>John Doe</td>
                <td>Employee Training 101</td>
                <td>Incomplete</td>
                <td>
                <button type="button" class="btn btn-primary btn-sm">Edit</button>
                <button type="button" class="btn btn-info btn-sm">View</button>
                <button type="button" class="btn btn-danger btn-sm">Delete</button>
                <button type="button" class="btn btn-success btn-sm">Generate</button>




                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        </div>
    </div>
  </div>
</div>

                    </div>
                </div>
  </div>
</div>

       
                

                
              

                


                


                
                


                

                
            </div>
        </div>
    </div>
</div><div class="modal fade" id="trainerModal" tabindex="-1" aria-labelledby="trainerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="trainerModalLabel">Trainer Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <h6><strong>Name:</strong> John Doe</h6>
        <p><strong>Email:</strong> johndoe@example.com</p>
        <p><strong>Expertise:</strong> Workplace Safety, HR Compliance</p>

        <hr>
        <h6><strong>Assigned Students:</strong></h6>
        <ul>
          <li>Anna Cruz</li>
          <li>Mark Dela Peña</li>
          <li>Ricky Santos</li>
        </ul>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<div class="modal fade" id="editTrainingModal" tabindex="-1" aria-labelledby="editTrainingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="editTrainingModalLabel">Edit Training Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <!-- Training Info -->
        <form id="editTrainingForm">
        <div class="mb-3">
            <label for="trainingTitle" class="form-label">Program ID</label>
            <input type="number" class="form-control" id="trainingTitle" value="1">
          </div>

        <div class="mb-3">
            <label for="trainingTitle" class="form-label">Program Type</label>
            <input type="text" class="form-control" id="trainingTitle" value="ET101">
          </div>

          <div class="mb-3">
            <label for="trainingTitle" class="form-label">Program Name</label>
            <input type="text" class="form-control" id="trainingTitle" value="Employee Training 101">
          </div>

          <div class="mb-3">
            <label for="trainerName" class="form-label">Trainer</label>
            <input type="text" class="form-control" id="trainerName" value="John Doe">
          </div>
          <div class="form-floating">
  <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
  <label for="floatingTextarea2">Program Description</label>
</div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="startDate" class="form-label">Start Date</label>
              <input type="date" class="form-control" id="startDate" value="2025-05-10">
            </div>
            <div class="col-md-6 mb-3">
              <label for="endDate" class="form-label">End Date</label>
              <input type="date" class="form-control" id="endDate" value="2025-06-10">
            </div>
          </div>
          <div class="mb-3">
            <label for="trainingTitle" class="form-label">Program Type</label>
            <input type="text" class="form-control" id="trainingTitle" value="Employee Training 101">
          </div>


          <hr>

          <!-- Student List -->
          <h6>Participants</h6>
          <ul id="studentList" class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between align-items-center">
              Anna Cruz
              <button type="button" class="btn btn-sm btn-danger">Remove</button>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              Mark Dela Peña
              <button type="button" class="btn btn-sm btn-danger">Remove</button>
            </li>
          </ul>

          <div class="mb-3">
            <label for="newStudent" class="form-label">Add Student</label>
            <input type="text" class="form-control mb-2" id="newStudent" placeholder="Enter student name">
            <button type="button" class="btn btn-secondary" onclick="addStudent()">Add</button>
          </div>
        </form>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-success">Save Changes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>

    </div>
  </div>
</div>
<div class="modal fade" id="addTalentModal" tabindex="-1" aria-labelledby="addTalentLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="addTalent.php" method="post" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addTalentLabel">Add Talent</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="employee" class="form-label">Employee</label>
          <input type="text" name="EMPLOYEE" id="employee" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="department" class="form-label">Department</label>
          <input type="text" name="DEPARTMENT" id="department" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="role" class="form-label">Current Role</label>
          <input type="text" name="CURRENT_ROLE" id="role" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="successor" class="form-label">Successor For</label>
          <input type="text" name="SUCCESSOR" id="successor" class="form-control">
        </div>
        <div class="mb-3">
          <label for="readiness" class="form-label">Readiness</label>
          <select name="READINESS" id="readiness" class="form-select">
            <option>High</option>
            <option>Medium</option>
            <option>Low</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="potential" class="form-label">Potential</label>
          <input type="text" name="POTENTIAL" id="potential" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="submit" class="btn btn-success">Add</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>
<!-- Add Critical Position Modal -->
<div class="modal fade" id="addCritPositionModal" tabindex="-1" aria-labelledby="addPositionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="add_critical_position.php" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Critical Position</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <!-- Form fields -->
          <div class="mb-3">
            <label>Position Title</label>
            <input type="text" class="form-control" name="POSITION_TITLE" required>
          </div>
          <div class="mb-3">
            <label>Department</label>
            <input type="text" class="form-control" name="DEPARTMENT" required>
          </div>
          <div class="mb-3">
            <label>Incumbent</label>
            <input type="text" class="form-control" name="INCUMBERT">
          </div>
          <div class="mb-3">
            <label>Successors Identified</label>
            <input type="text" class="form-control" name="SUCCESSORS">
          </div>
          <div class="mb-3">
            <label>Risk Level</label>
            <select class="form-select" name="RISKLEVEL" required>
              <option>High</option>
              <option>Medium</option>
              <option>Low</option>
            </select>
          </div>
          <div class="mb-3">
            <label>Status</label>
            <select class="form-select" name="STATUS" required>
              <option>Occupied</option>
              <option>Vacant</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- <nav class="navbar navbar-blue" style="height:70px;">
<div class="container">
    <a href="#!" class="navbar-brand" data-bs-toggle="offcanvas" aria-controls="staticBackdrop" data-bs-target="#sideBarNav" >
        <img src="logo.png" style="margin-top:-25px;" width="90" height="90" alt=""> <span class="white-text" style=" ">HR 2</span></a></div>
</nav> -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="../mdb/js/mdb.es.min.js"></script>

    
    <script>

window.addEventListener('scroll',function(){
    let sidenav=document.querySelector('.bg-side');
    if (window.scrollY>50){
        sidenav.classList.add('fixed-sidebar');
    }else{
        sidenav.classList.remove('fixed-sidebar');
    }
});




document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
  timeZone: 'UTC',
  initialView: 'dayGridMonth', // default to month view
  editable: true,
  selectable: true,
  events: 'fetch-events.php',

  // Custom toolbar buttons
  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,timeGridWeek,timeGridDay'
  },

  // Selection for new event
  select: function(info) {
    var title = prompt('Enter Event Title:');
    if (title) {
      const newEvent = {
        title: title,
        start: info.startStr,
        end: info.endStr
      };

      calendar.addEvent(newEvent);

      fetch('add-event.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(newEvent)
      });
    }
    calendar.unselect();
  },

  eventDrop: function(info) {
    updateEvent(info.event);
  },

  eventResize: function(info) {
    updateEvent(info.event);
  }
});


  calendar.render();

  function updateEvent(event) {
    fetch('update-event.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        id: event.id,
        title: event.title,
        start: event.start.toISOString(),
        end: event.end ? event.end.toISOString() : null
      })
    });
  }
});
function deleteTalent(id) {
  if (confirm("Delete this talent record?")) {
    fetch('deleteTalent.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'TALENT_ID=' + encodeURIComponent(id)
    })
    .then(res => res.text())
    .then(response => {
      if (response.trim() === "Success") {
        alert("Deleted successfully");
        location.reload();
      } else {
        alert("Error: " + response);
      }
    })
    .catch(error => alert("Fetch Error: " + error));
  }
}

    </script>


</body>
</html>