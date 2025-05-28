<?php
$host = 'localhost:3307';
$username = 'root';
$password = '#mayeskwoel^Xyz098';
$database = 'hr4';

$conn = new mysqli($host, $username, $password, $database);

$hr1 = new mysqli($host, $username, $password, 'hr1');

$hr3 = new mysqli($host, $username, $password, 'hr3');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else{
    // echo "Connected successfully";
}
