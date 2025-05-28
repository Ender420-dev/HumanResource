<?php
// connections.php
// Database connections for hr1, hr2, hr3, and hr4

// --- Configuration for each database (adjust as needed) ---
$db_configs = [
    'hr1' => [
        'host' => 'localhost',
        'port' => '3307', // Common MySQL/MariaDB port
        'username' => 'root',
        'password' => '', // Your password for hr1
        'db_name' => 'hr1'
    ],
    'hr2' => [
        'host' => 'localhost',
        'port' => '3307',
        'username' => 'root',
        'password' => '', // Your password for hr2
        'db_name' => 'hr2'
    ],
    'hr3' => [
        'host' => 'localhost',
        'port' => '3307',
        'username' => 'root',
        'password' => '', // Your password for hr3
        'db_name' => 'hr3'
    ],
    'hr4' => [
        'host' => 'localhost',
        'port' => '3307',
        'username' => 'root',
        'password' => '', // Your password for hr4
        'db_name' => 'hr4'
    ]
];

// --- Initialize connection variables to null ---
$conn_hr1 = null;
$conn_hr2 = null;
$conn_hr3 = null;
$conn_hr4 = null;

// --- Establish connections ---
foreach ($db_configs as $db_key => $config) {
    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['db_name']};charset=utf8mb4";
    $conn_var_name = "conn_" . $db_key; // e.g., conn_hr1, conn_hr2

    try {
        ${$conn_var_name} = new PDO($dsn, $config['username'], $config['password']);
        ${$conn_var_name}->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        ${$conn_var_name}->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        // echo "Connected successfully to {$config['db_name']} database.<br>"; // For testing, remove in production
    } catch (PDOException $e) {
        // Log the error for debugging (DO NOT echo detailed errors in production)
        error_log("Database connection failed for {$config['db_name']}: " . $e->getMessage());

        // Display a user-friendly error message, but don't halt the script unless critical
        echo "<div class='alert alert-danger'>Error connecting to {$config['db_name']} database. Please check configuration.</div>";

        // If a specific database is absolutely critical for the script to run, you can die here.
        // For example, if 'hr4' is essential for timesheets, you might add:
        // if ($db_key === 'hr4') {
        //      die("Critical connection to HR4 failed. Please contact support.");
        // }
    }
}

// Optional: For existing scripts that expect a general $conn variable,
// you might alias one of the connections here.
// For the 'all timesheets.php' script, we'll assume hr3 is the primary as per the script's original name.
$conn = $conn_hr3; // FIX: Changed from $conn_hr4 to $conn_hr3
?>