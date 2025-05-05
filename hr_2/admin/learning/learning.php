<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Management</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.2/css/all.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../mdb/css/mdb.min.css">
    <link rel="icon" href="../logo.png">   
    <link rel="stylesheet" href="../../../tm.css?v=2.4">

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
                        <a href="../learning/learning.php" class="nav-link active align-middle px-0 text-start">
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
    <button class="nav-link  active" id="pillsCM-tab" data-bs-toggle="pill" data-bs-target="#pillsCM" aria-controls="pillsCM" aria-selected="true">Course Management</button>
  </li>

  <li class="nav-item">
    <button class="nav-link for-pills" id="pillsLCM-tab" data-bs-toggle="pill" data-bs-target="#pillsLCM" aria-controls="pillsLCM" aria-selected="false">Learning Content Management</button>
  </li>

  <li class="nav-item">
    <button class="nav-link for-pills" id="pillsLPT-tab" data-bs-toggle="pill" data-bs-target="#pillsLPT" aria-controls="pillsEnrollment" aria-selected="false">Learning Progress and Tracking
    </button>
  </li>

  <li class="nav-item">
    <button class="nav-link for-pills" id="pillsAC-tab" data-bs-toggle="pill" data-bs-target="#pillsAC" aria-controls="pillsAC" aria-selected="false">Assessment and Certification
    </button>
  </li>

  
</ul>

<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pillsCM" role="tabpanel" >

  <h3 class="white-text card-title text-center">Course Management</h3>
        <br>
        <div style="overflow-y: auto; height: 100%;">
          <table class="table table-hover table-striped">
            <thead class="thead-primary">
              <tr>
                <th scope="col">Course Title</th>
                <th scope="col">Trainer</th>
                <th scope="col">Start Date</th>
                <th scope="col">End Date</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Employee Training 101</td>
                <td>John Doe</td>
                <td>2025-05-10</td>
                <td>2025-06-10</td>
                <td>Ongoing</td>
                <td>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editTrainingModal">Edit</button>

                  <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#trainerModal">View</button>

                </td>
              </tr>
              <!-- Repeat more rows here -->
            </tbody>
          </table>
        </div>
      </div>
</div>

<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade" id="pillsLCM" role="tabpanel" >

  <h3 class="white-text card-title text-center">Learning Content Management</h3>
        <br>
        <div style="overflow-y: auto; height: 100%;">
          <table class="table table-hover table-striped">
            <thead class="thead-primary">
              <tr>
                <th scope="col">Course Title</th>
                <th scope="col">Trainer</th>
                <th scope="col">Start Date</th>
                <th scope="col">End Date</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Employee Training 101</td>
                <td>John Doe</td>
                <td>2025-05-10</td>
                <td>2025-06-10</td>
                <td>Ongoing</td>
                <td>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editTrainingModal">Edit</button>

                  <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#trainerModal">View</button>

                </td>
              </tr>
              <!-- Repeat more rows here -->
            </tbody>
          </table>
        </div>
      </div>
</div>

<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show " id="pillsLPT" role="tabpanel" >

  <h3 class="white-text card-title text-center">Learning Progress and Tracking</h3>
        <br>
        <div style="overflow-y: auto; height: 100%;">
          <table class="table table-hover table-striped">
            <thead class="thead-primary">
              <tr>
                <th scope="col">Course Title</th>
                <th scope="col">Trainer</th>
                <th scope="col">Start Date</th>
                <th scope="col">End Date</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Employee Training 101</td>
                <td>John Doe</td>
                <td>2025-05-10</td>
                <td>2025-06-10</td>
                <td>Ongoing</td>
                <td>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editTrainingModal">Edit</button>

                  <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#trainerModal">View</button>

                </td>
              </tr>
              <!-- Repeat more rows here -->
            </tbody>
          </table>
        </div>
      </div>
</div>
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show " id="pillsAC" role="tabpanel" >

  <h3 class="white-text card-title text-center">Assessment and Certification</h3>
        <br>
        <div style="overflow-y: auto; height: 100%;">
          <table class="table table-hover table-striped">
            <thead class="thead-primary">
              <tr>
                <th scope="col">Course Title</th>
                <th scope="col">Trainer</th>
                <th scope="col">Start Date</th>
                <th scope="col">End Date</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Employee Training 101</td>
                <td>John Doe</td>
                <td>2025-05-10</td>
                <td>2025-06-10</td>
                <td>Ongoing</td>
                <td>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editTrainingModal">Edit</button>

                  <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#trainerModal">View</button>

                </td>
              </tr>
              <!-- Repeat more rows here -->
            </tbody>
          </table>
        </div>
      </div>
</div>

<div class="modal fade" id="viewCourseModal" tabindex="-1" aria-labelledby="viewCourseLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="viewCourseLabel">Course Overview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <p><strong>Course Title:</strong> Leadership Essentials</p>
        <p><strong>Trainer:</strong> Jane Smith</p>
        <p><strong>Content:</strong> Video Modules, Reading Material, Case Studies</p>
        
        <hr>
        <h6>Enrolled Learners:</h6>
        <ul>
          <li>Maria Gomez - 70% Complete</li>
          <li>Arvin Santos - 40% Complete</li>
          <li>Lea Valdez - 100% Complete (Certified)</li>
        </ul>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<!-- Edit Course Modal -->
<div class="modal fade" id="editCourseModal" tabindex="-1" aria-labelledby="editCourseLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="editCourseLabel">Edit Course Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Course Title</label>
            <input type="text" class="form-control" value="Leadership Essentials">
          </div>
          <div class="mb-3">
            <label class="form-label">Trainer</label>
            <input type="text" class="form-control" value="Jane Smith">
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" rows="3">Introductory leadership skills course...</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select">
              <option selected>Ongoing</option>
              <option>Completed</option>
              <option>Upcoming</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>

    </div>
  </div>
</div>
<!-- Upload Materials Modal -->
<div class="modal fade" id="uploadMaterialModal" tabindex="-1" aria-labelledby="uploadLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="uploadLabel">Upload Learning Materials</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form enctype="multipart/form-data">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Course</label>
            <select class="form-select">
              <option selected>Leadership Essentials</option>
              <option>Communication Skills</option>
              <!-- More courses -->
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Material Title</label>
            <input type="text" class="form-control" placeholder="e.g., Introduction Video">
          </div>
          <div class="mb-3">
            <label class="form-label">Upload File</label>
            <input type="file" class="form-control">
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Upload</button>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- <nav class="navbar navbar-blue" style="height:70px;">
<div class="container">
    <a href="#!" class="navbar-brand" data-bs-toggle="offcanvas" aria-controls="staticBackdrop" data-bs-target="#sideBarNav" >
        <img src="logo.png" style="margin-top:-25px;" width="90" height="90" alt=""> <span class="white-text" style=" ">HR 2</span></a></div>
</nav> -->


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

    </script>


</body>
</html>