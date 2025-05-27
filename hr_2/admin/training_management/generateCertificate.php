<?php
include '../../../phpcon/conn.php';

if (!isset($_GET['lp_id'])) {
    echo "Certificate ID missing.";
    exit;
}

$lp_id = intval($_GET['lp_id']);

$query = "
    SELECT 
        e.name AS TRAINEE_NAME,
        tp.PROGRAM_NAME,
        lp.END
    FROM learning_progress lp
   LEFT JOIN hr1.applicant e ON lp.EMPLOYEE_ID = e.applicantID

    LEFT JOIN training_program tp ON lp.COURSE = tp.PROGRAM_ID
    WHERE lp.LP_ID = ?
";

$stmt = $connection->prepare($query);
$stmt->bind_param("i", $lp_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()):
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate of Completion</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            text-align: center;
            padding: 50px;
            background: #f9f9f9;
        }
        .certificate {
            border: 10px solid #ddd;
            padding: 50px;
            background: #fff;
            max-width: 800px;
            margin: auto;
        }
        h1 {
            font-size: 2.5em;
            color: #4a4a4a;
        }
        .name {
            font-size: 2em;
            margin: 20px 0;
            font-weight: bold;
        }
        .program {
            font-size: 1.5em;
            color: #555;
        }
        .date {
            margin-top: 40px;
            font-size: 1em;
            color: #777;
        }
    </style>
</head>
<body>

<div class="certificate">
    <h1>Certificate of Completion</h1>
    <p>This is to certify that</p>
    <div class="name"><?= htmlspecialchars($row['TRAINEE_NAME']) ?></div>
    <p>has successfully completed the training program</p>
    <div class="program"><?= htmlspecialchars($row['PROGRAM_NAME']) ?></div>
    <div class="date">Date Completed: <?= date("F j, Y", strtotime($row['END'])) ?></div>
</div>

</body>
</html>

<?php
else:
    echo "No certificate data found.";
endif;

$stmt->close();
?>
