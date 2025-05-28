<?php
session_start();
ob_start();

$title = "Manage Shift Templates";
include_once 'admin.php';

?>
<style>
    .shift template{
        color: var(--bs-dark);
    }
</style>
<div class="p-2 gap-3">
    <div class="d-flex col">
        <h6 class="text-muted pe-none mb-0">
            <a class="text-decoration-none text-muted" href="">Home</a> > 
            <a class="text-decoration-none text-muted" href="">Shift Schedule</a> > 
            <a class="text-decoration-none text-muted" href="">Manage Shift Templates</a>
        </h6>
    </div>
    <hr>
    <div class="nav col-12 d-flex justify-content-around">
        <?php include('nav/shift schedule/nav.php') ?>
    </div>
    <hr>
    <div class="container-fluid shadow-lg col p-4">
        <div class="col d-flex flex-column p-4 border border-2 rounded-3">
            <h3 class="text-center">Manage Shift Templates</h3>
            <hr>
            <table class="table table-striped table-hover border text-center">
                <thead>
                    <tr>
                        <th>Template Name</th>
                        <th>Department(s)</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Duration</th>
                        <th>Default Staffing</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $templates = [
                        ['name'=>'Day Shift - General','departments'=>'Medical Wards, ER','start'=>'07:00','end'=>'15:00','duration'=>'8 hours','staffing'=>'Nurse (2), Aide (1)'],
                        ['name'=>'Night Shift - General','departments'=>'Medical Wards, ER, ICU','start'=>'23:00','end'=>'07:00','duration'=>'8 hours','staffing'=>'Nurse (3), Aide (2)'],
                        ['name'=>'Mid Shift - OPD','departments'=>'Outpatient Departments','start'=>'11:00','end'=>'19:00','duration'=>'8 hours','staffing'=>'Nurse (1)'],
                        ['name'=>'12 Hour Day - ICU','departments'=>'ICU','start'=>'07:00','end'=>'19:00','duration'=>'12 hours','staffing'=>'Nurse (4), Aide (2)'],
                        ['name'=>'12 Hour Night - ICU','departments'=>'ICU','start'=>'19:00','end'=>'07:00','duration'=>'12 hours','staffing'=>'Nurse (4), Aide (2)'],
                        ['name'=>'Admin - Regular Hours','departments'=>'Administration','start'=>'08:00','end'=>'17:00','duration'=>'9 hours','staffing'=>'Clerk (1)'],
                    ];
                    foreach($templates as $t):
                    ?>
                    <tr>
                        <td><?= $t['name'] ?></td>
                        <td><?= $t['departments'] ?></td>
                        <td><?= $t['start'] ?></td>
                        <td><?= $t['end'] ?></td>
                        <td><?= $t['duration'] ?></td>
                        <td><?= $t['staffing'] ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm">Edit</button>
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <div class="d-flex gap-2">
                <button class="btn btn-success">Add New Template</button>
                <button class="btn btn-secondary">Import Templates</button>
                <button class="btn btn-secondary">Export Templates</button>
            </div>
        </div>
        <br>
        <div class="col d-flex flex-column border border-2 rounded-3 p-4">
            <h3 class="text-center">Template Details: [Select to View/Edit]</h3>
            <hr>
            <form>
                <div class="mb-3">
                    <label class="form-label">Template Name</label>
                    <input type="text" class="form-control" placeholder="Day Shift - General">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control">Standard 8-hour day shift for general areas.</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Applicable Departments</label>
                    <select class="form-select" multiple>
                        <option>Medical Wards</option>
                        <option>ER</option>
                        <option>ICU</option>
                        <option>Outpatient Departments</option>
                    </select>
                </div>
                <div class="mb-3 d-flex gap-3">
                    <div class="flex-fill">
                        <label class="form-label">Start Time</label>
                        <input type="time" class="form-control" value="07:00">
                    </div>
                    <div class="flex-fill">
                        <label class="form-label">End Time</label>
                        <input type="time" class="form-control" value="15:00">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Default Staffing</label>
                    <div class="d-flex gap-2 align-items-center">
                        <select class="form-select" style="width: 200px;">
                            <option>Nurse</option>
                            <option>Aide</option>
                            <option>Clerk</option>
                        </select>
                        <input type="number" class="form-control" placeholder="Quantity" style="width: 120px;">
                        <button class="btn btn-outline-secondary btn-sm">Add Role</button>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" placeholder="Specific guidelines for this shift."></textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Template</button>
                    <button type="button" class="btn btn-secondary">Save As New Template</button>
                    <button type="reset" class="btn btn-danger">Cancel</button>
                </div>
            </form>
        </div>
        <hr>
    </div>
</div>
