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
    <link rel="stylesheet" href="../mdb/css/mdb.min.css">
    <link rel="icon" href="../logo.png">   
    <link rel="stylesheet" href="../../tm.css?v=2.5">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel='stylesheet' />
    <style>
        .collapse-animated {
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            opacity: 1;
            max-height: 500px;
        }
        .collapse-animated:not(.show) {
            opacity: 0;
            max-height: 0 !important;
        }
        .collapse-animated.show {
            opacity: 1;
            max-height: 500px;
        }
    </style>
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
                        <a href="#trainingSubmodules" data-bs-toggle="collapse" aria-controls="trainingSubmodules" aria-expanded="false" class="nav-link align-middle px-0 text-start">
                            <i class="fa-solid fa-chalkboard-user"></i>
                            <span class="ms-1 d-none d-sm-inline">Training Management</span>
                        </a>
                        <ul class="nav flex-column ms-4 collapse collapse-animated" id="trainingSubmodules">
                            <li class="nav-item"><a href="./training_program.php" class="nav-link px-0 text-start">Training Program Management</a></li>
                            <li class="nav-item"><a href="./training_calendar.php" class="nav-link px-0 text-start">Training Calendar and Scheduling</a></li>

                        </ul>
                    </li>
                    <li class="nav-item mt-2">
                        <a href="#learningSubmodules" data-bs-toggle="collapse" aria-controls="learningSubmodules" aria-expanded="false" class="nav-link align-middle px-0 text-start">
                            <i class="fa-solid fa-book-medical"></i>
                            <span class="ms-1 d-none d-sm-inline">Learning Management</span>
                        </a>
                        <ul class="nav flex-column ms-4 collapse collapse-animated" id="learningSubmodules">
                            <li class="nav-item"><a href="./course_management.php" class="nav-link px-0 text-start">Course Management</a></li>
                            <li class="nav-item"><a href="./learning_content.php" class="nav-link px-0 text-start">Learning Content and Management</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col main-content py-4">
            <div class="row">
                <!-- Schedule Table Column (now on the left, larger) -->
                <div class="col-md-8 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-side text-white">
                            <h5 class="mb-0">Training Schedule</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="height:calc(100vh - 220px); min-height:300px;">
                                <table class="table table-bordered table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Program Name</th>
                                            <th>Trainer</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Example row, replace with PHP loop for dynamic data -->
                                        <tr>
                                            <td>Leadership 101</td>
                                            <td>John Doe</td>
                                            <td>2025-06-01</td>
                                            <td>2025-06-05</td>
                                        </tr>
                                        <!-- End example row -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Calendar Column (now on the right, smaller) -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-side text-white">
                            <h5 class="mb-0">Calendar</h5>
                        </div>
                        <div class="card-body">
                            <!-- FullCalendar.io widget integration -->
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <nav class="navbar navbar-blue" style="height:70px;">
<div class="container">
    <a href="#!" class="navbar-brand" data-bs-toggle="offcanvas" aria-controls="staticBackdrop" data-bs-target="#sideBarNav" >
        <img src="logo.png" style="margin-top:-25px;" width="90" height="90" alt=""> <span class="white-text" style=" ">HR 2</span></a></div>
</nav> -->


<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script type="text/javascript" src="../mdb/js/mdb.es.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    if(calendarEl) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 400,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: [
                // Example event, replace with dynamic events if needed
                {
                    title: 'Leadership 101',
                    start: '2025-06-01',
                    end: '2025-06-05',
                    color: '#1a237e'
                }
            ]
        });
        calendar.render();
    }
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