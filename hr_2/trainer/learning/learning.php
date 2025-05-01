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
    <link rel="stylesheet" href="../tm.css?v=2.4">
</head>
<body>
    <div class="container-fluid">
        <div class="row  flex-nowrap">
            <div class=" col-auto col-md-2 col-xl-2 px-sm-1 px-0 min-vh-100 bg-side ">
                <div class=" d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100" style=" background: #4A628A ;">
                    <a href="#!" class="d-flex text-decoration-none align-items-center mb-md-0 text-white text-decoration-none">
                        <!-- Group  -->
                        <span class="fs-5 d-none  d-sm-inline" style=" color: white ;"> <div class="media align-items-center">
                        <img src="logo.png" width="100"  class="mr-3" alt="">
                        </div>  <h3>Hospital Management</h3></span>
                </a>
                <ul class="nav nav-link flex-column mb-sm-auto mb-0 align-items-center align-items-sm-center" id="menu" style=" color: white ;">

                <!-- Modules -->
                <li class="nav-item mt-2">
                        <a href="../training_management/training_management.php" class="nav-link  align-middle px-0  text-start" style="">
                        <i class="fa-solid fa-chalkboard-user"></i>
                            <span class="ms-1 d-none d-sm-inline text-start">Training Management</span>
                        </a>
                    </li>
                    <li class="nav-item mt-2">
                        <a href="../learning/learning.php" class="nav-link active align-middle px-0 text-start">
                        <i class="fa-solid fa-book-medical"></i>
                            <span class="ms-1 d-none d-sm-inline">Learning Management</span>
                        </a>
                    </li>
                </ul>
            </div>
          
        </div>  
        <div class="main-content">
            <br>
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="container">
                        <h3 class="white-text text-center">No. of Trainees</h3>
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