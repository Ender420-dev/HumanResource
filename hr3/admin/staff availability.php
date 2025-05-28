<?php
session_start();
ob_start();

$title = "Staff Availability";
include_once 'admin.php';

?>

<div class="p-2 gap-3">
    <div class="d-flex col">
        <h6 class=" text-muted pe-none mb-0"><a class="text-decoration-none text-muted" href="">Home</a> > <a class="text-decoration-none text-muted" href="">Shift Schedule</a> > <a class="text-decoration-none text-muted" href="">Staff Availability</a></h6>
    </div>
    <hr>
    <div class="nav col-12 d-flex justify-content-around">
<?php include('nav/shift schedule/nav.php') ?>
    </div>
    <hr>
    <div class="container-fluid shadow-lg col p-4">
        <div class="col d-flex flex-column p-4">
            <div class="col d-flex flex-column border border-2 border rounded-3 p-4">
                <h3 class="align-items-center text-center">Staff Availability - Filters & Period </h3>
                <hr>
                <div class="d-flex gap-3">
                    <div class="d-flex flex-column gap-3">
                        <h4>Department / Ward:</h4>
                        <h4>Week:</h4>
                        <h4>Staff Name:</h4>
                    </div>
                    <div>
                        <h4>
                            <select name="" id="" class="form-select">
                                <option value="">Emergency Room</option>
                                <option value="">Cashier</option>
                                <option value="">ICU</option>
                                <option value="">Marital</option>
                            </select>
                        </h4>
                        <h4 class="d-flex">
                            <input class="form-control" type="date" name="" id=""> - <input class="form-control" type="date" name="" id="">
                        </h4>
                        <h4>
                            <select name="" id="" class="form-select">
                                <option value="">sadsad</option>
                                <option value="">dadsad</option>
                                <option value="">dsadsada</option>
                                <option value="">dsadsad</option>
                                <option value="">dasdsad</option>
                            </select>
                        </h4>
                    </div>
                </div>
                <br>
                <div>
                    <button class="btn btn-primary">Apply Filters</button>
                    <button class="btn btn-primary">Clear Filters</button>
                </div>
            </div>
            <br>
            <div class="col d-flex flex-column border border-2 border rounded-3 p-4">
                <h3 class="align-items-center text-center">Weekly Staff Availability Overview </h3>
                <hr>
                <div>
                    <table class="table table-striped table-hover border text-center">
                        <thead>
                            <tr>
                                <th>Staff Name</th>
                                <th>Mon </th>
                                <th>Tue</th>
                                <th>Wed</th>
                                <th>Thu</th>
                                <th>Fri</th>
                                <th>Sat</th>
                                <th>Sun</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $weekly=[
                                    [ 'name'=>'sadsads','mon'=>'Available(All)','tue'=>'Available(All)','wed'=>'Available','thu'=>'Available','fri'=>'Available','sat'=>'Available','sun'=>'Available',],
                                    [ 'name'=>'sadsads','mon'=>'Available(All)','tue'=>'Available(All)','wed'=>'Available','thu'=>'Available','fri'=>'Available','sat'=>'Available','sun'=>'Available',],
                                    [ 'name'=>'sadsads','mon'=>'Available(All)','tue'=>'Available(All)','wed'=>'Available','thu'=>'Available','fri'=>'Available','sat'=>'Available','sun'=>'Available',],
                                    [ 'name'=>'sadsads','mon'=>'Available(All)','tue'=>'Available(All)','wed'=>'Available','thu'=>'Available','fri'=>'Available','sat'=>'Available','sun'=>'Available',],
                                    [ 'name'=>'sadsads','mon'=>'Available(All)','tue'=>'Available(All)','wed'=>'Available','thu'=>'Available','fri'=>'Available','sat'=>'Available','sun'=>'Available',],
                                    [ 'name'=>'sadsads','mon'=>'Available(All)','tue'=>'Available(All)','wed'=>'Available','thu'=>'Available','fri'=>'Available','sat'=>'Available','sun'=>'Available',],
                                    [ 'name'=>'sadsads','mon'=>'Available(All)','tue'=>'Available(All)','wed'=>'Available','thu'=>'Available','fri'=>'Available','sat'=>'Available','sun'=>'Available',],
                                ];
                                foreach($weekly as $week):
                            ?>
                            <tr>
                                <td><?= $week['name'] ?></td>
                                <td><?= $week['mon'] ?></td>
                                <td><?= $week['tue'] ?></td>
                                <td><?= $week['wed'] ?></td>
                                <td><?= $week['thu'] ?></td>
                                <td><?= $week['fri'] ?></td>
                                <td><?= $week['sat'] ?></td>
                                <td><?= $week['sun'] ?></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                    <div class="d-flex gap-3">
                        <div>
                            <h4>Legend: </h4>
                        </div>
                        <div>
                            <h4>Available (All) =</h4>
                            <h4>Available (AM/PM/Ngt) =</h4>
                            <h4>Preffered (Day/Ngt) =</h4>
                            <h4>Unavailable (L) =</h4>
                            <h4>Unavailable (T) =</h4>
                            <h4>Unavailable (C) =</h4>
                        </div>
                        <div>
                            <h4>All day available</h4>
                            <h4>Specific part of day</h4>
                            <h4>Prefers certain shift types</h4>
                            <h4>On Leave</h4>
                            <h4>Training</h4>
                            <h4>Personal Commitment</h4>
                        </div>
                    </div>
                </div>
                <br>
                <div class="col d-flex flex-column border border-2 border rounded-3 p-4">
                <h3 class="align-items-center text-center">Pending Availability Requests / Preferences </h3>
                <hr>
                <div>
                    <table class="table table-striped table-hover border text-center">
                        <thead>
                            <tr>
                                <th>Staff Name</th>
                                <th>Type</th>
                                <th>Date / Period</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $weekly=[
                                    [ 'name'=>'sadsads','type'=>'Available(All)','period'=>'Available(All)','status'=>'Available'],

                                ];
                                foreach($weekly as $week):
                            ?>
                            <tr>
                                <td><?= $week['name'] ?></td>
                                <td><?= $week['type'] ?></td>
                                <td><?= $week['period'] ?></td>
                                <td><?= $week['status'] ?></td>
                                <td class="col-1">
                                    <button class="btn btn-primary">Review</button>
                                </td>

                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                    <div class="d-flex gap-3">
                        <button class="btn btn-primary">View All Requests</button>
                        <button class="btn btn-primary">Add Manual Unavailability</button>
                    </div>
                </div>