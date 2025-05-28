<?php
// Connection for hr1
$connections = mysqli_connect("localhost:3307", "root", "", "hr1");
$hr1_conn_error_msg = ""; // Optional: store specific error message
if (!$connections) {
    // error_log("Failed to connect to hr1: " . mysqli_connect_error()); // Log to PHP error log
    $hr1_conn_error_msg = "Failed to connect to hr1: " . mysqli_connect_error(); // For script to check
}

$connections_hr2 = mysqli_connect("localhost:3307", "root", "", "hr2"); // Adjust as needed
$hr2_conn_error_msg = "";
if (!$connections_hr2) {
    $hr2_conn_error_msg = "Failed to connect to hr2: " . mysqli_connect_error();
}

$connections_hr3 = mysqli_connect("localhost:3307", "root", "", "hr3");
$hr3_conn_error_msg = ""; // Optional: store specific error message
if (!$connections_hr3) {
    // error_log("Failed to connect to hr4: " . mysqli_connect_error()); // Log to PHP error log
    $hr3_conn_error_msg = "Failed to connect to hr3: " . mysqli_connect_error(); // For script to check
}

// Connection for hr4
$connections_hr4 = mysqli_connect("localhost:3307", "root", "", "hr4");
$hr4_conn_error_msg = ""; // Optional: store specific error message
if (!$connections_hr4) {
    // error_log("Failed to connect to hr4: " . mysqli_connect_error()); // Log to PHP error log
    $hr4_conn_error_msg = "Failed to connect to hr4: " . mysqli_connect_error(); // For script to check
}

?>