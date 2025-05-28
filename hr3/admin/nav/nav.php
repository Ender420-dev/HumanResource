<?php

?>
<div class="container-fluid max-vh-100">
    <div class="row flex-nowrap position-sticky">
        <div class="center bg-side col-auto col-md-3 col-xl-2 px-sm-2 px-0 min-vh-100">
            <div class="d-flex flex-column align-items-sm-start px-3 pt-2 text-white min-vh-100" style="background: #4A628A;">
                <a href="../admin/" class="d-flex text-decoration-none align-items-center mb-md-0 p-1 text-white">
                    <img src="nav/logo.png" width="65" height="65" alt="">
                    <span class="fs-5 d-none d-sm-inline fw-bold" style="color: white;">HR3</span>
                </a>
                <style>
                    li, li a {
                        border-radius: var(--bs-border-radius-sm) !important;
                    }
                </style>
                <ul class="nav nav-link flex-column mb-sm-auto mb-0 align-items-center align-items-sm-center" id="menu" style="color: white;">
                    <li class="my-2">
                        <a href="attendance tracking.php" class="nav-link align-middle p-2 ">
                            <i class="fs-4 fa fa-clock"></i>
                            <span class="ms-1 d-none d-sm-inline">Attendance Tracking</span>
                        </a>
                    </li>

                    <li class="my-2">
                        <a href="timesheets.php" class="nav-link align-middle p-2 ">
                            <i class="fs-4 fa fa-table"></i>
                            <span class="ms-1 d-none d-sm-inline">Time Sheets</span>
                        </a>
                    </li>
                    <li class="my-2">
                        <a href="shifting schedule.php" class="nav-link align-middle p-2 ">
                            <i class="fs-4 fa fa-calendar-check"></i>
                            <span class="ms-1 d-none d-sm-inline">Shift Schedule</span>
                        </a>
                    </li>

                    <li class="my-2">
                        <a href="leave_request.php" class="nav-link align-middle p-2 ">
                            <i class="fs-4 fas fa-briefcase"></i>
                            <span class="ms-1 d-none d-sm-inline">Leave Management</span>
                        </a>
                    </li>

                    <li class="my-2">
                        <a href="claims & reimbursement.php" class="nav-link align-middle p-2 ">
                            <i class="fs-4 fa fa-dollar-sign"></i>
                            <span class="ms-1 d-none d-sm-inline">Claims & Reimbursements</span>
                        </a>
                    </li>
                </ul>

                <hr>

                <!-- Dropdown User -->
                <div class="dropdown pb-4">
                    <a href="!#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../logo.png" width="30" height="30" alt="profile pic" class="rounded-circle">
                        <span class="d-none d-sm-inline mx-1">
                            <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?>
                        </span>

                    </a>
                    <ul class="dropdown-menu dropdown-bg text-small shadow">
                        <li><a href="#" class="dropdown-item">Setting</a></li>
                        <li><a href="#" class="dropdown-item">Profile</a></li>
                        <li><a href="#" class="dropdown-item">Help & Support</a></li>
                        <li><a href="nav/logout.php" class="dropdown-item">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col">
        <?php include_once 'nav/header.php'; ?>
        <div class="row" style="max-width:82.5vw !important;">