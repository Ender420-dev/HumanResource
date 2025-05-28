<?php
session_start();
ob_start();

$title = "Leave Dashboard";
include_once 'admin.php';
?>
<style>
    .dashboard {
        color: var(--br-dark);
    }
</style>
<div class="p-2 gap-3">
    <div class="d-flex col">
        <h6 class="text-muted pe-none mb-0">
            <a class="text-decoration-none text-muted" href="">Home</a> >
            <a class="text-decoration-none text-muted" href="">Leave Management</a>
        </h6>
    </div>
    <hr>
        <div class="nav col-12 d-flex justify-content-around">
        <?php include('nav/leave management/nav.php') ?>
    </div>
    <hr>

    <div class="container-fluid shadow-lg col p-4">

        <!-- Leave Overview Metrics -->
        <div class="col d-flex flex-column border border-2 rounded-3 p-4 mb-4">
            <h3 class="text-center">Leave Overview - Key Metrics</h3>
            <hr>
            <p><strong>Pending Leave Requests:</strong> 5</p>
            <p><strong>Upcoming Leaves (Next 7 Days):</strong> 12</p>
            <p><strong>Average Leave Days/Employee (YTD):</strong> 7.2 days</p>
            <p><strong>Total Leave Days Taken (YTD):</strong> 540 days</p>
            <div class="mt-3">
                <button class="btn btn-primary">View All Pending Requests</button>
                <button class="btn btn-outline-secondary">View Upcoming Leave Calendar</button>
            </div>
        </div>

        <!-- Leave Requests Requiring Action -->
        <div class="col d-flex flex-column border border-2 rounded-3 p-4 mb-4">
            <h3 class="text-center">Leave Requests Requiring Your Action</h3>
            <hr>
            <table class="table table-striped text-center">
                <thead>
                    <tr>
                        <th>Req. ID</th>
                        <th>Employee</th>
                        <th>Type</th>
                        <th>Dates</th>
                        <th>Total Days</th>
                        <th>Submitted On</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>LR001</td><td>Nurse Emily</td><td>Vacation</td><td>2025-06-01 to 06-05</td><td>5.0</td><td>2025-05-18</td><td>Pending</td><td><button class="btn btn-warning btn-sm">Review</button></td></tr>
                    <tr><td>LR002</td><td>Dr. John</td><td>Sick</td><td>2025-05-20 to 05-20</td><td>1.0</td><td>2025-05-20</td><td>Pending</td><td><button class="btn btn-success btn-sm">Approve</button></td></tr>
                    <tr><td>LR003</td><td>Admin Mark</td><td>Emergency</td><td>2025-05-21 to 05-21</td><td>1.0</td><td>2025-05-21</td><td>Pending</td><td><button class="btn btn-success btn-sm">Approve</button></td></tr>
                </tbody>
            </table>
            <div class="mt-2">
                <button class="btn btn-success">Approve Selected</button>
                <button class="btn btn-danger">Reject Selected</button>
                <button class="btn btn-secondary">View All Requests</button>
            </div>
        </div>

        <!-- Leave Balances Quick Search -->
        <div class="col d-flex flex-column border border-2 rounded-3 p-4">
            <h3 class="text-center">Leave Balances - Quick Search</h3>
            <hr>
            <form class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Employee:</label>
                    <input type="text" class="form-control" value="Nurse Jane Doe">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Leave Type:</label>
                    <select class="form-select">
                        <option>All</option>
                        <option>Vacation</option>
                        <option>Sick</option>
                        <option>Emergency</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <button class="btn btn-primary">Search</button>
                    <button class="btn btn-secondary">View All Balances</button>
                </div>
            </form>
            <div class="border p-3 rounded bg-light">
                <p><strong>Nurse Jane Doe (ID: 12345)</strong></p>
                <p>Vacation Leave: Accrued 15.0, Used 5.0, Remaining 10.0</p>
                <p>Sick Leave: Accrued 10.0, Used 2.0, Remaining 8.0</p>
            </div>
        </div>

    </div>
</div>
