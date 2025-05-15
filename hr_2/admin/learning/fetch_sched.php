<?php
include '../../../phpcon/conn.php';

$query = "
SELECT tp.PROGRAM_ID, tp.PROGRAM_NAME, tp.START AS start_date, tp.END AS end_date, tf.FULLNAME AS trainer
FROM training_program tp 
LEFT JOIN trainer_faculty tf ON tp.TRAINER = tf.TRAINER_ID
";
$result = $connection->query($query);

$events = [];

while ($row = $result->fetch_assoc()) {
    // Adjust end date to be exclusive (+1 day)
    $endDate = date('Y-m-d', strtotime($row['end_date'] . ' +1 day'));

    $events[] = [
        'id' => $row['PROGRAM_ID'],
        'title' => $row['PROGRAM_NAME'],
        'start' => $row['start_date'],
        'end' => $endDate,
        'allDay' => true // This makes it span the whole day(s) like a long event
    ];
}

echo json_encode($events);
?>
