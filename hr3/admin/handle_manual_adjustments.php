<?php
session_start();
require_once '../connections.php'; // Include your PDO DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['adjustments']) || !is_array($_POST['adjustments'])) {
        $_SESSION['message'] = "No requests selected.";
        header("Location: pending approval.php");
        exit();
    }

    $action = $_POST['action']; // should be either 'approve' or 'reject'
    $adjustments = $_POST['adjustments']; // array of adjustment_id

    $status = '';
    if ($action === 'approve') {
        $status = 'Approved';
    } elseif ($action === 'reject') {
        $status = 'Rejected';
    } else {
        $_SESSION['message'] = "Invalid action.";
        header("Location: pending approval.php");
        exit();
    }

    // Prepare update query
    $placeholders = implode(',', array_fill(0, count($adjustments), '?'));
    $sql = "UPDATE ManualAdjustments SET status = ? WHERE adjustment_id IN ($placeholders)";
    $stmt = $conn->prepare($sql);

    // Merge action with adjustment IDs
    $params = array_merge([$status], $adjustments);
    try {
        $stmt->execute($params);
        $_SESSION['message'] = "Requests successfully {$status}.";
    } catch (Exception $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
    }

    header("Location: pending approval.php");
    exit();
}
?>
