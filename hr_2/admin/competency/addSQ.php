<?php
include '../../../phpcon/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $skill_id = $_POST['SKILL_ID'];
   
    $skill = $_POST['SKILL'];
    $position = $_POST['POSITION'];
  

    $stmt = $connection->prepare("INSERT INTO skill_qualification ( SKILL,POSITION ) VALUES (?, ?)");
    $stmt->bind_param("ss", $skill,  $position);

    if ($stmt->execute()) {
        header("Location: competency.php"); // redirect to the correct tab
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
}
?>
