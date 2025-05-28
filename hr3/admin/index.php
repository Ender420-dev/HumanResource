<?php
ob_start();
$title='Dashboard';
include_once 'admin.php';
?>

<div class="gap-3 h-100 overflow-y-scroll p-2">
    <div class="d-flex col">
        <h6 class=" text-muted pe-none mb-0"><a class="text-decoration-none text-muted" href="">Home</a> > <a class="text-decoration-none text-muted" href="">Dashboard</a></h6>
    </div>
    <hr>
        <h3 class="text-muted text-center">Welcome Admin</h3>
    <hr>
    <div class="nav col-12 p-3 d-flex justify-content-around">
        <button class="btn btn-primary">Time Attendance</button>
        <button class="btn btn-primary">Time Sheets</button>
        <button class="btn btn-primary">Shift Schedule</button>
        <button class="btn btn-primary">Leave Management</button>
        <button class="btn btn-primary">Claims and Reimbursements</button>
    </div>
    <hr>
    <div class="col-12 p-3">
        <div>
            <h4 class="text-muted">Attendance Tracking</h4>
            <div class="row">
                <div class="col">
                    <table class="table table-striped table-bordered h-25 overflow-y-scroll">
                        <thead>
                            <tr>
                                <th class="text-center">Employee ID</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Time In</th>
                                <th class="text-center">Time Out</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Sample data for attendance tracking
                            $attendance_data = [
                                ['id' => 'E001', 'name' => 'John Doe', 'date' => '2023-10-01', 'time_in' => '08:00 AM', 'time_out' => '05:00 PM', 'status' => 'Present', 'edit' => 'Edit Delete'],
                                ['id' => 'E002', 'name' => 'Jane Smith', 'date' => '2023-10-01', 'time_in' => '08:15 AM', 'time_out' => '05:00 PM', 'status' => 'Late', 'edit' => 'Edit Delete'],
                                ['id' => 'E003', 'name' => 'Alice Johnson', 'date' => '2023-10-01', 'time_in' => '', 'time_out' => '', 'status' => 'Absent', 'edit' => 'Edit Delete'],
                            ];

                            foreach ($attendance_data as $record) {
                                echo "<tr>";
                                echo "<td class=\"text-center\">{$record['id']}</td>";
                                echo "<td class=\"text-center\">{$record['name']}</td>";
                                echo "<td class=\"text-center\">{$record['date']}</td>";
                                echo "<td class=\"text-center\">{$record['time_in']}</td>";
                                echo "<td class=\"text-center\">{$record['time_out']}</td>";
                                echo "<td class=\"text-center\">{$record['status']}</td>";
                                echo "<td class=\"text-center\">{$record['edit']}</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <hr>
        <div>
            <h4 class="text-muted">Time Sheets</h4>
            <div class="row">
                <div class="col">
                    <table class="table table-striped table-bordered h-25 overflow-y-scroll">
                        <thead>
                            <tr>
                                <th class="text-center">Employee ID</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Week Ending</th>
                                <th class="text-center">Total Hours</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Sample data for time sheets
                            $time_sheet_data = [
                                ['id' => 'E001', 'name' => 'John Doe', 'week_ending' => '2023-10-07', 'total_hours' => 40, 'status' => 'Submitted', 'edit' => 'Edit Delete'],
                                ['id' => 'E002', 'name' => 'Jane Smith', 'week_ending' => '2023-10-07', 'total_hours' => 38, 'status' => 'Pending', 'edit' => 'Edit Delete'],
                                ['id' => 'E003', 'name' => 'Alice Johnson', 'week_ending' => '2023-10-07', 'total_hours' => 42, 'status' => 'Approved', 'edit' => 'Edit Delete'],
                            ];

                            foreach ($time_sheet_data as $record) {
                                echo "<tr>";
                                echo "<td class=\"text-center\">{$record['id']}</td>";
                                echo "<td class=\"text-center\">{$record['name']}</td>";
                                echo "<td class=\"text-center\">{$record['week_ending']}</td>";
                                echo "<td class=\"text-center\">{$record['total_hours']}</td>";
                                echo "<td class=\"text-center\">{$record['status']}</td>";
                                echo "<td class=\"text-center\">{$record['edit']}</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <hr>
        <div>
            <h4 class="text-muted">Shift Schedule</h4>
            <div class="row">
                <div class="col">
                    <table class="table table-striped table-bordered h-25 overflow-y-scroll">
                        <thead>
                            <tr>
                                <th class="text-center">Employee ID</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Shift Date</th>
                                <th class="text-center">Shift Time</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Sample data for shift schedule
                            $shift_data = [
                                ['id' => 'E001', 'name' => 'John Doe', 'shift_date' => '2023-10-01', 'shift_time' => '08:00 AM - 05:00 PM', 'status' => 'Scheduled', 'edit' => 'Edit Delete'],
                                ['id' => 'E002', 'name' => 'Jane Smith', 'shift_date' => '2023-10-01', 'shift_time' => '09:00 AM - 06:00 PM', 'status' => 'Scheduled', 'edit' => 'Edit Delete'],
                                ['id' => 'E003', 'name' => 'Alice Johnson', 'shift_date' => '2023-10-01', 'shift_time' => '', 'status' => 'Not Scheduled', 'edit' => 'Edit Delete'],
                            ];

                            foreach ($shift_data as $record) {
                                echo "<tr>";
                                echo "<td class=\"text-center\">{$record['id']}</td>";
                                echo "<td class=\"text-center\">{$record['name']}</td>";
                                echo "<td class=\"text-center\">{$record['shift_date']}</td>";
                                echo "<td class=\"text-center\">{$record['shift_time']}</td>";
                                echo "<td class=\"text-center\">{$record['status']}</td>";
                                echo "<td class=\"text-center\">{$record['edit']}</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <hr>
        <div>
            <h4 class="text-muted">Leave Management</h4>
            <div class="row">
                <div class="col">
                    <table class="table table-striped table-bordered h-25 overflow-y-scroll">
                        <thead>
                            <tr>
                                <th class="text-center">Employee ID</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Leave Type</th>
                                <th class="text-center">Start Date</th>
                                <th class="text-center">End Date</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Sample data for leave management
                            $leave_data = [
                                ['id' => 'E001', 'name' => 'John Doe', 'leave_type' => 'Sick Leave', 'start_date' => '2023-10-01', 'end_date' => '2023-10-03', 'status' => 'Approved', 'edit' => 'Edit Delete'],
                                ['id' => 'E002', 'name' => 'Jane Smith', 'leave_type' => 'Vacation Leave', 'start_date' => '2023-10-05', 'end_date' => '2023-10-10', 'status' => 'Pending', 'edit' => 'Edit Delete'],
                                ['id' => 'E003', 'name' => 'Alice Johnson', 'leave_type' => '', 'start_date' => '', 'end_date' => '', 'status' => '', 'edit' => 'Edit Delete'],
                            ];

                            foreach ($leave_data as $record) {
                                echo "<tr>";
                                echo "<td class=\"text-center\">{$record['id']}</td>";
                                echo "<td class=\"text-center\">{$record['name']}</td>";
                                echo "<td class=\"text-center\">{$record['leave_type']}</td>";
                                echo "<td class=\"text-center\">{$record['start_date']}</td>";
                                echo "<td class=\"text-center\">{$record['end_date']}</td>";
                                echo "<td class=\"text-center\">{$record['status']}</td>";
                                echo "<td class=\"text-center\">{$record['edit']}</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <hr>
        <div>
            <h4 class="text-muted">Claims and Reimbursements</h4>
            <div class="row">
                <div class="col">
                    <table class="table table-striped table-bordered h-25 overflow-y-scroll">
                        <thead>
                            <tr>
                                <th class="text-center">Employee ID</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Claim Type</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Sample data for claims and reimbursements
                            $claim_data = [
                                ['id' => 'E001', 'name' => 'John Doe', 'claim_type' => 'Medical', 'amount' => '$100', 'status' => 'Approved', 'edit' => 'Edit Delete'],
                                ['id' => 'E002', 'name' => 'Jane Smith', 'claim_type' => 'Travel', 'amount' => '$200', 'status' => 'Pending', 'edit' => 'Edit Delete'],
                                ['id' => 'E003', 'name' => 'Alice Johnson', 'claim_type' => '', 'amount' => '', 'status' => '', 'edit' => 'Edit Delete'],
                            ];

                            foreach ($claim_data as $record) {
                                echo "<tr>";
                                echo "<td class=\"text-center\">{$record['id']}</td>";
                                echo "<td class=\"text-center\">{$record['name']}</td>";
                                echo "<td class=\"text-center\">{$record['claim_type']}</td>";
                                echo "<td class=\"text-center\">{$record['amount']}</td>";
                                echo "<td class=\"text-center\">{$record['status']}</td>";
                                echo "<td class=\"text-center\">{$record['edit']}</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
