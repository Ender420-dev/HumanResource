<?php 
    $Connection = mysqli_connect ("localhost:3306","root","","hr2");
        if(mysqli_connect_errno()){
            echo"failed to connect in mySQL:" .mysqli_connect_error();
        }else{
            echo"";
        }

        $connections = mysqli_connect("localhost:3307", "root", "", "hr1");
$hr1_conn_error_msg = ""; // Optional: store specific error message
if (!$connections) {
    // error_log("Failed to connect to hr1: " . mysqli_connect_error()); // Log to PHP error log
    $hr1_conn_error_msg = "Failed to connect to hr1: " . mysqli_connect_error(); // For script to check
}

$connections_hr4 = mysqli_connect("localhost:3307", "root", "", "hr4");
$hr4_conn_error_msg = ""; // Optional: store specific error message
if (!$connections_hr4) {
    // error_log("Failed to connect to hr4: " . mysqli_connect_error()); // Log to PHP error log
    $hr4_conn_error_msg = "Failed to connect to hr4: " . mysqli_connect_error(); // For script to check
}

?>