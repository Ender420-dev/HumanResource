<?php
session_start();


require_once("../../../phpcon/conn.php");


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

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../../../tm.css?v=4.6">
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
                        <a href="../competency/competency.php" class="nav-link align-middle px-0 text-start">
                            <i class="fa-solid fa-ranking-star"></i>
                            <span class="ms-1 d-none d-sm-inline">Competency Management</span>
                        </a>
                    </li>
                    <li class="nav-item mt-2">
                        <a href="../succession/succession.php" class="nav-link align-middle px-0 text-start">
                            <i class="fa-solid fa-chart-line"></i>
                            <span class="ms-1 d-none d-sm-inline">Succession Planning</span>
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
              <div class="nav navbar navbar-expand-lg">

              <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
  <li class="nav-item">
    <button class="nav-link  active" id="pillsTPM-tab" data-bs-toggle="pill" data-bs-target="#pillsTPM" aria-controls="pillsTPM" aria-selected="true">Training Program Management</button>
  </li>

  <li class="nav-item">
    <button class="nav-link for-pills" id="pillsCalendar-tab" data-bs-toggle="pill" data-bs-target="#pillsCalendar" aria-controls="pillsCalendar" aria-selected="false">Training Calendar and Scheduling</button>
  </li>

  <li class="nav-item">
    <button class="nav-link for-pills" id="pillsEnrollment-tab" data-bs-toggle="pill" data-bs-target="#pillsEnrollment" aria-controls="pillsEnrollment" aria-selected="false">Trainee Enrollment and Approval
    </button>
  </li>

  <li class="nav-item">
    <button class="nav-link for-pills" id="pillsFaculty-tab" data-bs-toggle="pill" data-bs-target="#pillsFaculty" aria-controls="pillsFaculty" aria-selected="false">Trainer Faculty Management
    </button>
  </li>

  <li class="nav-item">
    <button class="nav-link for-pills" id="pillsRecord-tab" data-bs-toggle="pill" data-bs-target="#pillsRecord" aria-controls="pillsRecord" aria-selected="false">Training Record and Certificate

    </button>
  </li>
</ul>


              </div>
                <div class="card" style="height: 700px; overflow: hidden;">

                

  <div class="card-content" style="height: 100%;">
    <div class="card-body" style="height: 100%; display: flex; flex-direction: column;">
      <div class="container" style="flex: 1; overflow: hidden;">


      

<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pillsTPM" role="tabpanel" >

  <h3 class="white-text card-title text-center">Training Program Management</h3>
  <div class="d-grid gap-2 d-md-flex justify-content-md-end"><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTrainingModal">
  + Add New Training Program
</button>
</div>
        <br>
        <div style="overflow-y: auto; height: 100%;">

          <table class="table table-hover table-striped">
            <thead class=" table-dark">
              <tr>
                <th scope="col">Program ID</th>
                <th scope="col">Program Type</th>
                <th>Program Name</th>

                <th scope="col">Trainer</th>
                <th scope="col">Start Date</th>
                <th scope="col">End Date</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
            <?php
$query = "
SELECT tp.PROGRAM_ID, tp.PROGRAM_TYPE, tp.PROGRAM_NAME, tf.TRAINER_ID, tf.FULLNAME, tp.STATUS, tp.START AS START_DATE, tp.END AS END_DATE
    FROM training_program tp 
    LEFT JOIN trainer_faculty tf ON tp.TRAINER = tf.TRAINER_ID
";
$result=$connection->query($query);
?>
<?php
while ($program=$result->fetch_assoc()):
?>
              <tr>
              <td><?php echo htmlspecialchars($program['PROGRAM_ID']); ?></td>
            <td><?php echo htmlspecialchars($program['PROGRAM_TYPE']); ?></td>
            <td><?php echo htmlspecialchars($program['PROGRAM_NAME']); ?></td>
            <td><?php echo htmlspecialchars($program['FULLNAME']); ?></td>
            <td><?php echo htmlspecialchars($program['START_DATE']); ?></td>
            <td><?php echo htmlspecialchars($program['END_DATE']); ?></td>
            <td><?php echo htmlspecialchars($program['STATUS']); ?></td>
            <td>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editTrainingModal<?php echo $program['PROGRAM_ID']; ?>">Edit</button>
            
  <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#viewTrainingModal<?php echo $program['PROGRAM_ID']; ?>">View</button>
            </td>
              </tr>
            
              <!-- Repeat more rows here -->
            </tbody>
            <!-- View Training Modal -->
<div class="modal fade" id="viewTrainingModal<?php echo $program['PROGRAM_ID']; ?>" tabindex="-1" aria-labelledby="viewTrainingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title">View Training Program Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Program ID:</label>
            <div class="form-control-plaintext"><?php echo htmlspecialchars($program['PROGRAM_ID']); ?></div>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Program Type:</label>
            <div class="form-control-plaintext"><?php echo htmlspecialchars($program['PROGRAM_TYPE']); ?></div>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Program Name:</label>
            <div class="form-control-plaintext"><?php echo htmlspecialchars($program['PROGRAM_NAME']); ?></div>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Trainer:</label>
            <div class="form-control-plaintext"><?php echo htmlspecialchars($program['TRAINER']); ?></div>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Start Date:</label>
            <div class="form-control-plaintext"><?php echo htmlspecialchars($program['START_DATE']); ?></div>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">End Date:</label>
            <div class="form-control-plaintext"><?php echo htmlspecialchars($program['END_DATE']); ?></div>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Description:</label>
            <div class="form-control-plaintext"><?php echo htmlspecialchars($program['DESCRIPTION_PROGRAM']); ?></div>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Status:</label>
            <div class="form-control-plaintext"><?php echo htmlspecialchars($program['STATUS']); ?></div>
          </div>
        </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

            <div class="modal fade" id="editTrainingModal<?php echo $program['PROGRAM_ID']; ?>" tabindex="-1" aria-labelledby="editTrainingModalLabel<?php echo $program['PROGRAM_ID']; ?>" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="editTrainingModalLabel<?php echo $program['PROGRAM_ID']; ?>">Edit Training Details (Program ID: <?php echo $program['PROGRAM_ID']; ?>)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form action="updateTrainingProgram.php" method="post">
          <input type="hidden" name="PROGRAM_ID" value="<?php echo $program['PROGRAM_ID']; ?>">

          <div class="mb-3">
            <label for="PROGRAM_TYPE<?php echo $program['PROGRAM_ID']; ?>" class="form-label">Program Type</label>
            <input type="text" class="form-control" id="PROGRAM_TYPE<?php echo $program['PROGRAM_ID']; ?>" name="PROGRAM_TYPE" value="<?php echo htmlspecialchars($program['PROGRAM_TYPE']); ?>">
          </div>

          <div class="mb-3">
            <label for="PROGRAM_NAME<?php echo $program['PROGRAM_ID']; ?>" class="form-label">Program Name</label>
            <input type="text" class="form-control" id="PROGRAM_NAME<?php echo $program['PROGRAM_ID']; ?>" name="PROGRAM_NAME" value="<?php echo htmlspecialchars($program['PROGRAM_NAME']); ?>">
          </div>

          <?php
// Fetch trainer list
$trainerQuery = "SELECT TRAINER_ID, FULLNAME FROM trainer_faculty";
$trainerResult = $connection->query($trainerQuery);
?>

<div class="mb-3">
  <label for="TRAINER<?php echo $program['PROGRAM_ID']; ?>" class="form-label">Trainer</label>
  <select class="form-select" id="TRAINER<?php echo $program['PROGRAM_ID']; ?>" name="TRAINER_I">
    <option value="">Select Trainer</option>
    <?php while($trainer = $trainerResult->fetch_assoc()): ?>
      <option value="<?php echo $trainer['TRAINER_ID']; ?>"
        <?php if($program['FULLNAME'] == $trainer['TRAINER_ID']) echo 'selected'; ?>>
        <?php echo htmlspecialchars($trainer['FULLNAME']); ?>
      </option>
    <?php endwhile; ?>
  </select>
</div>


<div class="mb-3">
  <label for="STATUS<?php echo $program['PROGRAM_ID']; ?>" class="form-label">Status</label>
  <select class="form-select" id="STATUS<?php echo $program['PROGRAM_ID']; ?>" name="STATUS">
    <option value="Cancel" <?php echo $program['STATUS'] == 'Cancel' ? 'selected' : ''; ?>>Cancel</option>
    <option value="Ongoing" <?php echo $program['STATUS'] == 'Ongoing' ? 'selected' : ''; ?>>Ongoing</option>
    <option value="Pending" <?php echo $program['STATUS'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
    <option value="Complete" <?php echo $program['STATUS'] == 'Complete' ? 'selected' : ''; ?>>Complete</option>
  </select>
</div>


          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="START_DATE<?php echo $program['PROGRAM_ID']; ?>" class="form-label">Start Date</label>
              <input type="date" class="form-control" id="START_DATE<?php echo $program['PROGRAM_ID']; ?>" name="START_DATE" value="<?php echo htmlspecialchars($program['START_DATE']); ?>">
            </div>

            <div class="col-md-6 mb-3">
              <label for="END_DATE<?php echo $program['PROGRAM_ID']; ?>" class="form-label">End Date</label>
              <input type="date" class="form-control" id="END_DATE<?php echo $program['PROGRAM_ID']; ?>" name="END_DATE" value="<?php echo htmlspecialchars($program['END_DATE']); ?>">
            </div>
          </div>
          <div class="mb-3">
            <label for="DESCRIPTION_PROGRAM<?php echo $program['PROGRAM_ID']; ?>" class="form-label">Description</label>
            <textarea type="text" class="form-control" id="STATUS<?php echo $program['PROGRAM_ID']; ?>" name="DESCRIPTION_PROGRAM" value="<?php echo htmlspecialchars($program['STATUS']); ?>">
          </textarea>

          <div class="modal-footer">
            <button type="submit" name="submit" class="btn btn-success">Save Changes</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>

        </form>
      </div>

    </div>
  </div>
</div>


            <?php endwhile;?>
          </table>
          
        </div>
        <!-- Add Training Modal -->
        <?php
// Fetch trainer list
$trainerQuery = "SELECT TRAINER_ID, FULLNAME FROM trainer_faculty";
$trainerResult = $connection->query($trainerQuery);
?>

<div class="modal fade" id="addTrainingModal" tabindex="-1" aria-labelledby="addTrainingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="addTrainingModalLabel">Add New Training Program</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form action="addTrainingProgram.php" method="post">
          
          <div class="mb-3">
            <label for="PROGRAM_TYPE" class="form-label">Program Type</label>
            <input type="text" class="form-control" id="PROGRAM_TYPE" name="PROGRAM_TYPE" placeholder="Enter Program Type">
          </div>

          <div class="mb-3">
            <label for="PROGRAM_NAME" class="form-label">Program Name</label>
            <input type="text" class="form-control" id="PROGRAM_NAME" name="PROGRAM_NAME" placeholder="Enter Program Name">
          </div>

          <div class="mb-3">
            <label for="TRAINER" class="form-label">Trainer</label>
            <select class="form-select" id="TRAINER" name="TRAINER">
              <option value="">Select Trainer</option>
              <?php while($trainer = $trainerResult->fetch_assoc()): ?>
                <option value="<?php echo $trainer['TRAINER_ID']; ?>">
                  <?php echo htmlspecialchars($trainer['FULLNAME']); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="mb-3">
  <label for="STATUS" class="form-label">Status</label>
  <select class="form-select" id="STATUS" name="STATUS">
    <option value="">Select Status</option>
    <option value="Cancel">Cancel</option>
    <option value="Ongoing">Ongoing</option>
    <option value="Pending">Pending</option>
    <option value="Complete">Complete</option>
  </select>
</div>


          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="START_DATE" class="form-label">Start Date</label>
              <input type="date" class="form-control" id="START_DATE" name="START_DATE">
            </div>

            <div class="col-md-6 mb-3">
              <label for="END_DATE" class="form-label">End Date</label>
              <input type="date" class="form-control" id="END_DATE" name="END_DATE">
            </div>
          </div>

          <div class="mb-3">
            <label for="DESCRIPTION_PROGRAM" class="form-label">Description</label>
            <textarea class="form-control" id="DESCRIPTION_PROGRAM" name="DESCRIPTION_PROGRAM" placeholder="Enter Program Description"></textarea>
          </div>

          <div class="modal-footer">
            <button type="submit" name="submit" class="btn btn-success">Add Program</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>

        </form>
      </div>

    </div>
  </div>
</div>
</div>


      <div class="tab-pane fade" id="pillsCalendar" role="tabpanel"> <h3 class="white-text card-title text-center">Training Calendar and Scheduling</h3>
        <br>
        <div style="overflow-y: auto; height: 100%;">
          <div class="row">
            <!-- <div class="col-sm-3">
              <div class="card"  >
                <div class="container">
                  <div class="card-body">
                    <h5 class="card-title">Schedule</h5>

                    <div class="">

                    </div>
                  </div>
                </div>
              </div>
            </div> -->
<div class="col-sm-4">
  <div class="card">
    <div class="card-body">
      <div class="container">
      <h5 class="card-title">Schedule</h5>
    <table class="table table-striped table-hover">
<thead class="table-dark">
  <tr>
    <th>ID</th>
    <th>Start Date</th>
    <th>End Date</th>
    <th>PROGRAM</th>
   
  </tr>
</thead>
<tbody>
<?php
$query = "
SELECT tp.PROGRAM_ID, tp.PROGRAM_TYPE, tp.PROGRAM_NAME, tf.TRAINER_ID, tf.FULLNAME, tp.STATUS, tp.START AS START_DATE, tp.END AS END_DATE
    FROM training_program tp 
    LEFT JOIN trainer_faculty tf ON tp.TRAINER = tf.TRAINER_ID
";
$result=$connection->query($query);
?>
<?php
while ($sched=$result->fetch_assoc()):
?>
  <tr>
    <td><?php echo htmlspecialchars($sched['PROGRAM_ID']);?></td>
    <td><?php echo htmlspecialchars($sched['START_DATE']);?></td>
    <td><?php echo htmlspecialchars($sched['END_DATE']);?></td>
    <td><?php echo htmlspecialchars($sched['PROGRAM_NAME']);?></td>
   


  </tr>

</tbody>
    </table>
    
    
    
    <?php
    endwhile;?>
    
    </div>


    </div>
  </div>
</div>



            <div class="col-sm-8">
              <div class="card" style="height:700px; overflow:hidden;">
                <div class="card-body">
                  <h5 class="card-title">Calendar</h5>
                  <div id="calendar" style=" min-height: 500px;"></div>
                  
                </div>
              </div>
            </div>
          </div>
        </div></div>

        <div class="tab-pane fade" id="pillsEnrollment" role="tabpanel"> 

          <h3 class="white-text card-title text-center">Trainee Enrollment and Approval</h3>
        <br>
        <div style="overflow-y: auto; height: 100%;">
        <div class="row">
  <div class="col-md-2">
    <!-- Vertical Nav Pills -->
    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
    <button class="nav-link new-tab active" id="v-pills-new-tab" data-bs-toggle="pill" data-bs-target="#v-pills-new" type="button" role="tab" aria-controls="v-pills-new" aria-selected="true">New</button>
        <button class="nav-link approved-tab" id="v-pills-approve-tab" data-bs-toggle="pill" data-bs-target="#v-pills-approve" type="button" role="tab" aria-controls="v-pills-approve" aria-selected="false">Approved</button>
        <button class="nav-link rejected-tab" id="v-pills-rejected-tab" data-bs-toggle="pill" data-bs-target="#v-pills-rejected" type="button" role="tab" aria-controls="v-pills-rejected" aria-selected="false">Rejected</button>

    </div>
  </div>

  <div class="col-md-10">
    <!-- Tab Content -->
    <div class="tab-content" id="v-pills-tabContent">
      <div class="tab-pane fade show active" id="v-pills-new" role="tabpanel" aria-labelledby="v-pills-new-tab">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">New Enrollment</h5>
          </div>
        </div>
      </div>

      <div class="tab-pane fade" id="v-pills-approve" role="tabpanel" aria-labelledby="v-pills-approve-tab">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Approved Enrollments</h5>
          </div>
        </div>
      </div>

      <div class="tab-pane fade" id="v-pills-rejected" role="tabpanel" aria-labelledby="v-pills-rejected-tab">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Rejected Enrollments</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
        </div>
        </div>
        </div>

 <div class="tab-pane fade" id="pillsFaculty" role="tabpanel"> 
          
          <h3 class="white-text card-title text-center">Trainer Faculty Management
          </h3>
        <br>
        <div style="overflow-y: auto; height: 100%;">
          <div class="d-grid gap-2 d-md-flex justify-content-md-end"><button type="button" class="btn d-flex text-center btn-primary" data-bs-toggle="modal" data-bs-target="#addTrainerForm">Add Trainer</button></div>
          
          <table class="table table-striped">
            <thead class="table-dark">
              <tr>
                <th>Trainer ID</th>
                <th>Full Name</th>
                <th>Subject</th>
                <th>Action</th>

              </tr>
            </thead>
            <tbody>
<?php
$query="SELECT * FROM trainer_faculty";
$result=$connection->query($query);
?>
<?php
while ($trainer=$result->fetch_assoc()):
?>
              <tr>
                <td><?php echo htmlspecialchars($trainer['TRAINER_ID']);?></td>
                <td><?php echo htmlspecialchars($trainer['FULLNAME']);?></td>
                <td><?php echo htmlspecialchars($trainer['course']);?></td>
                <td>

                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#trainerViewID<?php echo $trainer['TRAINER_ID']?>">View</button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#trainerEditID<?php echo $trainer['TRAINER_ID']?>">Edit</button>
                <button type="button" class="btn btn-danger" onclick="deleteTrainer(<?php echo $trainer['TRAINER_ID']; ?>)">Delete</button>


              </td>
              </tr>

              <div class="modal fade" id="trainerViewID<?php echo $trainer['TRAINER_ID']?>">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Trainer Details</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                      <div class="modal-body">
                        <p><strong>Trainer ID: </strong> <?php echo htmlspecialchars($trainer['TRAINER_ID']);?></p>
                        <p><strong>Full Name: </strong><?php echo htmlspecialchars($trainer['FULLNAME']);?></p>
                        <p><strong>Subject: </strong><?php echo htmlspecialchars($trainer['course']);?></p>
                        <p><strong>Update at: </strong><?php echo htmlspecialchars($trainer['update_at']);?></p>
                        <p><strong>Created at: </strong><?php echo htmlspecialchars($trainer['create_at']);?></p>
                      </div>

                  </div>
                </div>
              </div>


              



              <div class="modal fade" data-bs-Backdrop="static" id="trainerEditID<?php echo $trainer['TRAINER_ID']?>">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Edit Trainer </h5>

                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                
                </div>

                <div class="modal-body">
                  <form action="updateTrainer.php" method="post" enctype="multipart/form-data">
                    <div class="container">
                      <h1 class="text-center">Edit Trainer ID #<?php echo htmlspecialchars($trainer['TRAINER_ID']);?></h1>

                      <input type="hidden" name="TRAINER_ID" value="<?php echo htmlspecialchars($trainer['TRAINER_ID']);?>">

                      <div class="mb-3">
                        <label for="FULLNAME" class="form-label">Full Name</label>
                        <input type="text" name="FULLNAME" id="FULLNAME" value="<?php echo htmlspecialchars($trainer['FULLNAME']);?>" class="form-control">
                      </div>

                      <div class="mb-3">
                        <label for="course" class="form-label">Subject</label>
                        <input type="text" name="course" id="course" value="<?php echo htmlspecialchars($trainer['course']);?>" class="form-control">
                      </div>
                      <div class="modal-footer"><button name="submit" type="submit" class="btn btn-success">Submit</button></div>
                    </div>
                  


                  </form>
                </div>
                
              </div>
            </div>


          </div>


       




              <?php endwhile;?>
            </tbody>
          </table>


        </div>
        <div class="modal fade" id="addTrainerForm" data-bs-Backdrop="static">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Add Trainer</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                    <form action="addTrainer.php" method="post" enctype="multipart/form-data">
                    <div class="container">
                      

                      <input type="hidden" name="TRAINER_ID" placeholder="">

                      <div class="mb-3">
                        <label for="FULLNAME" class="form-label">Full Name</label>
                        <input type="text" name="FULLNAME" id="FULLNAME" placeholder="Full Name" class="form-control">
                      </div>

                      <div class="mb-3">
                        <label for="course" class="form-label">Subject</label>
                        <input type="text" name="course" id="course" placeholder="Subject/Course" class="form-control">
                      </div>
                      <div class="modal-footer"><button name="submit" type="submit" class="btn btn-success">Submit</button></div>
                    </div>
                  


                  </form>

                    </div>
                  </div>
                </div>
              </div>

       
        </div>


        <div class="tab-pane fade" id="pillsRecord" role="tabpanel"> 
          
          <h3 class="white-text card-title text-center">Training Record and Certificate

          </h3>
        <br>
        <div style="overflow-y: auto; height: 100%;">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Trainee ID</th>
                <th>Trainee Name</th>
                <th>Program</th>
               
                <th>Progress</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>John Doe</td>
                <td>Employee Training 101</td>
                <td>Incomplete</td>
                <td>
                <button type="button" class="btn btn-primary btn-sm">Edit</button>
                <button type="button" class="btn btn-info btn-sm">View</button>
                <button type="button" class="btn btn-danger btn-sm">Delete</button>
                <button type="button" class="btn btn-success btn-sm">Generate</button>




                </td>
              </tr>
            </tbody>
          </table>
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
</div><div class="modal fade" id="trainerModal" tabindex="-1" aria-labelledby="trainerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="trainerModalLabel">Trainer Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <h6><strong>Name:</strong> John Doe</h6>
        <p><strong>Email:</strong> johndoe@example.com</p>
        <p><strong>Expertise:</strong> Workplace Safety, HR Compliance</p>

        <hr>
        <h6><strong>Assigned Students:</strong></h6>
        <ul>
          <li>Anna Cruz</li>
          <li>Mark Dela Peña</li>
          <li>Ricky Santos</li>
        </ul>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<div class="modal fade" id="editTrainingModal" tabindex="-1" aria-labelledby="editTrainingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="editTrainingModalLabel">Edit Training Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <!-- Training Info -->
        <form id="editTrainingForm">
        <div class="mb-3">
            <label for="trainingTitle" class="form-label">Program ID</label>
            <input type="number" class="form-control" id="trainingTitle" value="1">
          </div>

        <div class="mb-3">
            <label for="trainingTitle" class="form-label">Program Type</label>
            <input type="text" class="form-control" id="trainingTitle" value="ET101">
          </div>

          <div class="mb-3">
            <label for="trainingTitle" class="form-label">Program Name</label>
            <input type="text" class="form-control" id="trainingTitle" value="Employee Training 101">
          </div>

          <div class="mb-3">
            <label for="trainerName" class="form-label">Trainer</label>
            <input type="text" class="form-control" id="trainerName" value="John Doe">
          </div>
          <div class="form-floating">
  <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
  <label for="floatingTextarea2">Program Description</label>
</div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="startDate" class="form-label">Start Date</label>
              <input type="date" class="form-control" id="startDate" value="2025-05-10">
            </div>
            <div class="col-md-6 mb-3">
              <label for="endDate" class="form-label">End Date</label>
              <input type="date" class="form-control" id="endDate" value="2025-06-10">
            </div>
          </div>
          <div class="mb-3">
            <label for="trainingTitle" class="form-label">Program Type</label>
            <input type="text" class="form-control" id="trainingTitle" value="Employee Training 101">
          </div>


          <hr>

          <!-- Student List -->
          <h6>Participants</h6>
          <ul id="studentList" class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between align-items-center">
              Anna Cruz
              <button type="button" class="btn btn-sm btn-danger">Remove</button>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              Mark Dela Peña
              <button type="button" class="btn btn-sm btn-danger">Remove</button>
            </li>
          </ul>

          <div class="mb-3">
            <label for="newStudent" class="form-label">Add Student</label>
            <input type="text" class="form-control mb-2" id="newStudent" placeholder="Enter student name">
            <button type="button" class="btn btn-secondary" onclick="addStudent()">Add</button>
          </div>
        </form>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-success">Save Changes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>

    </div>
  </div>
</div>

<!-- <nav class="navbar navbar-blue" style="height:70px;">
<div class="container">
    <a href="#!" class="navbar-brand" data-bs-toggle="offcanvas" aria-controls="staticBackdrop" data-bs-target="#sideBarNav" >
        <img src="logo.png" style="margin-top:-25px;" width="90" height="90" alt=""> <span class="white-text" style=" ">HR 2</span></a></div>
</nav> -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="../mdb/js/mdb.es.min.js"></script>

    
    <script>
     
function deleteTrainer(trainerID) {
    if (confirm("Are you sure you want to delete Trainer ID #" + trainerID + "?")) {
        const formData = new FormData();
        formData.append('TRAINER_ID', trainerID);

        fetch('deleteTrainer.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload(); // Refresh table after delete
        })
        .catch(error => console.error('Error:', error));
    }
}



window.addEventListener('scroll',function(){
    let sidenav=document.querySelector('.bg-side');
    if (window.scrollY>50){
        sidenav.classList.add('fixed-sidebar');
    }else{
        sidenav.classList.remove('fixed-sidebar');
    }
});




document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    timeZone: 'UTC',
    initialView: 'dayGridMonth', // default to month view
    editable: true,
    selectable: true,
    events: function(fetchInfo, successCallback, failureCallback) {
      // Fetch events when the calendar loads
      fetch('fetch_sched.php', {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json'
        }
      })
      .then(response => response.json())
      .then(data => {
        // Pass the data to FullCalendar's success callback
        successCallback(data);
      })
      .catch(error => {
        // Handle any errors that occur during the fetch
        failureCallback(error);
      });
    },

    // Custom toolbar buttons
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },

    // Selection for new event
    select: function(info) {
      var title = prompt('Enter Event Title:');
      if (title) {
        const newEvent = {
          title: title,
          start: info.startStr,
          end: info.endStr
        };

        calendar.addEvent(newEvent);

        fetch('add-event.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(newEvent)
        });
      }
      calendar.unselect();
    },

    eventDrop: function(info) {
      updateEvent(info.event);
    },

    eventResize: function(info) {
      updateEvent(info.event);
    }
  });

  // Render the calendar immediately after initializing
  calendar.render();

  // Update event in the database
  function updateEvent(event) {
    fetch('update-event.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        id: event.id,
        title: event.title,
        start: event.start.toISOString(),
        end: event.end ? event.end.toISOString() : null
      })
    });
  }
});


    </script>


</body>
</html>