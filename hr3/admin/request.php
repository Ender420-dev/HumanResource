<?php
session_start();
ob_start();

$title = "Requests Management";
include_once 'admin.php';
?>


<div class="p-2 gap-3">
    <div class="d-flex col">
        <h6 class="text-muted pe-none mb-0">
            <a class="text-decoration-none text-muted" href="">Home</a> >
            <a class="text-decoration-none text-muted" href="">Shift Schedule</a> >
            <a class="text-decoration-none text-muted" href="">Requests Management</a>
        </h6>
    </div>
    <hr>
    <div class="nav col-12 d-flex justify-content-around">
        <?php include('nav/shift schedule/nav.php') ?>
    </div>
    <hr>
    <div class="container-fluid shadow-lg col p-4">
        <div class="col d-flex flex-column border border-2 border rounded-3 p-4">
            <h3 class="text-center">Filter & Review Staff Requests</h3>
            <hr>
            <div class="d-flex flex-wrap gap-3">
                <div class="col-md">
                    <label class="form-label">Request Type:</label>
                    <select class="form-select">
                        <option>All Types</option>
                        <option>Leave</option>
                        <option>Shift Swap</option>
                        <option>Time Off</option>
                        <option>Availability</option>
                        <option>Schedule Change</option>
                    </select>
                </div>
                <div class="col-md">
                    <label class="form-label">Department:</label>
                    <select class="form-select">
                        <option>All Departments</option>
                        <option>ER</option>
                        <option>ICU</option>
                        <option>Cardiology</option>
                        <option>Admissions</option>
                    </select>
                </div>
                <div class="col-md">
                    <label class="form-label">Staff Name:</label>
                    <input class="form-control" type="text" placeholder="All Staff">
                </div>
                <div class="col-md">
                    <label class="form-label">Status:</label>
                    <select class="form-select">
                        <option>Pending</option>
                        <option>All</option>
                        <option>Approved</option>
                        <option>Rejected</option>
                    </select>
                </div>
                <div class="col-md">
                    <label class="form-label">Date Range:</label>
                    <select class="form-select">
                        <option>Last 30 Days</option>
                        <option>Today</option>
                        <option>This Week</option>
                        <option>This Month</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button class="btn btn-primary">Apply Filters</button>
                <button class="btn btn-secondary">Clear Filters</button>
            </div>
        </div>

        <br>
        <div class="col d-flex flex-column border border-2 border rounded-3 p-4">
            <h3 class="text-center">Pending Requests Requiring Your Action (5)</h3>
            <hr>
            <table class="table table-striped table-hover border text-center">
                <thead>
                    <tr>
                        <th>Req. ID</th>
                        <th>Request Type</th>
                        <th>Requester</th>
                        <th>Department</th>
                        <th>Details</th>
                        <th>Submitted On</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $pending = [
                        ['id'=>'REQ001','type'=>'Shift Swap','name'=>'Nurse Emily','dept'=>'ER','details'=>'Swap Night 21/05 w/ John','date'=>'2025-05-19','status'=>'Pending'],
                        ['id'=>'REQ002','type'=>'Leave (Vacation)','name'=>'Dr. Lim','dept'=>'Cardiology','details'=>'2025-07-01 to 07-05','date'=>'2025-05-18','status'=>'Pending'],
                        ['id'=>'REQ003','type'=>'Time Off','name'=>'Tech Chris','dept'=>'Lab','details'=>'Half-day 2025-05-23 PM','date'=>'2025-05-19','status'=>'Pending'],
                        ['id'=>'REQ004','type'=>'Availability','name'=>'Nurse Karen','dept'=>'Pediatrics','details'=>'Prefers Day Shifts only','date'=>'2025-05-17','status'=>'Pending'],
                        ['id'=>'REQ005','type'=>'Schedule Change','name'=>'Admin Sarah','dept'=>'Admissions','details'=>'Change 5/22 to earlier','date'=>'2025-05-19','status'=>'Pending'],
                    ];
                    foreach ($pending as $p): ?>
                        <tr>
                            <td><?= $p['id'] ?></td>
                            <td><?= $p['type'] ?></td>
                            <td><?= $p['name'] ?></td>
                            <td><?= $p['dept'] ?></td>
                            <td><?= $p['details'] ?></td>
                            <td><?= $p['date'] ?></td>
                            <td><?= $p['status'] ?></td>
                            <td class="d-flex justify-content-center gap-2">
                                <button class="btn btn-info btn-sm">Review</button>
                                <button class="btn btn-success btn-sm">Approve</button>
                                <button class="btn btn-danger btn-sm">Reject</button>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <div class="d-flex gap-2">
                <button class="btn btn-success">Approve Selected</button>
                <button class="btn btn-danger">Reject Selected</button>
                <button class="btn btn-secondary">Request Info for Selected</button>
            </div>
        </div>

        <br>
        <div class="col d-flex flex-column border border-2 border rounded-3 p-4">
            <h3 class="text-center">All Recent Requests (Last 7 Days)</h3>
            <hr>
            <table class="table table-striped table-hover border text-center">
                <thead>
                    <tr>
                        <th>Req. ID</th>
                        <th>Request Type</th>
                        <th>Requester</th>
                        <th>Details</th>
                        <th>Submitted On</th>
                        <th>Status</th>
                        <th>Approver</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $recent = [
                        ['id'=>'REQ006','type'=>'Leave (Sick)','name'=>'Nurse Emily','details'=>'2025-05-16','date'=>'2025-05-16','status'=>'Approved','approver'=>'Admin Alice'],
                        ['id'=>'REQ007','type'=>'Shift Swap','name'=>'Dr. Santos','details'=>'Swap 5/17 w/ Dr. Lee','date'=>'2025-05-15','status'=>'Rejected','approver'=>'Admin Alice'],
                    ];
                    foreach ($recent as $r): ?>
                        <tr>
                            <td><?= $r['id'] ?></td>
                            <td><?= $r['type'] ?></td>
                            <td><?= $r['name'] ?></td>
                            <td><?= $r['details'] ?></td>
                            <td><?= $r['date'] ?></td>
                            <td><?= $r['status'] ?></td>
                            <td><?= $r['approver'] ?></td>
                            <td><button class="btn btn-outline-primary btn-sm">View</button></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <nav class="d-flex justify-content-center">
                <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="#">« Prev</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next »</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>
