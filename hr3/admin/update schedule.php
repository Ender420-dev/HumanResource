<?php
// update_schedule.php
// Handles updating an existing scheduled report entry in the database.

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
$report_id = $data['report_id'] ?? null; // This is crucial for updating
$report_name = trim($data['report_name'] ?? '');
$report_type = trim($data['report_type'] ?? '');
$frequency = trim($data['frequency'] ?? '');
$recipient_email = trim($data['recipient_email'] ?? null);

// Validate required fields, including the report_id
if (empty($report_id) || empty($report_name) || empty($report_type) || empty($frequency)) {
    $response['message'] = 'All required fields (Report ID, Report Name, Report Type, Frequency) must be filled.';
    echo json_encode($response);
    exit();
}

try {
    // Prepare the SQL UPDATE statement
    $stmt = $conn->prepare("
        UPDATE scheduled_reports
        SET
            report_name = :report_name,
            report_type = :report_type,
            frequency = :frequency,
            recipient_email = :recipient_email,
            updated_at = CURRENT_TIMESTAMP() -- Update the timestamp
        WHERE
            report_id = :report_id
    ");

    // Bind parameters
    $stmt->bindParam(':report_name', $report_name);
    $stmt->bindParam(':report_type', $report_type);
    $stmt->bindParam(':frequency', $frequency);
    $stmt->bindParam(':recipient_email', $recipient_email, PDO::PARAM_STR);
    $stmt->bindParam(':report_id', $report_id, PDO::PARAM_INT); // Bind as integer

    // Execute the statement
    if ($stmt->execute()) {
        // Check if any rows were actually updated
        if ($stmt->rowCount() > 0) {
            $response['success'] = true;
            $response['message'] = 'Report updated successfully.';
        } else {
            $response['message'] = 'Report not found or no changes were made.';
        }
    } else {
        $response['message'] = 'Failed to update report schedule. Database query did not affect any rows.';
    }

} catch (PDOException $e) {
    // Catch PDO exceptions (database errors)
    $response['message'] = 'Database error: ' . $e->getMessage();
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