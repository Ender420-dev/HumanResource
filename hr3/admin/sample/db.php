<?php
$conn_hr1 = mysqli_connect("localhost:3307", "root", "", "samplehr1");
if (!$conn_hr1) {
    die("Failed to connect to hr1: " . mysqli_connect_error());
}

$conn_hr2 = mysqli_connect("localhost:3307", "root", "", "samplehr2");
if (!$conn_hr2) {
    die("Failed to connect to hr2: " . mysqli_connect_error());
}
?>