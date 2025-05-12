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
    <link rel="stylesheet" href="../../tm.css?v=4.5">
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
                        <a href="../training_management/training_management.php" class="nav-link align-middle px-0 text-start">
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
                        <a href="../ESS/ess.php" class="nav-link active align-middle px-0 text-start">
                            <i class="fa-solid fa-user"></i>
                            <span class="ms-1 d-none d-sm-inline">Employee Self-Services</span>
                        </a>
                    </li>
                </ul>
                <hr>
                <hr>
                <hr>
                <div class="dropdown pb-4 mt-auto px-3">
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
        <div class="col main-content ">
            <div class="row">
                    <div class="col">
                  <!-- Navbar -->
<nav class="navbar navbar-light navbar-expand-lg" style="background: #4A628A; color:white;">
  <div class="container-fluid">
    <a class="navbar-brand text-white" href="#">Employee Self-Services Portal</a>

    <div class="dropdown ms-auto">
      <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="ddUser" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="" width="30" height="30" alt="" class="rounded-circle me-2">
        <span id="user">User</span>
      </a>

      <ul class="dropdown-menu dropdown-menu-end black-text shadow" style="color:black !important;" aria-labelledby="ddUser">
  <li><a class="dropdown-item nav-link" style="color:black !important;" data-bs-target="#pillDashboard"><i class="fa-solid fa-user-tie"></i> Dashboard</a></li>
  <li><a class="dropdown-item nav-link" style="color:black !important;" data-bs-target="#pillProfile"><i class="fa-solid fa-gears"></i> Profile Management</a></li>
  <li><a class="dropdown-item nav-link" style="color:black !important;" data-bs-target="#pillLeave"><i class="fa-solid fa-calendar-check"></i> Leave and Attendance</a></li>
  <li><a class="dropdown-item nav-link" style="color:black !important;" data-bs-target="#pillPayroll"><i class="fa-solid fa-money-bill-wave"></i> Payroll Info</a></li>
</ul>

    </div>
  </div>
</nav>

<!-- Card + Tab Content -->
<div class="card" style="height: 700px; overflow: hidden; overflow-y:auto; max-height:700px;">
  <div class="card-body" style="height: 100%; ">
    <div class="container" style="flex: 1; overflow: hidden;">
      <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pillDashboard" role="tabpanel">
          <h3 class="white-text card-title text-center">Dashboard</h3>
<div class="row">
    <div class="col-sm-8">
        <div class="card" style="height: 100%; overflow:hidden; background-color: #4A628A; color: white;"> 
    <div class=" container mt-4"  style=" ">
  <h2 class="text-center mb-4">Attendance</h2>

  <!-- Summary Cards -->
  <div class="row mb-4">
    <div class="col-md-3">
      <div class="card bg-success text-white text-center" style="height: 120px;">
        <div class="card-body">
          <h5>Days Present</h5>
          <h3>22</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-danger text-white text-center" style="height: 120px;">
        <div class="card-body">
          <h5>Days Absent</h5>
          <h3>3</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-warning text-dark text-center" style="height: 120px;">
        <div class="card-body">
          <h5>Leaves Taken</h5>
          <h3>2</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-info text-white text-center" style="height: 120px;">
        <div class="card-body">
          <h5>Late Arrivals</h5>
          <h3>1</h3>
        </div>
      </div>
    </div>
  </div>

  <!-- Attendance Chart -->
  <div class="card mb-4">
    <div class="card-body">
      <h5 class="card-title">Monthly Attendance Overview</h5>
      <canvas id="attendanceChart"></canvas>
    </div>
  </div>
</div></div>




    </div>
    <div class="col-sm-4">
  <div class="card mb-4" style="height: 100%;">
    <div class="container mt-4">
      <div class="card-body">
        <h2 class="card-title text-center">Payroll</h2>
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>Date</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>2025-01-31</td>
              <td>$3,500</td>
            </tr>
            <tr>
              <td>2025-02-29</td>
              <td>$3,500</td>
            </tr>
            <tr>
              <td>2025-03-31</td>
              <td>$3,700</td>
            </tr>
            <tr>
              <td>2025-04-30</td>
              <td>$3,800</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col">
  <div class="container mt-4">
    <h2 class="text-center mb-4">Learning & Training Performance Dashboard</h2>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center bg-primary text-white">
                <div class="card-body">
                    <h5>Completed Courses</h5>
                    <h3>12</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-warning text-dark">
                <div class="card-body">
                    <h5>Ongoing Courses</h5>
                    <h3>3</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <h5>Certifications Earned</h5>
                    <h3>5</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-info text-white">
                <div class="card-body">
                    <h5>Training Hours</h5>
                    <h3>48 hrs</h3>
                </div>
            </div>
        </div>
    </div>

    

    <!-- Recent Trainings Table -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Recent Trainings</h5>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Status</th>
                        <th>Completion Date</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Advanced Healthcare Safety</td>
                        <td><span class="badge bg-success">Completed</span></td>
                        <td>2025-04-20</td>
                        <td>100%</td>
                    </tr>
                    <tr>
                        <td>Data Privacy & Security</td>
                        <td><span class="badge bg-warning text-dark">In Progress</span></td>
                        <td>--</td>
                        <td>60%</td>
                    </tr>
                    <tr>
                        <td>Patient Care Excellence</td>
                        <td><span class="badge bg-success">Completed</span></td>
                        <td>2025-03-10</td>
                        <td>100%</td>
                    </tr>
                    <tr>
                        <td>Medical Records Compliance</td>
                        <td><span class="badge bg-warning text-dark">In Progress</span></td>
                        <td>--</td>
                        <td>40%</td>
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
        <div class="tab-pane fade" id="pillProfile" role="tabpanel">
          <h3 class="white-text card-title text-center">Profile Management</h3>
<!-- Profile Section -->
<div class="row">
    <div class="col">
        <div class="card" style="height:100%">
            <div class="card-body">
                <div class="container">
                    <h2 class="card-title text-center">Employee Profile</h2>
                    <form>
                        <div class="row mb-4">
                            <div class="col-auto">
                                <!-- Avatar Upload -->
                                <div class="avatar-upload">
                                    <div class="avatar-edit">
                                        <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" />
                                        <label for="imageUpload"><i class="fa fa-pen-to-square"></i></label>
                                    </div>
                                    <div class="avatar-preview">
                                        <div id="imagePreview" style="background-image: url('default.jpg');"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Fields -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" id="fullName" class="form-control" placeholder="John Doe">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" class="form-control" placeholder="john@example.com">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="position" class="form-label">Position</label>
                                <input type="text" id="position" class="form-control" placeholder="Software Engineer">
                            </div>
                            <div class="col-md-6">
                                <label for="department" class="form-label">Department</label>
                                <input type="text" id="department" class="form-control" placeholder="IT">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Profile</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col">
          
        </div>
    </div>
</div>


        </div>
        <div class="tab-pane fade" id="pillLeave" role="tabpanel">
  <h3 class="white-text card-title text-center mb-4">Leave and Attendance</h3>

  <div class="d-flex justify-content-end mb-3">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLeaveModal">
      <i class="fa fa-plus"></i> Add Leave Request
    </button>
  </div>

  <!-- Leave Table -->
  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Leave Type</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Days</th>
          <th>Status</th>
          <th>Remarks</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Annual Leave</td>
          <td>2025-04-10</td>
          <td>2025-04-14</td>
          <td>5</td>
          <td><span class="badge bg-success">Approved</span></td>
          <td>Family trip</td>
        </tr>
        <tr>
          <td>Sick Leave</td>
          <td>2025-05-05</td>
          <td>2025-05-06</td>
          <td>2</td>
          <td><span class="badge bg-warning text-dark">Pending</span></td>
          <td>Flu symptoms</td>
        </tr>
        <tr>
          <td>Emergency Leave</td>
          <td>2025-03-15</td>
          <td>2025-03-15</td>
          <td>1</td>
          <td><span class="badge bg-danger">Rejected</span></td>
          <td>Personal reasons</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Add Leave Modal -->
  <div class="modal fade" id="addLeaveModal" tabindex="-1" aria-labelledby="addLeaveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addLeaveModalLabel">New Leave Request</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label for="leaveType" class="form-label">Leave Type</label>
              <select class="form-select" id="leaveType">
                <option>Annual Leave</option>
                <option>Sick Leave</option>
                <option>Emergency Leave</option>
                <option>Unpaid Leave</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="startDate" class="form-label">Start Date</label>
              <input type="date" class="form-control" id="startDate">
            </div>
            <div class="mb-3">
              <label for="endDate" class="form-label">End Date</label>
              <input type="date" class="form-control" id="endDate">
            </div>
            <div class="mb-3">
              <label for="remarks" class="form-label">Remarks</label>
              <textarea class="form-control" id="remarks" rows="3"></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Submit Request</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="tab-pane fade" id="pillPayroll" role="tabpanel">
  <h3 class="white-text card-title text-center mb-4">Payroll Information</h3>

  <!-- Payroll Summary Cards -->
  <div class="row mb-4">
    <div class="col-md-3">
      <div class="card text-center bg-primary text-white">
        <div class="card-body">
          <h5>Total Earnings</h5>
          <h3>$14,500</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center bg-success text-white">
        <div class="card-body">
          <h5>Total Deductions</h5>
          <h3>$2,300</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center bg-warning text-dark">
        <div class="card-body">
          <h5>Net Pay</h5>
          <h3>$12,200</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center bg-info text-white">
        <div class="card-body">
          <h5>Last Salary</h5>
          <h3>$3,800</h3>
        </div>
      </div>
    </div>
  </div>

  <!-- Payroll Table -->
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead class="table-dark">
        <tr>
          <th>Month</th>
          <th>Basic Pay</th>
          <th>Allowances</th>
          <th>Deductions</th>
          <th>Net Pay</th>
          <th>Payslip</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>April 2025</td>
          <td>$3,500</td>
          <td>$500</td>
          <td>$200</td>
          <td>$3,800</td>
          <td><button class="btn btn-sm btn-outline-primary">View Payslip</button></td>
        </tr>
        <tr>
          <td>March 2025</td>
          <td>$3,500</td>
          <td>$400</td>
          <td>$200</td>
          <td>$3,700</td>
          <td><button class="btn btn-sm btn-outline-primary">View Payslip</button></td>
        </tr>
        <tr>
          <td>February 2025</td>
          <td>$3,500</td>
          <td>$400</td>
          <td>$200</td>
          <td>$3,700</td>
          <td><button class="btn btn-sm btn-outline-primary">View Payslip</button></td>
        </tr>
        <tr>
          <td>January 2025</td>
          <td>$3,500</td>
          <td>$400</td>
          <td>$200</td>
          <td>$3,700</td>
          <td><button class="btn btn-sm btn-outline-primary">View Payslip</button></td>
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
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="../mdb/js/mdb.es.min.js"></script>

    
    <script>

const ctx = document.getElementById('attendanceChart').getContext('2d');
  const attendanceChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
      datasets: [{
        label: 'Days Present',
        data: [5, 4, 5, 5],
        backgroundColor: 'rgba(40, 167, 69, 0.7)',
      }, {
        label: 'Days Absent',
        data: [0, 1, 1, 1],
        backgroundColor: 'rgba(220, 53, 69, 0.7)',
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
document.querySelectorAll('.dropdown-menu .dropdown-item').forEach(function(item) {
    item.addEventListener('click', function (e) {
      e.preventDefault();
      const targetTab = document.querySelector(this.getAttribute('data-bs-target'));
      
      if (targetTab) {
        // Remove 'show active' from all panes
        document.querySelectorAll('.tab-pane').forEach(pane => {
          pane.classList.remove('show', 'active');
        });

        // Add 'show active' to selected pane
        targetTab.classList.add('show', 'active');

        // Close the dropdown
        const dropdown = bootstrap.Dropdown.getInstance(document.getElementById('ddUser'));
        if (dropdown) dropdown.hide();
      }
    });
  });
window.addEventListener('scroll',function(){
    let sidenav=document.querySelector('.bg-side');
    if (window.scrollY>50){
        sidenav.classList.add('fixed-sidebar');
    }else{
        sidenav.classList.remove('fixed-sidebar');
    }
});

    </script>


</body>
</html>