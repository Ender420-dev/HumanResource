<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Management</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.2/css/all.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../logo.png">   

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../../../tm.css?v=3.9">
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
                        <a href="../competency/competency.php" class="nav-link active align-middle px-0 text-start">
                            <i class="fa-solid fa-ranking-star"></i>
                            <span class="ms-1 d-none d-sm-inline">Competency Management</span>
                        </a>
                    </li>
                    <li class="nav-item mt-2">
                        <a href="../succession/succession.php" class="nav-link  align-middle px-0 text-start">
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
            </div>
        </div>

        <!-- Main Content -->
        <div class="col main-content py-4">
            <div class="row">
                <div class="col">
              
                <div class="card" style="height: 700px; overflow: hidden;">

                

  <div class="card-content" style="height: 100%;">
    <div class="card-body" style="height: 100%; display: flex; flex-direction: column;">
      <div class="container" style="flex: 1; overflow: hidden;">


      <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
  <li class="nav-item">
    <button class="nav-link  active" id="pillsTI-tab" data-bs-toggle="pill" data-bs-target="#pillsCFM" aria-controls="pillsTPM" aria-selected="true">Competency Framework & Mapping</button>
  </li>

  <li class="nav-item">
    <button class="nav-link for-pills" id="pillsCPM-tab" data-bs-toggle="pill" data-bs-target="#pillsCAE" aria-controls="pillsCPM" aria-selected="false">Competency Assessment & Evaluation

    </button>
  </li>

  <li class="nav-item">
    <button class="nav-link for-pills" id="pillsSPD-tab" data-bs-toggle="pill" data-bs-target="#pillsECP" aria-controls="pillsEnrollment" aria-selected="false">Employee Competency Profile
    </button>
  </li>

  <li class="nav-item">
    <button class="nav-link for-pills" id="pillsSI-tab" data-bs-toggle="pill" data-bs-target="#pillsSGATR" aria-controls="pillsFaculty" aria-selected="false">Skill Gap Analysis Training Recommendation
    </button>
  </li>
  <li class="nav-item">
    <button class="nav-link for-pills" id="pillsSI-tab" data-bs-toggle="pill" data-bs-target="#pillsCDP" aria-controls="pillsFaculty" aria-selected="false">Competency Development Plans
    </button>
  </li>
  
</ul>

<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pillsCFM" role="tabpanel" >

  
        <div style="overflow-y: auto; height: 100%;">
        <div class="container mt-5">
  <h2 class="text-center mb-4">Competency Framework & Mapping</h2>

  <!-- Filter Options -->
  <div class="row mb-3">
    <div class="col-md-4">
      <input type="text" class="form-control" placeholder="Search Competency Name">
    </div>
    <div class="col-md-3">
      <select class="form-select">
        <option selected>Filter by Role</option>
        <option>HR Manager</option>
        <option>IT Support</option>
        <option>Nurse</option>
        <!-- more roles -->
      </select>
    </div>
    <div class="col-md-3">
      <select class="form-select">
        <option selected>Filter by Department</option>
        <option>HR</option>
        <option>IT</option>
        <option>Medical</option>
        <!-- more depts -->
      </select>
    </div>
    <div class="col-md-2 text-end">
      <button class="btn btn-primary">Add Competency</button>
    </div>
  </div>

  <!-- Competency Table -->
  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>Competency ID</th>
        <th>Name</th>
        <th>Role</th>
        <th>Department</th>
        <th>Compliance</th>
        <th>Last Updated</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>CMP001</td>
        <td>Conflict Resolution</td>
        <td>HR Manager</td>
        <td>HR</td>
        <td>Yes</td>
        <td>2025-04-15</td>
        <td>
          <button class="btn btn-sm btn-primary">Edit</button>
          <button class="btn btn-sm btn-danger">Delete</button>
        </td>
      </tr>
      <!-- More rows here -->
    </tbody>
  </table>
</div>

        </div>
      </div>


      <div class="tab-pane fade" id="pillsCAE" role="tabpanel"> 
        <div style="overflow-y: auto; height: 100%;">
          <div class="row">
          <div class="container mt-5">
  <h2 class="text-center mb-4">Competency Assessment & Evaluation</h2>

  <!-- Filters -->
  <div class="row mb-3">
    <div class="col-md-4">
      <input type="text" class="form-control" placeholder="Search Employee Name">
    </div>
    <div class="col-md-3">
      <select class="form-select">
        <option selected>Filter by Assessment Type</option>
        <option>Self</option>
        <option>Manager</option>
        <option>360 Review</option>
      </select>
    </div>
    <div class="col-md-3">
      <select class="form-select">
        <option selected>Filter by Competency</option>
        <option>Leadership</option>
        <option>Technical Skills</option>
        <option>Teamwork</option>
        <!-- Populate dynamically -->
      </select>
    </div>
    <div class="col-md-2 text-end">
      <button class="btn btn-primary">Add Assessment</button>
    </div>
  </div>

  <!-- Assessment Table -->
  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>Assessment ID</th>
        <th>Employee Name</th>
        <th>Assessment Type</th>
        <th>Score</th>
        <th>Date</th>
        <th>Competency</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>A001</td>
        <td>Jane Smith</td>
        <td>Manager</td>
        <td>85%</td>
        <td>2025-05-01</td>
        <td>Leadership</td>
        <td>
          <button class="btn btn-sm btn-primary">Edit</button>
          <button class="btn btn-sm btn-danger">Delete</button>
        </td>
      </tr>
      <!-- More rows here -->
    </tbody>
  </table>
</div>

        </div></div></div>

        <div class="tab-pane fade" id="pillsECP" role="tabpanel"> 

        
        <br>
        <div style="overflow-y: auto; height: 100%;">
        <div class="row">
        <div class="container mt-5">
  <h2 class="text-center mb-4">Employee Competency Profiles</h2>

  <!-- Filter/Search Bar -->
  <div class="row mb-3">
    <div class="col-md-4">
      <input type="text" class="form-control" placeholder="Search by Employee ID or Name">
    </div>
    <div class="col-md-3">
      <select class="form-select">
        <option selected>Filter by Competency</option>
        <option>Leadership</option>
        <option>Technical Writing</option>
        <option>Team Collaboration</option>
        <!-- Dynamically populate -->
      </select>
    </div>
    <div class="col-md-3">
      <select class="form-select">
        <option selected>Filter by Certification Status</option>
        <option>Certified</option>
        <option>In Progress</option>
        <option>Not Certified</option>
      </select>
    </div>
    <div class="col-md-2 text-end">
      <button class="btn btn-primary">Add Profile</button>
    </div>
  </div>

  <!-- Competency Profile Table -->
  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>Profile ID</th>
        <th>Employee ID</th>
        <th>Competency</th>
        <th>Proficiency</th>
        <th>Certification Status</th>
        <th>Last Updated</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>CP001</td>
        <td>EMP1234</td>
        <td>Leadership</td>
        <td>Advanced</td>
        <td>Certified</td>
        <td>2025-05-03</td>
        <td>
          <button class="btn btn-sm btn-primary">Edit</button>
          <button class="btn btn-sm btn-danger">Delete</button>
        </td>
      </tr>
      <!-- Additional rows -->
    </tbody>
  </table>
</div>

</div>

        </div>
        </div>
       

 <div class="tab-pane fade" id="pillsSGATR" role="tabpanel"> 
          
        <br>
        <div style="overflow-y: auto; height: 100%;">
        <div class="container mt-5">
  <h2 class="text-center mb-4">Skill-Gap Analysis & Training Recommendation</h2>

  <!-- Filter & Search Controls -->
  <div class="row mb-4">
    <div class="col-md-4">
      <input type="text" class="form-control" placeholder="Search by Employee ID or Name">
    </div>
    <div class="col-md-3">
      <select class="form-select">
        <option selected>Filter by Gap Level</option>
        <option>Low</option>
        <option>Medium</option>
        <option>High</option>
      </select>
    </div>
    <div class="col-md-3">
      <input type="date" class="form-control" placeholder="Training Deadline">
    </div>
    <div class="col-md-2 text-end">
      <button class="btn btn-primary">Add Gap</button>
    </div>
  </div>

  <!-- Gap Table -->
  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>Gap ID</th>
        <th>Employee ID</th>
        <th>Competency</th>
        <th>Gap Level</th>
        <th>Recommended Training</th>
        <th>Training Deadline</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>GAP001</td>
        <td>EMP1023</td>
        <td>Technical Skills</td>
        <td>High</td>
        <td>Advanced Java Workshop</td>
        <td>2025-06-15</td>
        <td>
          <button class="btn btn-sm btn-primary">Edit</button>
          <button class="btn btn-sm btn-danger">Delete</button>
        </td>
      </tr>
      <!-- More rows dynamically populated -->
    </tbody>
  </table>
</div>


        </div>
        
        </div> </div>

        <div class="tab-pane fade" id="pillsCDP" role="tabpanel"> 
          
          <br>
          <div style="overflow-y: auto; height: 100%;">
        <div class="container mt-5">
  <h2 class="text-center mb-4">Competency Development Plans</h2>

  <!-- Filters and Search -->
  <div class="row mb-3">
    <div class="col-md-5">
      <input type="text" class="form-control" placeholder="Search by Employee ID or Goal">
    </div>
    <div class="col-md-4">
      <select class="form-select">
        <option selected>Filter by Progress Status</option>
        <option>Not Started</option>
        <option>In Progress</option>
        <option>Completed</option>
      </select>
    </div>
    <div class="col-md-3 text-end">
      <button class="btn btn-success">Add Development Plan</button>
    </div>
  </div>

  <!-- Development Plan Table -->
  <table class="table table-striped table-hover">
    <thead class="table-dark">
      <tr>
        <th>Plan ID</th>
        <th>Employee ID</th>
        <th>Goal Description</th>
        <th>Assigned Training</th>
        <th>Milestone Dates</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>PLAN1001</td>
        <td>EMP204</td>
        <td>Improve client presentation skills</td>
        <td>Presentation Mastery Course</td>
        <td>2025-06-01, 2025-07-15</td>
        <td>In Progress</td>
        <td>
          <button class="btn btn-sm btn-primary">Edit</button>
          <button class="btn btn-sm btn-danger">Delete</button>
        </td>
      </tr>
      <!-- More rows can be dynamically rendered -->
    </tbody>
  </table>
</div>

  
          </div>
          
          </div> </div>

        <div class="tab-pane fade" id="pillsCDP" role="tabpanel"> 
          
         
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

    </script>


</body>
</html>