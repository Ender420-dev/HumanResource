<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Self-Services</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.2/css/all.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../logo.png">   

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="../../../tm.css?v=4.7">
    <!-- <link rel="stylesheet" href="../../../tm.css?v=4.5"> -->
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
                        <a href="../succession/succession.php" class="nav-link align-middle px-0 text-start">
                            <i class="fa-solid fa-chart-line"></i>
                            <span class="ms-1 d-none d-sm-inline">Succession Planning</span>
                        </a>
                    </li>
                    <li class="nav-item mt-2">
                        <a href="../ESS/ess.php" class="nav-link active  align-middle px-0 text-start">
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
    <button class="nav-link  active" id="pillsPM-tab" data-bs-toggle="pill" data-bs-target="#pillsPM" aria-controls="pillsTPM" aria-selected="true">Profile Management</button>
  </li>

  <li class="nav-item">
    <button class="nav-link for-pills" id="pillsPM2-tab" data-bs-toggle="pill" data-bs-target="#pillsPM2" aria-controls="pillsCalendar" aria-selected="false">Performance Management</button>
  </li>

  <li class="nav-item">
    <button class="nav-link for-pills" id="pillsPSI-tab" data-bs-toggle="pill" data-bs-target="#pillsPSI" aria-controls="pillsEnrollment" aria-selected="false">Payroll 
    </button>
  </li>

  <li class="nav-item">
    <button class="nav-link for-pills" id="pillsLAM-tab" data-bs-toggle="pill" data-bs-target="#pillsLAM" aria-controls="pillsFaculty" aria-selected="false">Leave and Attendance Management
    </button>
  </li>

  
</ul>
              </nav>
                <div class="card" style="height: 700px; overflow: hidden;">

                

  <div class="card-content" style="height: 100%;">
    <div class="card-body" style="height: 100%; display: flex; flex-direction: column;">
      <div class="container" style="flex: 1; overflow: hidden;">


      

<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pillsPM" role="tabpanel" >

  
        <br>
        <div style="overflow-y: auto; height: 100%;">
        <div class="container mt-4">
  <h2 class="mb-4 text-center">Employee Profile Management (Admin)</h2>

  <!-- Search Bar -->
  <div class="row mb-3">
    <div class="col-md-6">
      <input type="text" class="form-control" placeholder="Search by name or Employee ID" id="searchInput">
    </div>
  </div>

  <!-- Employee List Table -->
  <table class="table table-hover">
    <thead class="table-dark">
      <tr>
        <th>Employee ID</th>
        <th>Name</th>
        <th>Department</th>
        <th>Position</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>001</td>
        <td>Jane Doe</td>
        <td>IT</td>
        <td>Developer</td>
        <td>
          <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editEmployeeModal">Edit</button>
        </td>
      </tr>
      <!-- More employee rows -->
    </tbody>
  </table>

  <!-- Edit Modal -->
  <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Edit Employee Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <form>
            <!-- Basic Info -->
            <div class="row">
              <div class="col-md-6 mb-3">
                <label>Employee ID</label>
                <input type="text" class="form-control" value="001" readonly>
              </div>
              <div class="col-md-6 mb-3">
                <label>Profile ID</label>
                <input type="text" class="form-control" value="P-0001-2025" readonly>
              </div>
            </div>

            <!-- Personal Info -->
            <div class="row">
              <div class="col-md-6 mb-3">
                <label>Full Name</label>
                <input type="text" class="form-control" value="Jane Doe">
              </div>
              <div class="col-md-6 mb-3">
                <label>Date of Birth</label>
                <input type="date" class="form-control" value="1990-01-01">
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label>Phone Number</label>
                <input type="text" class="form-control" value="09171234567">
              </div>
              <div class="col-md-6 mb-3">
                <label>Address</label>
                <input type="text" class="form-control" value="123 ABC Street, QC">
              </div>
            </div>

            <!-- Professional Info -->
            <div class="row">
              <div class="col-md-6 mb-3">
                <label>Department</label>
                <input type="text" class="form-control" value="IT">
              </div>
              <div class="col-md-6 mb-3">
                <label>Position</label>
                <input type="text" class="form-control" value="Developer">
              </div>
            </div>

            <!-- Profile Picture Upload -->
            <div class="mb-3">
              <label>Profile Picture</label>
              <input type="file" class="form-control">
            </div>

            <div class="text-end">
              <button type="submit" class="btn btn-success">Save Changes</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
</div>

        </div>
      </div>
    </div>
      <div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show " id="pillsPM2" role="tabpanel" >

        <br>
        <div style="overflow-y: auto; height: 100%;">
         

        <div class="container mt-4">
  <h2 class="text-center mb-4">Performance Review Management</h2>

  <!-- Search/Filter -->
  <div class="row mb-3">
    <div class="col-md-6">
      <input type="text" class="form-control" placeholder="Search by Employee ID" id="searchInput">
    </div>
    <div class="col-md-6 text-end">
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal">Add Review</button>
    </div>
  </div>

  <!-- Review Table -->
  <table class="table table-hover">
    <thead class="table-dark">
      <tr>
        <th>Review ID</th>
        <th>Employee ID</th>
        <th>Review Date</th>
        <th>Rating</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>REV001</td>
        <td>EMP001</td>
        <td>2025-04-01</td>
        <td>Excellent</td>
        <td>
          <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#reviewModal">Edit</button>
        </td>
      </tr>
      <!-- Repeat rows -->
    </tbody>
  </table>

  <!-- Review Modal -->
  <div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Add/Edit Performance Review</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <form>
            <!-- IDs and Date -->
            <div class="row mb-3">
              <div class="col-md-4">
                <label>Review ID</label>
                <input type="text" class="form-control" value="REV001" readonly>
              </div>
              <div class="col-md-4">
                <label>Employee ID</label>
                <input type="text" class="form-control" value="EMP001">
              </div>
              <div class="col-md-4">
                <label>Review Date</label>
                <input type="date" class="form-control" value="2025-04-01">
              </div>
            </div>

            <!-- Rating -->
            <div class="mb-3">
              <label>Performance Rating</label>
              <select class="form-select">
                <option>Excellent</option>
                <option>Good</option>
                <option>Average</option>
                <option>Needs Improvement</option>
              </select>
            </div>

            <!-- Strengths -->
            <div class="mb-3">
              <label>Strengths</label>
              <textarea class="form-control" rows="2">Teamwork, Communication</textarea>
            </div>

            <!-- Areas for Improvement -->
            <div class="mb-3">
              <label>Areas for Improvement</label>
              <textarea class="form-control" rows="2">Time management</textarea>
            </div>

            <!-- Development Plan -->
            <div class="mb-3">
              <label>Development Plan</label>
              <textarea class="form-control" rows="3">Enroll in time management training</textarea>
            </div>

            <div class="text-end">
              <button type="submit" class="btn btn-success">Save Review</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
</div>




        </div>
      </div>
      </div>
      <div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show " id="pillsPSI" role="tabpanel" >

  
        <br>
        <div style="overflow-y: auto; height: 100%;">
         

        <div class="container mt-5">
  <h2 class="text-center mb-4">Payroll </h2>

  <!-- Search Filter -->
  <div class="row mb-3">
    <div class="col-md-6">
      <input type="text" class="form-control" placeholder="Search by Employee ID">
    </div>
    
  </div>

  <!-- Payroll Table -->
  <table class="table table-hover">
    <thead class="table-dark">
      <tr>
        <th>Payroll ID</th>
        <th>Employee ID</th>
        <th>Last Pay Date</th>
        <th>Total Salary</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>PAY001</td>
        <td>EMP001</td>
        <td>2025-04-30</td>
        <td>₱50,000</td>
        <td>
          <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#payrollModal">View</button>
        </td>
      </tr>
    </tbody>
  </table>

  <!-- Payroll Modal -->
  <div class="modal fade" id="payrollModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Add/Edit Payroll</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <form>
            <div class="row mb-3">
              <div class="col-md-4">
                <label>Payroll ID</label>
                <input type="text" class="form-control" value="PAY001" readonly>
              </div>
              <div class="col-md-4">
                <label>Employee ID</label>
                <input type="text" class="form-control" value="EMP001">
              </div>
              <div class="col-md-4">
                <label>Last Pay Date</label>
                <input type="date" class="form-control" value="2025-04-30">
              </div>
            </div>

            <h6>Salary Breakdown</h6>
            <div class="row mb-3">
              <div class="col-md-4">
                <label>Base Salary</label>
                <input type="number" class="form-control" value="40000">
              </div>
              <div class="col-md-4">
                <label>Allowances</label>
                <input type="number" class="form-control" value="5000">
              </div>
              <div class="col-md-4">
                <label>Deductions</label>
                <input type="number" class="form-control" value="-2000">
              </div>
            </div>

            <div class="mb-3">
              <label>Benefits</label>
              <textarea class="form-control" rows="2">HMO, 13th Month, Insurance</textarea>
            </div>

            <div class="mb-3">
              <label>Payroll History (optional log)</label>
              <textarea class="form-control" rows="3">Paid: 2025-04-30 ₱50,000 | Paid: 2025-03-30 ₱49,000</textarea>
            </div>

            <div class="text-end">
              <button type="submit" class="btn btn-success">Save Payroll</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
</div>




        </div>
      </div></div>


      
      <div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show " id="pillsLAM" role="tabpanel" >

 
        <br>
        <div style="overflow-y: auto; height: 100%;">
         
        <div class="container mt-5">
  <h2 class="text-center mb-4">Leave & Attendance Management</h2>

  <!-- Tabs -->
  <ul class="nav nav-pills mb-3" id="leaveTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link button-1 active" id="attendance-tab" data-bs-toggle="pill" data-bs-target="#attendance" type="button">Attendance History</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link button-1" id="leave-tab" data-bs-toggle="pill" data-bs-target="#leave" type="button">Leave Requests</button>
    </li>
  </ul>

  <div class="tab-content" id="leaveTabContent">
    
    <!-- Attendance History Tab -->
    <div class="tab-pane fade show active" id="attendance" role="tabpanel">
      <table class="table table-striped">
        <thead class="table-dark">
          <tr>
            <th>Date</th>
            <th>Employee ID</th>
            <th>Status</th>
            <th>Time In</th>
            <th>Time Out</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>2025-04-30</td>
            <td>EMP001</td>
            <td>Present</td>
            <td>08:00 AM</td>
            <td>05:00 PM</td>
          </tr>
          <!-- More rows -->
        </tbody>
      </table>
    </div>

    <!-- Leave Requests Tab -->
    <div class="tab-pane fade" id="leave" role="tabpanel">
      <table class="table table-hover">
        <thead class="table-secondary">
          <tr>
            <th>Leave ID</th>
            <th>Employee ID</th>
            <th>Type</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>L001</td>
            <td>EMP002</td>
            <td>Sick Leave</td>
            <td>2025-05-01</td>
            <td>2025-05-03</td>
            <td>Pending</td>
            <td>
              <button class="btn btn-success btn-sm">Approve</button>
              <button class="btn btn-danger btn-sm">Reject</button>
            </td>
          </tr>
          <!-- More leave rows -->
        </tbody>
      </table>
    </div>
  </div>
</div>





        </div>
      </div>



      <div class="tab-pane fade" id="pillsCalendar" role="tabpanel"> <h3 class="white-text card-title text-center">Training Calendar and Scheduling</h3>
        <br>
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
                  <h5 class="card-title">Calendar</h5>
                  <div id="calendar" style=" min-height: 500px;"></div>
                  
                </div>
              </div>
            </div>
          </div>
        </div></div>

        <div class="tab-pane fade" id="pillsEnrollment" role="tabpanel"> 

          <h3 class="white-text card-title text-center">Trainee Enrollment and Approval</h3>
        <br>
        <div style="overflow-y: auto; height: 100%;">
        <div class="row">
  <div class="col-md-2">
    <!-- Vertical Nav Pills -->
    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
    <button class="nav-link new-tab active" id="v-pills-new-tab" data-bs-toggle="pill" data-bs-target="#v-pills-new" type="button" role="tab" aria-controls="v-pills-new" aria-selected="true">New</button>
        <button class="nav-link approved-tab" id="v-pills-approve-tab" data-bs-toggle="pill" data-bs-target="#v-pills-approve" type="button" role="tab" aria-controls="v-pills-approve" aria-selected="false">Approved</button>
        <button class="nav-link rejected-tab" id="v-pills-rejected-tab" data-bs-toggle="pill" data-bs-target="#v-pills-rejected" type="button" role="tab" aria-controls="v-pills-rejected" aria-selected="false">Rejected</button>

    </div>
  </div>

  <div class="col-md-10">
    <!-- Tab Content -->
    <div class="tab-content" id="v-pills-tabContent">
      <div class="tab-pane fade show active" id="v-pills-new" role="tabpanel" aria-labelledby="v-pills-new-tab">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">New Enrollment</h5>
          </div>
        </div>
      </div>

      <div class="tab-pane fade" id="v-pills-approve" role="tabpanel" aria-labelledby="v-pills-approve-tab">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Approved Enrollments</h5>
          </div>
        </div>
      </div>

      <div class="tab-pane fade" id="v-pills-rejected" role="tabpanel" aria-labelledby="v-pills-rejected-tab">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Rejected Enrollments</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
        </div>
        </div>
        </div>

 <div class="tab-pane fade" id="pillsFaculty" role="tabpanel"> 
          
          <h3 class="white-text card-title text-center">Trainer Faculty Management
          </h3>
        <br>
        <div style="overflow-y: auto; height: 100%;">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Trainer ID</th>
                <th>Full Name</th>
                <th>Subject</th>
                <th>Action</th>

              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>John Doe</td>
                <td>Employee 101</td>
                <td>

                <button type="button" class="btn btn-success">View</button>
                <button type="button" class="btn btn-primary">Edit</button>
                <button type="button" class="btn btn-danger">Delete</button>

              </td>
              </tr>
            </tbody>
          </table>
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

document.getElementById("imageUpload").addEventListener("change", function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById("imagePreview").style.backgroundImage = `url(${e.target.result})`;
        };
        reader.readAsDataURL(file);
    }
});


    </script>


</body>
</html>