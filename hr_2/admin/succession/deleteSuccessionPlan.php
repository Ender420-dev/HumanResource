
// deleteSuccessionPlan.php
<?php
include '../../../phpcon/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['SUCCESSION_ID'];

    $stmt = $connection->prepare("DELETE FROM succession_plan_dev WHERE SUCCESSION_ID = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: succession.php?deleted=1");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
}
?>