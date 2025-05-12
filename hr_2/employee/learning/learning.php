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
   
    <link rel="icon" href="../logo.png">   
    <link rel="stylesheet" href="../../../tm.css?v=2.5">
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
                           <div class="card-body">
                            


                            <div class="row">
    <div class="col-sm-3">
        <div class="card" >
            <div class="card-body">
                <div class="card-header">My Course</div>
                <div class="list-group" id="list-tab" role="tablist">
                    <a class="list-group-item list-group-item-action active" id="list-Course-1-list" data-bs-toggle="list" href="#Course-1" role="tab" aria-controls="Course-1" aria-selected="true">Course 1 - <span id="status">Incomplete</span> </a>
                    <a class="list-group-item list-group-item-action" id="list-Course-2-list" data-bs-toggle="list" href="#Course-2" role="tab" aria-controls="Course-2" aria-selected="false">Course 2 - <span id="status">Incomplete</span></a>
                    <a class="list-group-item list-group-item-action" id="list-Course-3-list" data-bs-toggle="list" href="#Course-3" role="tab" aria-controls="Course-3" aria-selected="false">Course 3 - <span id="status">Not Started</span></a>
                    <a class="list-group-item list-group-item-action" id="list-Course-4-list" data-bs-toggle="list" href="#Course-4" role="tab" aria-controls="Course-4" aria-selected="false">Course 4 - <span id="status">Incomplete</span></a>
                    <a class="list-group-item list-group-item-action" id="list-Course-5-list" data-bs-toggle="list" href="#Course-5" role="tab" aria-controls="Course-5" aria-selected="false">Course 5 - <span id="status">Complete</span></a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-9">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="Course-1" role="tabpanel" aria-labelledby="list-Course-1-list">
                <h3 class="card-title">Course 1</h3>
                

                
               
        <p><strong>Instructor:</strong> Jane Doe</p>
        <p><strong>Progress:</strong> 75%</p>
        <div class="progress mb-3">
          <div class="progress-bar bg-success" role="progressbar" style="width: 75%;">75%</div>
        </div>
        <p><strong>Quiz Score:</strong> 80%</p>
        <button class="btn btn-primary">Continue Course</button>
        <button class="btn btn-secondary">Take Quiz</button>
        <button class="btn btn-success">Download Certificate</button>











            </div>

            <div class="tab-pane fade" id="Course-2" role="tabpanel" aria-labelledby="list-Course-2-list">
                <h3 class="card-title">Course 2</h3>



            </div>
            <div class="tab-pane fade" id="Course-3" role="tabpanel" aria-labelledby="list-Course-3-list">
                <h3 class="card-title">Course 3</h3>

                <h3>Leadership Essentials</h3>
        <p>Start this course to develop leadership skills.</p>
        <button class="btn btn-primary">Start Course</button>
            </div>
            <div class="tab-pane fade" id="Course-4" role="tabpanel" aria-labelledby="list-Course-4-list">
                <h3 class="card-title">Course 4</h3>


            </div>
            <div class="tab-pane fade" id="Course-5" role="tabpanel" aria-labelledby="list-Course-5-list">
                <h3 class="card-title">Course 5</h3>


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