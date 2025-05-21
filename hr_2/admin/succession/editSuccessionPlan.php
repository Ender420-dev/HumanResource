// editSuccessionPlan.php
<?php
include '../../../phpcon/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['SUCCESSION_ID'];
    $currentRole = $_POST['CURRENT_ROLE'];
    $actions = $_POST['DEVELOPMENT_ACTIONS'];
    $targetDate = $_POST['TARGET_READINESS_DATE'];
    $progress = $_POST['PROGRESS'];

    $stmt = $connection->prepare("UPDATE succession_plan_dev SET CURRENT_ROLE = ?, DEVELOPMENT_ACTIONS = ?, TARGET_READINESS_DATE = ?, PROGRESS = ? WHERE SUCCESSION_ID = ?");
    $stmt->bind_param("sssii", $currentRole, $actions, $targetDate, $progress, $id);

    if ($stmt->execute()) {
        header("Location: succession.php?updated=1");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
}
?>