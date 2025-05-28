<?php
// save_schedule.php
// Handles creating a new scheduled report entry in the database.

session_start(); // Start the session if you use session messages
include_once '../connections.php'; // Include your database connection file

// Set header to indicate JSON response
header('Content-Type: application/json');

// Initialize response array
$response = ['success' => false, 'message' => ''];

// Enable error reporting for debugging (REMOVE IN PRODUCTION)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the raw POST data (JSON string)
$input = file_get_contents('php://input');
$data = json_decode($input, true); // Decode JSON into an associative array

// Check for JSON decoding errors
if (json_last_error() !== JSON_ERROR_NONE) {
    $response['message'] = 'Invalid JSON input received: ' . json_last_error_msg();
    echo json_encode($response);
    exit();
}

// Extract data from the decoded JSON array
// Use null coalescing operator (?? '') to prevent "Undefined index" notices
$report_name = trim($data['report_name'] ?? '');
$report_type = trim($data['report_type'] ?? '');
$frequency = trim($data['frequency'] ?? '');
$recipient_email = trim($data['recipient_email'] ?? null); // Use null if empty string or not set

// Validate required fields
if (empty($report_name) || empty($report_type) || empty($frequency)) {
    $response['message'] = 'All required fields (Report Name, Report Type, Frequency) must be filled.';
    echo json_encode($response);
    exit();
}

try {
    // Prepare the SQL INSERT statement
    $stmt = $conn->prepare("
        INSERT INTO scheduled_reports (report_name, report_type, frequency, recipient_email, last_run_date)
        VALUES (:report_name, :report_type, :frequency, :recipient_email, NULL)
    ");

    // Bind parameters
    $stmt->bindParam(':report_name', $report_name);
    $stmt->bindParam(':report_type', $report_type);
    $stmt->bindParam(':frequency', $frequency);
    // Use PDO::PARAM_STR for recipient_email, even if it's null
    $stmt->bindParam(':recipient_email', $recipient_email, PDO::PARAM_STR);

    // Execute the statement
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Report scheduled successfully.';
    } else {
        // This 'else' block might be hit if a constraint fails (e.g., unique key)
        $response['message'] = 'Failed to save report schedule. Database query did not affect any rows.';
        // You could get more detailed error info here if needed: $stmt->errorInfo();
    }

} catch (PDOException $e) {
    // Catch PDO exceptions (database errors)
    $response['message'] = 'Database error: ' . $e->getMessage();
    // For debugging, you might want to log $e->getCode() as well
} catch (Exception $e) {
    // Catch any other general exceptions
    $response['message'] = 'An unexpected error occurred: ' . $e->getMessage();
} finally {
    // Close the database connection
    $conn = null;
}

// Send the JSON response back to the client
echo json_encode($response);
?>