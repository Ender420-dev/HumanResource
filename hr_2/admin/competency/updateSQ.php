<?php
include '../../../phpcon/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $skill_id = $_POST['SKILL_ID'];
   
    $skill = $_POST['SKILL'];
    $position = $_POST['POSITION'];
  

    $stmt = $connection->prepare("UPDATE skill_qualification SET   SKILL = ?,  POSITION = ? WHERE SKILL_ID = ?");
    $stmt->bind_param("ssi", $skill, $position, $skill_id);

    if ($stmt->execute()) {
        header("Location: competency.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
}
?>
