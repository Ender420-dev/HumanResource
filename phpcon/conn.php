
<?php
// Connect to HR2
$connection = mysqli_connect("localhost:3307", "root", "", "hr2");
if (!$connection) {
    die("Failed to connect to HR2: " . mysqli_connect_error());
}

// Connect to HR1
$connection_hr1 = mysqli_connect("localhost:3307", "root", "", "hr1");
if (!$connection_hr1) {
    die("Failed to connect to HR1: " . mysqli_connect_error());
}


// Connect to HR4
$connection_hr4 = mysqli_connect("localhost:3307", "root", "", "hr4");
if (!$connection_hr4) {
    die("Failed to connect to HR4: " . mysqli_connect_error());
}
?>


