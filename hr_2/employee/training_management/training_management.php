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
    <link rel="stylesheet" href="../../tm.css?v=2.5">
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
                        <a href="../training_management/training_management.php" class="nav-link active align-middle px-0 text-start">
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
                    <div class="card" style="height: 700px; overflow: hidden;">
                        <div class="container">
                        <div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    

    <!-- Main Content -->
    <div class="col-md-12 p-4">
      <h2>Welcome, Employee</h2>

      <!-- Summary Cards -->
      <div class="row my-4">
        <div class="col-md-3">
          <div class="card text-center bg-success text-white">
            <div class="card-body">
              <h5>Completed</h5>
              <h3>8</h3>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-center bg-info text-white">
            <div class="card-body">
              <h5>Ongoing</h5>
              <h3>2</h3>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-center bg-warning text-dark">
            <div class="card-body">
              <h5>Upcoming</h5>
              <h3>3</h3>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-center bg-primary text-white">
            <div class="card-body">
              <h5>Total Hours</h5>
              <h3>45h</h3>
            </div>
          </div>
        </div>
      </div>

      <!-- My Courses -->
      <h4>My Courses</h4>
      <div class="row">
        <div class="col-md-6">
          <div class="course-card">
            <h5>Leadership Essentials</h5>
            <p>Self-paced | Due: May 31, 2025</p>
            <div class="progress mb-2">
              <div class="progress-bar bg-success" style="width: 75%;">75%</div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="course-card">
            <h5>Advanced Excel Training</h5>
            <p>Instructor-led | Next session: May 20, 2025</p>
            <div class="progress mb-2">
              <div class="progress-bar bg-info" style="width: 40%;">40%</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Certificates -->
      <h4 class="mt-4">My Certificates</h4>
      <ul class="list-group">
        <li class="list-group-item d-flex justify-content-between align-items-center">
          Data Privacy Training
          <a href="#" class="btn btn-sm btn-outline-primary">Download</a>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          Fire Safety Awareness
          <a href="#" class="btn btn-sm btn-outline-primary">Download</a>
        </li>
      </ul>

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