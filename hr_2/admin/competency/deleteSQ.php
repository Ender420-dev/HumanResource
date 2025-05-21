<?php
include '../../../phpcon/conn.php';

if (isset($_POST['SKILL_ID'])) {
    $skill_id = $_POST['SKILL_ID'];

    $stmt = $connection->prepare("DELETE FROM skill_qualification WHERE SKILL_ID = ?");
    $stmt->bind_param("i", $skill_id);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
}
?>
